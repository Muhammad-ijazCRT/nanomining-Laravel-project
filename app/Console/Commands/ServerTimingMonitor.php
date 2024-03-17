<?php

namespace App\Console\Commands;

use App\Constants\Status;
use Illuminate\Console\Command;
use App\Models\Order;

class ServerTimingMonitor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'timingMonitor:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {


        $orders = Order::approved()
            ->where('period_remain', '>=', 1)
            ->get();

        // return now()->format('g:i A');
        // return $orders[0]['created_at']->format('g:i A');

        foreach ($orders as $order) {
            if ($order['created_at']->format('g:i A') == now()->format('g:i A')) {
                $order->period_remain -= 1;

                if ($order->period_remain == 0) {
                    $order->status = Status::ORDER_COMPLETED;
                }

                $order->save();
            }
        }
    }
}
