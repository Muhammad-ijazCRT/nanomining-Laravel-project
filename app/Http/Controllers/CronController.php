<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use App\Models\UserCoinBalance;
use App\Lib\CurlRequest;
use App\Models\CronJob;
use App\Models\CronJobLog;
use App\Models\Order;
use App\Models\Transaction;
use Carbon\Carbon;

class CronController extends Controller
{
    public function cron()
    {
        // $general            = gs();
        // $general->last_cron = now();
        // $general->save();
        // $crons = CronJob::with('schedule');

        $orders = Order::approved()
            ->with('user', 'miner')
            ->whereHas('user')
            ->where('period_remain', '>=', 1)
            // ->where('last_paid', '<=', Carbon::now()->subHours(24)->toDateTimeString())
            ->get();

        foreach ($orders as $order) {
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
           // $ucb->balance += $addAmountInOneMinute;
            $ucb->balance += '50';
            $ucb->save();
            
            if ($order->user) {
                Session::forget('mining_servers', $order->user->id);
            }
            
        }



        // if (request()->alias) {
        //     $crons->where('alias', request()->alias);
        // } else {
        //     $crons->where('next_run', '<', now())->where('is_running', 1);
        // }
        // $crons = $crons->get();
        // foreach ($crons as $cron) {
        //     $cronLog              = new CronJobLog();
        //     $cronLog->cron_job_id = $cron->id;
        //     $cronLog->start_at    = now();
        //     if ($cron->is_default) {
        //         $controller = new $cron->action[0];
        //         try {
        //             $method = $cron->action[1];
        //             $controller->$method();
        //         } catch (\Exception $e) {
        //             $cronLog->error = $e->getMessage();
        //         }
        //     } else {
        //         try {
        //             CurlRequest::curlContent($cron->url);
        //         } catch (\Exception $e) {
        //             $cronLog->error = $e->getMessage();
        //         }
        //     }
        //     $cron->last_run = now();
        //     $cron->next_run = now()->addSeconds($cron->schedule->interval);
        //     $cron->save();

        //     $cronLog->end_at = $cron->last_run;

        //     $startTime         = Carbon::parse($cronLog->start_at);
        //     $endTime           = Carbon::parse($cronLog->end_at);
        //     $diffInSeconds     = $startTime->diffInSeconds($endTime);
        //     $cronLog->duration = $diffInSeconds;
        //     $cronLog->save();
        // }
        if (request()->alias) {
            $notify[] = ['success', keyToTitle(request()->alias) . ' executed successfully'];
            return back()->withNotify($notify);
        }
    }
    public function returnAmount()
    {
        $general            = gs();
        $general->last_cron = Carbon::now()->toDateTimeString();
        $general->save();

        $orders = Order::approved()
            ->with('user', 'miner')
            ->whereHas('user')
            ->where('period_remain', '>=', 1)
            ->where('last_paid', '<=', Carbon::now()->subHours(24)->toDateTimeString())
            ->get();

        foreach ($orders as $order) {
            $returnAmount = rand($order->min_return_per_day * 100000000, $order->max_return_per_day * 100000000) / 100000000;

            $ucb          = UserCoinBalance::where('user_id', $order->user_id)->where('miner_id', $order->miner_id)->first();
            if (!$ucb) {
                continue;
            }

            $ucb->balance += $returnAmount;
            $ucb->save();

            $order->period_remain -= 1;
            $order->last_paid      = Carbon::now();
            $order->save();

            $trx = getTrx();

            $transaction               = new Transaction();
            $transaction->user_id      = $order->user_id;
            $transaction->amount       = $returnAmount;
            $transaction->post_balance = getAmount($ucb->balance);
            $transaction->charge       = 0;
            $transaction->trx_type     = '+';
            $transaction->details      = 'Daily return amount for the plan ' . $order->plan_details->title;
            $transaction->trx          = $trx;
            $transaction->currency     = $order->miner->coin_code;
            $transaction->remark       = 'return_amount';
            $transaction->save();

            $maintenanceCost = $returnAmount * $order->maintenance_cost / 100;
            $ucb->balance -= $maintenanceCost;

            $ucb->save();

            $transaction               = new Transaction();
            $transaction->user_id      = $order->user_id;
            $transaction->amount       = $maintenanceCost;
            $transaction->post_balance = getAmount($ucb->balance);
            $transaction->charge       = 0;
            $transaction->trx_type     = '-';
            $transaction->details      = 'Deducted as maintenance charge';
            $transaction->trx          = $trx;
            $transaction->currency     = $order->miner->coin_code;
            $transaction->remark       = 'maintenance_cost';
            $transaction->save();
        }

        return 'Cron executed successfully';
    }
}
