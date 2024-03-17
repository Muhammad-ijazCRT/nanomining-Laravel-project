<?php

namespace App\Http\Controllers\User;

use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use App\Models\UserCoinBalance;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Constants\Status;
use App\Models\ActiveMinedCoin;
use App\Models\Deposit;
use App\Models\Miner;
use App\Models\Order;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class OrderPlanController extends Controller
{
    public function plans()
    {
        $pageTitle = "Mining Plans";
        $miners    = Miner::with('activePlans')->whereHas('activePlans')->get();
        return view($this->activeTemplate . 'user.plans.index', compact('pageTitle', 'miners'));
    }

    public function orderPlan(Request $request)
    {
        $request->validate([
            'plan_id'        => 'required|exists:plans,id',
            'payment_method' => 'required|integer|between:1,2',
        ], [
            'payment_method.required' => 'Please Select a Payment System',
        ]);

        $plan = Plan::active()->with('miner')->findOrFail($request->plan_id);
        // return $plan;
        $user = auth()->user();

        if ($request->payment_method == 1 && $user->balance < $plan->price) {
            $notify[] = ['error', 'Insufficient balance'];
            return back()->withNotify($notify);
        }
        // if(Order::where('user_id', $user->id)->where('plan_id', $plan->id)->exists())
        // { 
        //     $notify[] = ['error', 'Sorry, This item is limited to only 1 purchase'];
        //     return back()->withNotify($notify);
        // }

        $planDetails = [
            'title'        => $plan->title,
            'miner'        => $plan->miner->name,
            'speed'        => $plan->speed . ' ' . $plan->speedUnitText,
            'period'       => $plan->period . ' ' . $plan->periodUnitText,
            'period_value' => $plan->period,
            'period_unit'  => $plan->period_unit,
        ];

        $order                     = new Order();
        $order->trx                = getTrx();
        $order->user_id            = $user->id;
        $order->plan_details       = $planDetails;
        $order->amount             = $plan->price;
        $order->min_return_per_day = $plan->min_return_per_day;
        $order->max_return_per_day = $plan->max_return_per_day ?? $plan->min_return_per_day;
        $order->miner_id           = $plan->miner->id;
        $order->maintenance_cost   = $plan->maintenance_cost;
        $period                    = totalPeriodInDay($plan->period, $plan->period_unit);
        $order->period             = $period;
        $order->period_remain      = $period;
        $order->plan_id      = $plan->id;



        if ($request->payment_method == 1) {
            $order->status        = Status::ORDER_APPROVED;
            $user->miner = Status::ACTIVE_MINER;
            $order->save();

            //Check If Exists
            $ucb = UserCoinBalance::where('user_id', $user->id)->where('miner_id', $order->miner_id)->firstOrCreate([
                'user_id'  => $user->id,
                'miner_id' => $order->miner_id,
            ]);

            $user->balance -= $order->amount;
            $user->save();


            $general  = gs();
            $referrer = $user->referrer;
            if ($general->referral_system && $referrer) {
                levelCommission($user, $order->amount, $order->trx);
            }

            $transaction               = new Transaction();
            $transaction->user_id      = $order->user_id;
            $transaction->amount       = getAmount($order->amount);
            $transaction->charge       = 0;
            $transaction->currency     = $general->cur_text;
            $transaction->post_balance = $user->balance;
            $transaction->trx_type     = '-';
            $transaction->details      = 'Paid to buy a plan';
            $transaction->remark       = 'payment';
            $transaction->trx          = $order->trx;
            $transaction->save();



            notify($user, 'PAYMENT_VIA_USER_BALANCE', [
                'plan_title'      => $plan->title,
                'amount'          => showAmount($order->amount),
                'method_currency' => $general->cur_text,
                'post_balance'    => showAmount($user->balance),
                'method_name'     => $general->cur_text . ' Balance',
                'order_id'        => $order->trx,
            ]);



            $notify[] = ['success', 'Plan purchased successfully.'];


            $activeMiningCoin = new ActiveMinedCoin;
            $activeMiningCoin->mined_coins = 0;
            $activeMiningCoin->order_id = $order->id;
            $activeMiningCoin->save();

            // =========================================================
            return redirect()->route('user.plans.purchased')->withNotify($notify);
        } else {
            $order->status = Status::ORDER_UNPAID;
            $order->save();
            return redirect()->route('user.payment', encrypt($order->id));
        }
    }
    public function miningTracks()
    {
        // ========================================
        // Using the request() function
        $npId = request('NP_id');
        // dd($npId);
        if ($npId) {

            $curl = curl_init();

            $url = 'https://api-sandbox.nowpayments.io/v1/payment/' . $npId;
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'x-api-key: Z8NEK31-H5B49SZ-P7KBT64-09FPAVK'
                ),
            ));

            $response = curl_exec($curl);


            curl_close($curl);

            $response = json_decode($response);


            // $context = substr(strstr($response['$orderId'], 'RGDBP-'), strlen('RGDBP-'));

            // dd($response->order_id);


            $trx = substr(strstr($response->order_id, 'RGDBP-'), strlen('RGDBP-'));

            // dd($trx);
            if ($trx) {
                $deposit = Deposit::where('trx', $trx)->first();
                $deposit->status = 1;
                $deposit->save();
            }
            // dd($deposit);


            if ($response->payment_status == "finished") {

                $deposit_currency = Miner::where('coin_code', strtoupper($response->pay_currency))->first();

                $user_coin_balances = UserCoinBalance::where("user_id", auth()->user()->id)->where('miner_id', $deposit_currency->id)->first();

                if ($user_coin_balances) {
                    $user_coin_balances->balance += $response->outcome_amount;
                    $user_coin_balances->save();
                } else {
                    $user = User::where('id',  auth()->user()->id)->first();
                    $user->balance += $response->price_amount;
                    $user->save();
                }
            }
            // return $response->payment_status;
            // return $response;
            // dd($response);
            // echo $response;

            $notify[] = ['success', 'Amount is deposit successfully.'];
            return redirect()->route('user.home')->withNotify($notify);
        }

        // =============================================================

        $pageTitle = "Mining Tracks";
        $orders    = Order::where('user_id', auth()->id())->with('miner')->orderBy('id', 'desc')->paginate(getPaginate(5));
        return view($this->activeTemplate . 'user.plans.purchased', compact('pageTitle', 'orders'));
    }
}
