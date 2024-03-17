<?php

namespace App\Http\Controllers\Gateway;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Models\AdminNotification;
use App\Models\Deposit;
use App\Models\GatewayCurrency;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserCoinBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{


    public function deposit()
    {
        $pageTitle = 'Deposit Methods';
        $gatewayCurrency = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', Status::ENABLE);
        })->with('method')->orderBy('method_code')->get();

        // return $gatewayCurrency;

        return view($this->activeTemplate . 'user.payment.deposit', compact('gatewayCurrency', 'pageTitle'));
    }









    public function index()
    {
        $pageTitle = 'Deposit History';
        $deposits = Deposit::where('user_id', Auth::user()->id)->get();
        return view($this->activeTemplate . 'user.deposit.index', compact('deposits', 'pageTitle'));
    }

    public function DepositHistory()
    {
        $pageTitle = 'Deposit History';
        $deposits = Deposit::where('user_id', Auth::user()->id)->paginate(getPaginate(5));
        // return $deposits;
        return view($this->activeTemplate . 'user.deposit.index', compact('deposits', 'pageTitle'));
    }

    public function payment($id)
    {
        try {
            $orderId = decrypt($id);
        } catch (\Throwable $th) {
            abort(404);
        }

        $order = Order::unpaid()->findOrFail($orderId);

        $gatewayCurrency = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', Status::ENABLE);
        })->with('method')->orderby('method_code')->get();

        $pageTitle = 'Payment Methods';

        return view($this->activeTemplate . 'user.payment.deposit', compact('gatewayCurrency', 'pageTitle', 'order'));
    }

    public function depositInsert(Request $request)
    {
        $request->validate([
            'amount'   => 'required|numeric|gt:0',
            'gateway'  => 'required',
            'currency' => 'required',
            'order'    => 'nullable|integer|gt:0',
        ]);

        $order        = null;
        $amount       = $request->amount;
        $notification = 'Please follow deposit limit';

        if ($request->has('order')) {
            $order = Order::unpaid()->find($request->order);
            if (!$order) {
                $notify[] = ['error', 'Order not found!'];
                return back()->withNotify($notify);
            }
            $amount       = $order->amount;
            $notification = 'Please follow payment limit';
        }

        $user = auth()->user();

        $gate = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', Status::ENABLE);
        })->where('method_code', $request->gateway)->where('currency', $request->currency)->first();
        if (!$gate) {
            $notify[] = ['error', 'Invalid gateway'];
            return back()->withNotify($notify);
        }

        if ($gate->min_amount > $amount || $gate->max_amount < $amount) {
            $notify[] = ['error', $notification];
            return back()->withNotify($notify);
        }

        $charge    = $gate->fixed_charge + ($amount * $gate->percent_charge / 100);
        $payable   = $amount + $charge;
        $final_amo = $payable * $gate->rate;

        $data                  = new Deposit();
        $data->user_id         = $user->id;
        $data->order_id        = $order ? $order->id : 0;
        $data->method_code     = $gate->method_code;
        $data->method_currency = strtoupper($gate->currency);
        $data->amount          = $amount;
        $data->charge          = $charge;
        $data->rate            = $gate->rate;
        $data->final_amo       = $final_amo;
        $data->btc_amo         = 0;
        $data->btc_wallet      = "";
        $data->trx             = getTrx();
        $data->save();
        session()->put('Track', $data->trx);
        return to_route('user.deposit.confirm');
    }

    public function appDepositConfirm($hash)
    {
        try {
            $id = decrypt($hash);
        } catch (\Exception $ex) {
            return "Sorry, invalid URL.";
        }

        $data = Deposit::where('status', Status::PAYMENT_INITIATE)->findOrFail($id);
        $user = User::findOrFail($data->user_id);
        auth()->login($user);
        session()->put('Track', $data->trx);
        return to_route('user.deposit.confirm');
    }

    public function depositConfirm()
    {
        $track   = session()->get('Track');
        $deposit = Deposit::where('trx', $track)->where('status', Status::PAYMENT_INITIATE)->orderBy('id', 'DESC')->with('gateway')->firstOrFail();

        // dd($deposit->method_code);
        if ($deposit->method_code >= 1000) {
            return to_route('user.deposit.manual.confirm');
        }

        $dirName = $deposit->gateway->alias;
        $new     = __NAMESPACE__ . '\\' . $dirName . '\\ProcessController';


        $data = $new::process($deposit);
        $data = json_decode($data);
        // dd($new);

        if (isset($data->error)) {
            $notify[] = ['error', $data->message];
            return to_route(gatewayRedirectUrl())->withNotify($notify);
        }
        // dd('now working');

        if (isset($data->redirect_url)) {
            // dd($data->redirect_url);
            return redirect($data->redirect_url);
        }

        // for Stripe V3
        if (@$data->session) {
            $deposit->btc_wallet = $data->session->id;
            $deposit->save();
        }

        $pageTitle = 'Deposit Confirm';
        if ($deposit->order_id) {
            $pageTitle = 'Payment Confirm';
        }

        // dd($data);

        return view($this->activeTemplate . $data->view, compact('data', 'pageTitle', 'deposit'));
    }

    public static function userDataUpdate($deposit, $isManual = null)
    {

        if ($deposit->status == Status::PAYMENT_INITIATE || $deposit->status == Status::PAYMENT_PENDING) {

            $general = gs();
            $order   = $deposit->order;

            $deposit->status = Status::PAYMENT_SUCCESS;
            $deposit->save();

            $user = User::find($deposit->user_id);

            $postBalance = $user->balance;

            $postBalance += $deposit->amount;

            $transaction               = new Transaction();
            $transaction->user_id      = $deposit->user_id;
            $transaction->amount       = $deposit->amount;
            $transaction->post_balance = $postBalance;
            $transaction->charge       = $deposit->charge;
            $transaction->trx_type     = '+';
            $transaction->details      = 'Deposit via ' . $deposit->gatewayCurrency()->name;
            $transaction->trx          = $deposit->trx;
            $transaction->currency     = $general->cur_text;
            $transaction->remark       = 'deposit';
            $transaction->save();

            if (!$isManual) {
                $adminNotification            = new AdminNotification();
                $adminNotification->user_id   = $user->id;
                $adminNotification->title     = 'Payment successful via ' . $deposit->gatewayCurrency()->name;
                $adminNotification->click_url = urlPath('admin.payment.successful');
                $adminNotification->save();
            }

            if ($order) {
                $postBalance -= $deposit->amount;
                $order->status        = Status::ORDER_APPROVED;
                $order->save();

                $transaction               = new Transaction();
                $transaction->user_id      = $deposit->user_id;
                $transaction->amount       = $deposit->amount;
                $transaction->post_balance = $user->balance;
                $transaction->charge       = $deposit->charge;
                $transaction->trx_type     = '-';
                $transaction->details      = 'Payment via ' . $deposit->gatewayCurrency()->name;
                $transaction->trx          = $deposit->trx;
                $transaction->currency     = $general->cur_text;
                $transaction->remark       = 'payment';
                $transaction->save();

                session()->put('payment', true);

                //Check If Exists
                UserCoinBalance::where('user_id', $user->id)->where('miner_id', $order->miner_id)->firstOrCreate([
                    'user_id'  => $user->id,
                    'miner_id' => $order->miner_id,
                ]);

                $referrer = $user->referrer;

                if ($general->referral_system && $referrer) {
                    levelCommission($user, $order->amount, $order->trx);
                }

                notify($user, $isManual ? 'PAYMENT_APPROVE' : 'PAYMENT_COMPLETE', [
                    'plan_title'      => $order->plan_details->title,
                    'method_name'     => $deposit->gatewayCurrency()->name,
                    'method_currency' => $deposit->method_currency,
                    'method_amount'   => showAmount($deposit->final_amo),
                    'amount'          => showAmount($deposit->amount),
                    'charge'          => showAmount($deposit->charge),
                    'rate'            => showAmount($deposit->rate),
                    'order_id'        => $deposit->trx,
                ]);
            }
        }
    }

    public function manualDepositConfirm()
    {
        $track = session()->get('Track');
        $data  = Deposit::with('gateway')->where('status', Status::PAYMENT_INITIATE)->where('trx', $track)->first();
        if (!$data) {
            return to_route(gatewayRedirectUrl());
        }
        if ($data->method_code > 999) {

            $pageTitle = 'Deposit Confirm';
            $method    = $data->gatewayCurrency();
            $gateway   = $method->method;
            return view($this->activeTemplate . 'user.payment.manual', compact('data', 'pageTitle', 'method', 'gateway'));
        }
        abort(404);
    }

    public function manualDepositUpdate(Request $request)
    {
        $track   = session()->get('Track');
        $deposit = Deposit::with('gateway', 'order')->where('status', Status::PAYMENT_INITIATE)->where('trx', $track)->first();

        if (!$deposit) {
            return to_route(gatewayRedirectUrl());
        }

        $order = $deposit->order;

        $gatewayCurrency = $deposit->gatewayCurrency();
        $gateway         = $gatewayCurrency->method;
        $formData        = $gateway->form->form_data;

        $formProcessor  = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);
        $request->validate($validationRule);
        $userData = $formProcessor->processFormData($request, $formData);

        $deposit->detail = $userData;
        $deposit->status = Status::PAYMENT_PENDING; // pending
        $deposit->save();

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $deposit->user->id;
        $adminNotification->title     = 'Payment request form ' . $deposit->user->username;
        $adminNotification->click_url = urlPath('admin.payment.details', $deposit->id);
        $adminNotification->save();

        if ($order) {
            $order->status = Status::ORDER_PENDING; //pending
            $order->save();

            $short_code = [
                'method_name'     => $deposit->gatewayCurrency()->name,
                'method_currency' => $deposit->method_currency,
                'method_amount'   => getAmount($deposit->final_amo),
                'amount'          => getAmount($deposit->amount),
                'charge'          => getAmount($deposit->charge),
                'rate'            => getAmount($deposit->rate),
                'trx'             => $deposit->trx,
            ];

            notify($deposit->user, 'PAYMENT_REQUEST', $short_code);
        }

        $notify[] = ['success', 'Your payment request has been taken'];
        return to_route('user.plans.purchased')->withNotify($notify);
    }
}
