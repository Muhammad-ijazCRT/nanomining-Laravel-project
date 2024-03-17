<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\GeneralSetting;
use App\Models\Transaction;
use App\Models\UserCoinBalance;
use App\Models\Withdrawal;
use Illuminate\Http\Request;

class WithdrawController extends Controller
{

    public function withdrawMoney()
    {
        $withdrawMethod = UserCoinBalance::where('user_id', auth()->id())->with('miner')->get();
        $pageTitle = 'Withdraw Money';
        return view($this->activeTemplate . 'user.withdraw.methods', compact('pageTitle', 'withdrawMethod'));
    }

    public function withdrawStore(Request $request)
    {
        // return $request->all();
        $request->validate([
            'id'     => 'required|integer|gt:0',
            'amount' => 'required|numeric'
        ]);

        $user   = auth()->user();
        $wallet = UserCoinBalance::where('user_id', $user->id)->with('miner')->findOrFail($request->id);

        return $wallet;
        $minLimit  = $wallet->miner->min_withdraw_limit;
        $maxLimit  = $wallet->miner->max_withdraw_limit;

        $this->validate($request, [
            'amount'    => "numeric|min:$minLimit|max:$maxLimit"
        ]);

        if ($wallet->balance < $request->amount) {
            $notify[] = ['error', 'Insufficient balance'];
            return back()->withNotify($notify);
        }

        if (!$wallet->wallet) {
            $notify[] = ['error', 'No wallet address was provided for this coin.'];
            $notify[] = ['info', 'Kindly update your wallet address.'];
            return back()->withNotify($notify);
        }



        $withdraw                           = new Withdrawal();
        $withdraw->user_coin_balance_id     = $request->id;
        $withdraw->user_id                  = $user->id;
        $withdraw->amount                   = $request->amount;
        $withdraw->trx                      = getTrx();
        $withdraw->currency                 = $wallet->miner->coin_code;
        $withdraw->final_amount             = $request->amount;
        $withdraw->after_charge             = $request->amount;
        $withdraw->status                   = Status::PAYMENT_PENDING;
        $withdraw->save();


        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $user->id;
        $adminNotification->title     = 'New withdraw request from ' . $user->username;
        $adminNotification->click_url = urlPath('admin.withdraw.details', $withdraw->id);
        $adminNotification->save();

        //Decrease the Balance
        $wallet->decrement('balance', $request->amount);

        $transaction               = new Transaction();
        $transaction->user_id      = $withdraw->user_id;
        $transaction->currency     = $wallet->miner->coin_code;
        $transaction->amount       = $withdraw->amount;
        $transaction->post_balance = $wallet->balance;
        $transaction->trx_type     = '-';
        $transaction->details      = showAmount($withdraw->amount, 8, exceptZeros: true) . ' ' . $wallet->miner->coin_code . ' withdrawn to wallet address: ' . $wallet->wallet;
        $transaction->trx          = $withdraw->trx;
        $transaction->remark       = 'withdraw';
        $transaction->save();

        notify($user, 'WITHDRAW_REQUEST', [
            'wallet'        => $wallet->wallet,
            'post_balance'  => showAmount($wallet->balance, 8, exceptZeros: true),
            'amount'        => showAmount($withdraw->amount, 8, exceptZeros: true),
            'coin_code'     => $wallet->miner->coin_code,
            'trx'           => $withdraw->trx

        ]);

        $notify[] = ['success', 'Withdrawal request successfully submitted'];

        return redirect()->route('user.withdraw.preview', encrypt($withdraw->id))->withNotify($notify);
    }

