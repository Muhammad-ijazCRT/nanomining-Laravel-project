<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Lib\GoogleAuthenticator;
use App\Models\ActiveMinedCoin;
use App\Models\Deposit;
use App\Models\Form;
use App\Models\Miner;
use App\Models\Order;
use App\Models\Referral;
use App\Models\ReferralLog;
use App\Models\TestCorn;
use App\Models\Transaction;
use App\Models\UserCoinBalance;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    public function home()
    {
        $pageTitle     = 'Dashboard';
        $user          = auth()->user();
        $referralBonus = ReferralLog::where('referee_id', $user->id)->sum('amount');

        $miners = Miner::with(['userCoinBalances' => function ($q) {
            return $q->where('user_id', auth()->id());
        }])->whereHas('userCoinBalances', function ($q) {
            return $q->where('user_id', auth()->id());
        })->get();

        // $mining_server = Order::where('user_id', $user->id)->where('status', '1')->first();
        $mining_servers = Order::with('userCoinBalance')->where('user_id', $user->id)->where('status', '1')->latest('created_at')->get();
        // return $mining_servers;
        // return $mining_servers[0]->min_return_per_day;
        $transactions = Transaction::where('user_id', $user->id)->orderBy('id', 'desc')->limit(10)->get();
        return view($this->activeTemplate . 'user.dashboard', compact('pageTitle', 'referralBonus', 'miners', 'transactions', 'user', 'mining_servers'));
    }

    protected static function ForgetSessionData()
    {
        Session::forget('mining_servers');
    }

    public function getMiningServer(Request $request)
    {


        $orders = Order::approved()
            ->with('user', 'miner')
            ->whereHas('user')
            ->where('period_remain', '>=', 1)
            // ->where('last_paid', '<=', Carbon::now()->subHours(24)->toDateTimeString())
            ->get();

        // dd($orders);
        foreach ($orders as $order) {

            // return $order;

            $addAmount = $order->min_return_per_day;
            // $addAmountInSeconds = number_format($addAmount / (60 * 60 * 24), 10);
            // Calculate amount in one minute
            $addAmountInOneMinute = number_format($addAmount / (60 * 24), 10);

            // return $addAmountInOneMinute;

            $ucb = UserCoinBalance::where('user_id', $order->user_id)->where('miner_id', $order->miner_id)->first();
            // return $ucb;

            if (!$ucb) {
                continue;
            }


            // dd($returnAmount);
            // $ucb->balance += 50;
            $ucb->balance += $addAmountInOneMinute;
            $ucb->save();


            $activeMinedCoin = ActiveMinedCoin::where('order_id', $order->id)->first();
            if ($activeMinedCoin) {
                $activeMinedCoin->mined_coins += number_format('5.5', 10);
                $activeMinedCoin->save();
            }

            // if ($order->user) {
            //     Session::forget('mining_servers', $order->user->id);
            // }
        }

        $activeMinedCoin = ActiveMinedCoin::where('order_id', 195)->first();
        // if ($activeMinedCoin) {
        //     $activeMinedCoin->mined_coins += $addAmountInOneMinute;
        //     $activeMinedCoin->save();
        // }

        // $activeMinedCoin = ActiveMinedCoin::where('order_id', 11)->first();
        return  $activeMinedCoin;



        // Session::forget('mining_servers');
        $btc_balance = 0;
        $ltc_balance = 0;

        $user = Auth::user();
        $session_mining_servers = Session('mining_servers');

        // if (!$session_mining_servers) {
        $orders = Order::approved()
            ->where('user_id', $user->id)
            ->with('user', 'miner')
            ->whereHas('user')
            ->where('period_remain', '>=', 1)
            ->with('ActiveMinedCoins')
            // ->where('last_paid', '<=', Carbon::now()->subHours(24)->toDateTimeString())
            ->get();
        // }


        $resultArray = [];
        // Session::forget('mining_servers');
        if ($session_mining_servers) {

            foreach ($session_mining_servers as $order) {

                $addAmount = $order['min_return_per_day'];
                $addAmountInSeconds = floatval(number_format($addAmount / (60 * 60 * 24), 10));
                $ucb = UserCoinBalance::where('user_id', auth()->user()->id)->where('miner_id', $order['miner_currency_id'])->first();
                // return $ucb;

                $miner_machine_id = $order['miner_machine_id'];
                $wallet_amount = floatval($order['wallet_amount']);
                $miner_currency_id = $ucb->miner_id;

                $wallet_amount += $addAmountInSeconds;
                $order['mined_coin'] += $addAmountInSeconds;
                $order['countSecond'] += 1;

                if (isset($order['btc_balance'])) {
                    if ($miner_currency_id == 1) {
                        $order['btc_balance'] += $addAmountInSeconds;
                    }
                }

                if (isset($order['ltc_balance'])) {
                    if ($miner_currency_id == 2) {
                        $order['ltc_balance'] += $addAmountInSeconds;
                        // $ltc_amount += $wallet_amount;
                    }
                }

                $resultArray[] = [
                    'miner_machine_id' => $miner_machine_id,
                    'miner_currency_id' => $miner_currency_id,
                    'wallet_amount' => $wallet_amount,

                    'btc_balance' => $order['btc_balance'] ?? 0,
                    'ltc_balance' => $order['ltc_balance'] ?? 0,

                    'mined_coin' => $order['mined_coin'],
                    'min_return_per_day' => $order['min_return_per_day'],
                    'countSecond' =>  $order['countSecond'],
                ];
            }

            // if ($session_mining_servers[0]['countSecond'] > 59) {
            //     Session::forget('mining_servers');
            //     $data1 = Session::get('mining_servers');
            //     if ($data1) {
            //         UserController::ForgetSessionData();
            //     }

            //     $orders = Order::approved()
            //         ->where('user_id', $user->id)
            //         ->with('user', 'miner')
            //         ->whereHas('user')
            //         ->where('period_remain', '>=', 1)
            //         ->with('ActiveMinedCoins')
            //         // ->where('last_paid', '<=', Carbon::now()->subHours(24)->toDateTimeString())
            //         ->get();

            //     foreach ($orders as $order) {

            //         $btc_balance = 0;
            //         $ltc_balance = 0;

            //         $addAmount = $order->min_return_per_day;
            //         $addAmountInSeconds = number_format($addAmount / (60 * 60 * 24), 10);

            //         // return $order;
            //         $ucb = UserCoinBalance::where('user_id', $order->user_id)->where('miner_id', $order->miner_id)->first();

            //         if (!$ucb) {
            //             continue;
            //         }


            //         $wallet_amount = $ucb->balance;
            //         $miner_machine_id = $order->id;
            //         $miner_currency_id = $ucb->miner_id;
            //         $wallet_amount += $addAmountInSeconds;

            //         $order['ActiveMinedCoins'][0]['mined_coins'] += $addAmountInSeconds;

            //         // return $wallet_amount;

            //         if ($miner_currency_id == 1) {
            //             $btc_balance = $wallet_amount;
            //         }

            //         if ($miner_currency_id == 2) {
            //             $ltc_balance = $wallet_amount;
            //             // $ltc_amount += $wallet_amount;
            //         }

            //         $resultArray[] = [
            //             'miner_machine_id' => $miner_machine_id,
            //             'miner_currency_id' => $miner_currency_id,
            //             'wallet_amount' => $wallet_amount,

            //             'btc_balance' => $btc_balance,
            //             'ltc_balance' => $ltc_balance,

            //             'mined_coin' => $order['ActiveMinedCoins'][0]['mined_coins'],
            //             'min_return_per_day' => $order->min_return_per_day,
            //             'countSecond' => 1,
            //         ];
            //     }
            // } else {
            //     foreach ($session_mining_servers as $order) {

            //         $addAmount = $order['min_return_per_day'];
            //         $addAmountInSeconds = floatval(number_format($addAmount / (60 * 60 * 24), 10));
            //         $ucb = UserCoinBalance::where('user_id', auth()->user()->id)->where('miner_id', $order['miner_currency_id'])->first();
            //         // return $ucb;

            //         $miner_machine_id = $order['miner_machine_id'];
            //         $wallet_amount = floatval($order['wallet_amount']);
            //         $miner_currency_id = $ucb->miner_id;

            //         $wallet_amount += $addAmountInSeconds;
            //         $order['mined_coin'] += $addAmountInSeconds;
            //         $order['countSecond'] += 1;

            //         if (isset($order['btc_balance'])) {
            //             if ($miner_currency_id == 1) {
            //                 $order['btc_balance'] += $addAmountInSeconds;
            //             }
            //         }

            //         if (isset($order['ltc_balance'])) {
            //             if ($miner_currency_id == 2) {
            //                 $order['ltc_balance'] += $addAmountInSeconds;
            //                 // $ltc_amount += $wallet_amount;
            //             }
            //         }

            //         $resultArray[] = [
            //             'miner_machine_id' => $miner_machine_id,
            //             'miner_currency_id' => $miner_currency_id,
            //             'wallet_amount' => $wallet_amount,

            //             'btc_balance' => $order['btc_balance'] ?? 0,
            //             'ltc_balance' => $order['ltc_balance'] ?? 0,

            //             'mined_coin' => $order['mined_coin'],
            //             'min_return_per_day' => $order['min_return_per_day'],
            //             'countSecond' =>  $order['countSecond'],
            //         ];
            //     }
            // }
        } else {
            foreach ($orders as $order) {

                $btc_balance = 0;
                $ltc_balance = 0;

                $addAmount = $order->min_return_per_day;
                $addAmountInSeconds = number_format($addAmount / (60 * 60 * 24), 10);

                // return $order;
                $ucb = UserCoinBalance::where('user_id', $order->user_id)->where('miner_id', $order->miner_id)->first();

                if (!$ucb) {
                    continue;
                }


                $wallet_amount = $ucb->balance;
                $miner_machine_id = $order->id;
                $miner_currency_id = $ucb->miner_id;
                $wallet_amount += $addAmountInSeconds;

                $order['ActiveMinedCoins'][0]['mined_coins'] += $addAmountInSeconds;

                // return $wallet_amount;

                if ($miner_currency_id == 1) {
                    $btc_balance = $wallet_amount;
                }

                if ($miner_currency_id == 2) {
                    $ltc_balance = $wallet_amount;
                    // $ltc_amount += $wallet_amount;
                }

                $resultArray[] = [
                    'miner_machine_id' => $miner_machine_id,
                    'miner_currency_id' => $miner_currency_id,
                    'wallet_amount' => $wallet_amount,

                    'btc_balance' => $btc_balance,
                    'ltc_balance' => $ltc_balance,

                    'mined_coin' => $order['ActiveMinedCoins'][0]['mined_coins'],
                    'min_return_per_day' => $order->min_return_per_day,
                    'countSecond' => 1,
                ];
            }
        } // session data

        // // btc amount 
        $total_btc_amount = 0;
        $total_ltc_amount = 0;

        // return $resultArray;
        // return $order;
        $userBtcBalance = UserCoinBalance::where('user_id', auth()->user()->id)->where('miner_id', 1)->first();
        $userLtcBalance = UserCoinBalance::where('user_id', auth()->user()->id)->where('miner_id', 1)->first();


        foreach ($resultArray as &$server) {
            if ($server['miner_currency_id'] == 1) {
                $total_btc_amount += $server['mined_coin'];
            } else {
                $total_ltc_amount += $server['mined_coin'];
            }
        }

        $khan = $total_btc_amount + $userBtcBalance->balance;
        $khan2 = $total_ltc_amount + $userLtcBalance->balance;
        foreach ($resultArray as &$server) {
            if ($server['miner_currency_id'] == 1) {
                $server['btc_balance'] = $khan;
            } else {
                $server['ltc_balance'] = $khan2;
            }
        }

        // return $resultArray;
        Session::forget('mining_servers');
        Session::put('mining_servers', $resultArray);
        return response()->json($resultArray);
    }






    public function TestCorn(Request $request)
    {
        $corn = new TestCorn;
        $corn->corn += 1;
        $corn->save();
    }


    public function getMiningMachine(Request $request)
    {
        $user = auth()->user();
        $mining_servers = Order::where('user_id', $user->id)->where('status', '1')->latest('created_at')->get();
        return response()->json($mining_servers);
    }


    public function paymentHistory(Request $request)
    {
        $pageTitle = 'Payment History';
        $user = auth()->user();
        $deposits = Deposit::where('user_id', $user->id)->searchable(['trx'])->with(['gateway'])->orderBy('id', 'desc')->paginate(getPaginate());
        return view($this->activeTemplate . 'user.payment_history', compact('pageTitle', 'deposits'));
    }

    public function show2faForm()
    {
        $ga        = new GoogleAuthenticator();
        $user      = auth()->user();
        $secret    = $ga->createSecret();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl($user->username . '@' . gs('site_name'), $secret);
        $pageTitle = '2FA Setting';
        return view($this->activeTemplate . 'user.twofactor', compact('pageTitle', 'secret', 'qrCodeUrl'));
    }

    public function create2fa(Request $request)
    {
        $user = auth()->user();
        $this->validate($request, [
            'key'  => 'required',
            'code' => 'required',
        ]);
        $response = verifyG2fa($user, $request->code, $request->key);
        if ($response) {
            $user->tsc = $request->key;
            $user->ts  = 1;
            $user->save();
            $notify[] = ['success', 'Google authenticator activated successfully'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'Wrong verification code'];
            return back()->withNotify($notify);
        }
    }

    public function disable2fa(Request $request)
    {
        $this->validate($request, [
            'code' => 'required',
        ]);

        $user     = auth()->user();
        $response = verifyG2fa($user, $request->code);
        if ($response) {
            $user->tsc = null;
            $user->ts  = Status::DISABLE;
            $user->save();
            $notify[] = ['success', 'Two factor authenticator deactivated successfully'];
        } else {
            $notify[] = ['error', 'Wrong verification code'];
        }
        return back()->withNotify($notify);
    }

    public function transactions(Request $request)
    {
        $pageTitle    = 'Transactions';
        $remarks      = Transaction::distinct('remark')->orderBy('remark')->get('remark');
        $coins        = Transaction::distinct('currency')->orderBy('currency')->get('currency');
        $transactions = Transaction::where('user_id', auth()->id());

        if ($request->search) {
            $transactions = $transactions->where('trx', $request->search);
        }

        if ($request->type) {
            $transactions = $transactions->where('trx_type', $request->type);
        }

        if ($request->remark) {
            $transactions = $transactions->where('remark', $request->remark);
        }

        if ($request->coin_code) {
            $transactions = $transactions->where('currency', $request->coin_code);
        }

        $transactions = $transactions->orderBy('id', 'desc')->paginate(getPaginate());
        return view($this->activeTemplate . 'user.transactions', compact('pageTitle', 'transactions', 'remarks', 'coins'));
    }

    // CurrentWeekTransaction
    public function CurrentWeekTransaction(Request $request)
    {
        $pageTitle = 'Current Week Transactions';
        $remarks = Transaction::distinct('remark')->orderBy('remark')->get('remark');
        $coins = Transaction::distinct('currency')->orderBy('currency')->get('currency');

        $transactions = Transaction::where('user_id', auth()->id())
            ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);

        if ($request->search) {
            $transactions = $transactions->where('trx', $request->search);
        }

        if ($request->type) {
            $transactions = $transactions->where('trx_type', $request->type);
        }

        if ($request->remark) {
            $transactions = $transactions->where('remark', $request->remark);
        }

        if ($request->coin_code) {
            $transactions = $transactions->where('currency', $request->coin_code);
        }

        $transactions = $transactions->orderBy('id', 'desc')->paginate(getPaginate());

        return view($this->activeTemplate . 'user.transactions', compact('pageTitle', 'transactions', 'remarks', 'coins'));
    }



    public function CurrentMonthTransaction(Request $request)
    {
        $pageTitle = 'Transactions';
        $remarks = Transaction::distinct('remark')->orderBy('remark')->get('remark');
        $coins = Transaction::distinct('currency')->orderBy('currency')->get('currency');

        $transactions = Transaction::where('user_id', auth()->id())
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year);

        if ($request->search) {
            $transactions = $transactions->where('trx', $request->search);
        }

        if ($request->type) {
            $transactions = $transactions->where('trx_type', $request->type);
        }

        if ($request->remark) {
            $transactions = $transactions->where('remark', $request->remark);
        }

        if ($request->coin_code) {
            $transactions = $transactions->where('currency', $request->coin_code);
        }

        $transactions = $transactions->orderBy('id', 'desc')->paginate(getPaginate());

        return view($this->activeTemplate . 'user.transactions', compact('pageTitle', 'transactions', 'remarks', 'coins'));
    }


    public function kycForm()
    {
        if (auth()->user()->kv == Status::KYC_PENDING) {
            $notify[] = ['error', 'Your KYC is under review'];
            return to_route('user.home')->withNotify($notify);
        }
        if (auth()->user()->kv == Status::KYC_VERIFIED) {
            $notify[] = ['error', 'You are already KYC verified'];
            return to_route('user.home')->withNotify($notify);
        }
        $pageTitle = 'KYC Form';
        $form      = Form::where('act', 'kyc')->first();
        return view($this->activeTemplate . 'user.kyc.form', compact('pageTitle', 'form'));
    }

    public function kycData()
    {
        $user      = auth()->user();
        $pageTitle = 'KYC Data';
        return view($this->activeTemplate . 'user.kyc.info', compact('pageTitle', 'user'));
    }

    public function kycSubmit(Request $request)
    {
        $form           = Form::where('act', 'kyc')->first();
        $formData       = $form->form_data;
        $formProcessor  = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);
        $request->validate($validationRule);
        $userData       = $formProcessor->processFormData($request, $formData);
        $user           = auth()->user();
        $user->kyc_data = $userData;
        $user->kv       = Status::KYC_PENDING;
        $user->save();

        $notify[] = ['success', 'KYC data submitted successfully'];
        return to_route('user.home')->withNotify($notify);
    }

    public function attachmentDownload($fileHash)
    {
        $filePath  = decrypt($fileHash);
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $general   = gs();
        $title     = slug($general->site_name) . '- attachments.' . $extension;
        $mimetype  = mime_content_type($filePath);
        header('Content-Disposition: attachment; filename="' . $title);
        header("Content-Type: " . $mimetype);
        return readfile($filePath);
    }

    public function userData()
    {
        $user = auth()->user();
        if ($user->profile_complete == Status::YES) {
            return to_route('user.home');
        }
        $pageTitle = 'Complete Your Profile';
        return view($this->activeTemplate . 'user.user_data', compact('pageTitle', 'user'));
    }

    public function userDataSubmit(Request $request)
    {
        $user = auth()->user();
        if ($user->profile_complete == Status::YES) {
            return to_route('user.home');
        }
        $request->validate([
            'firstname' => 'required',
            'lastname'  => 'required',
        ]);
        $user->firstname = $request->firstname;
        $user->lastname  = $request->lastname;
        $user->address   = [
            'country' => @$user->address->country,
            'address' => $request->address,
            'state'   => $request->state,
            'zip'     => $request->zip,
            'city'    => $request->city,
        ];
        $user->profile_complete = Status::YES;
        $user->save();

        $notify[] = ['success', 'Registration process completed successfully'];
        return to_route('user.home')->withNotify($notify);
    }

    public function referral()
    {
        $general = gs();

        if (!$general->referral_system) {
            $notify[] = ['error', 'Sorry, the referral system is currently unavailable'];
            return back()->withNotify($notify);
        }

        $pageTitle = "Referrals";
        $maxLevel  = Referral::max('level');
        $relations = [];
        for ($label = 1; $label <= $maxLevel; $label++) {
            $relations[$label] = (@$relations[$label - 1] ? $relations[$label - 1] . '.allReferrals' : 'allReferrals');
        }
        $user = auth()->user()->load($relations);
        return view($this->activeTemplate . 'user.referral.index', compact('pageTitle', 'user', 'maxLevel'));
    }

    public function referralLog()
    {

        if (!gs()->referral_system) {
            $notify[] = ['error', 'Sorry, the referral system is currently unavailable'];
            return back()->withNotify($notify);
        }

        $pageTitle = "Referral Bonus Logs";
        $logs      = ReferralLog::where('referee_id', auth()->id())->with('referee')->orderBy('id', 'desc')->paginate(getPaginate());

        return view($this->activeTemplate . 'user.referral.logs', compact('pageTitle', 'logs'));
    }

    public function wallets()
    {
        $pageTitle        = "User Wallets";
        $userCoinBalances = UserCoinBalance::where('user_id', auth()->id());

        if (request()->coin_code) {
            $userCoinBalances = $userCoinBalances->whereHas('miner', function ($miner) {
                $miner->where('coin_code', request()->coin_code);
            });
        }

        $userCoinBalances = $userCoinBalances->with('miner')->get();
        return view($this->activeTemplate . 'user.wallets', compact('pageTitle', 'userCoinBalances'));
    }

    public function walletUpdate(Request $request, $id)
    {
        $userCoinBalance = UserCoinBalance::where('user_id', auth()->id())->findOrFail($id);

        $request->validate([
            "wallet" => 'required|string',
        ]);

        $userCoinBalance->wallet = $request->wallet;
        $userCoinBalance->save();

        $notify[] = ['success', 'Wallet Address Updated Successfully'];
        return back()->withNotify($notify);
    }
}