    public function withdrawReferralCommission(Request $request)
    {
        // return $request->all();
        $request->validate([
            'amount' => 'required|numeric'
        ]);

        $user   = auth()->user();
        
        if ($request->amount <= 0) {
            $notify[] = ['error', 'Insufficient balance'];
            return back()->withNotify($notify);
        }

        $general_setting = GeneralSetting::first();
        if ($user->balance < $request->amount) {
            $notify[] = ['error', 'Something Went Wrong! Please contact to support Team'];
            // $notify[] = ['error', 'Insufficient balance'];
            return back()->withNotify($notify);
        }

        
        $minWithdrawLimit = $user->ref_com_withdraw_min;

        if($general_setting->global_ref_com_withdraw == '1'){
            if ($request->amount < $general_setting->ref_com_withdraw_min) {
                $notify[] = ['error', sprintf('Minimum withdrawal amount is %s USD.', $general_setting->ref_com_withdraw_min)];
                return back()->withNotify($notify);
            }
        }else{
              if ($request->amount < $minWithdrawLimit) {
                $notify[] = ['error', sprintf('Minimum withdrawal amount is %s USD.', $minWithdrawLimit)];
                return back()->withNotify($notify);
            }
        }
        

        
        $maxWithdrawLimit = $user->ref_com_withdraw_mix;

        if($general_setting->global_ref_com_withdraw == '1'){
            if ($request->amount > $general_setting->ref_com_withdraw_min) {
                $notify[] = ['error', sprintf('Withdrawal amount exceeds the maximum limit of %s USD.', $general_setting->ref_com_withdraw_mix)];
                return back()->withNotify($notify);
            }
        }else{
            if ($request->amount > $maxWithdrawLimit) {
                $notify[] = ['error', sprintf('Withdrawal amount exceeds the maximum limit of %s USD.', $maxWithdrawLimit)];
                return back()->withNotify($notify);
            }
        }
        


        if (!$user->trc20_address) {
            $notify[] = ['error', 'No wallet address was provided for this coin.'];
            $notify[] = ['info', 'Kindly update your wallet address.'];
            return back()->withNotify($notify);
        }



        $withdraw                           = new Withdrawal();
        $withdraw->user_coin_balance_id     = 'USD';
        $withdraw->user_id                  = $user->id;
        $withdraw->amount                   = $request->amount;
        $withdraw->trx                      = getTrx();
        $withdraw->currency                 = 'USD';
        $withdraw->final_amount             = $request->amount;
        $withdraw->after_charge             = $request->amount;
        $withdraw->status                   = Status::PAYMENT_PENDING;
        $withdraw->save();


        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $user->id;
        $adminNotification->title     = 'New withdraw request from ' . $user->username;
        $adminNotification->click_url = urlPath('admin.withdraw.details', $withdraw->id);
        $adminNotification->save();

        //Decrease the Balance
        $user->balance -= $request->amount;
        $user->referral_commission -= $request->amount;



        $transaction               = new Transaction();
        $transaction->user_id      = $withdraw->user_id;
        $transaction->currency     = 'USD';
        $transaction->amount       = $withdraw->amount;
        $transaction->post_balance = $user->balance;
        $transaction->trx_type     = '-';
        $transaction->details      = showAmount($withdraw->amount, 8, exceptZeros: true) . ' ' . $user->balance . ' withdrawn to wallet address: ' . $user->trc20_address;
        $transaction->trx          = $withdraw->trx;
        $transaction->remark       = 'withdraw';
        $transaction->save();

        $user->save();

        notify($user, 'WITHDRAW_REQUEST', [
            'wallet'        => $user->trc20_address,
            'post_balance'  => showAmount($user->balance, 8, exceptZeros: true),
            'amount'        => showAmount($withdraw->amount, 8, exceptZeros: true),
            'coin_code'     => "USD",
            'trx'           => $withdraw->trx

        ]);

        $notify[] = ['success', 'Withdrawal request successfully submitted'];
        return redirect()->back()->withNotify($notify);
        // return redirect()->route('user.withdraw.preview', encrypt($withdraw->id))->withNotify($notify);
    }

    public function withdrawPreview($id)
    {
        try {
            $id = decrypt($id);
        } catch (\Throwable $th) {
            abort('404');
        }
        $withdraw = Withdrawal::with('user')->where('status', Status::PAYMENT_PENDING)->orderBy('id', 'desc')->findOrFail($id);
        $pageTitle = 'Withdraw Preview';
        return view($this->activeTemplate . 'user.withdraw.preview', compact('pageTitle', 'withdraw'));
    }

    public function withdrawLog()
    {
        $pageTitle = "My Withdrawals";
        $withdraws = Withdrawal::where('user_id', auth()->id());

        if (request()->search) {
            $withdraws = $withdraws->where(function ($query) {
                $query->where('trx', request()->search)->orWhereHas('userCoinBalance', function ($query) {
                    $query->where('wallet', 'like', '%' . request()->search . '%');
                });
            });
        }

        $withdraws = $withdraws->with('userCoinBalance.miner')->orderBy('id', 'desc')->paginate(getPaginate());

        return view($this->activeTemplate . 'user.withdraw.log', compact('pageTitle', 'withdraws'));
    }
}