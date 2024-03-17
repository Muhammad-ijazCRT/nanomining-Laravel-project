<?php

namespace App\Console\Commands;

use App\Models\ActiveMinedCoin;
use Illuminate\Support\Facades\Session;
use Illuminate\Console\Command;
use App\Models\UserCoinBalance;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DemoCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demo:cron';

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
        for ($i = 0; $i < 60; $i++) {
            $orders = Order::approved()
                ->where('period_remain', '>=', 1)
                // ->where('last_paid', '<=', Carbon::now()->subHours(24)->toDateTimeString())
                ->get();



            // \Log::info("Cron is working fine!");


            if ($orders) {
                foreach ($orders as $order) {
                    $addAmount = $order->min_return_per_day;
                    $addAmountInSeconds = number_format($addAmount / (60 * 60 * 24), 10);

                    // Retrieve the UserCoinBalance record or create a new one if it doesn't exist
                    UserCoinBalance::updateOrInsert(
                        [
                            'user_id' => $order->user_id,
                            'miner_id' => $order->miner_id,
                        ],
                        [
                            'balance' => DB::raw("balance + $addAmountInSeconds"),
                        ]
                    );

                    // data
                    ActiveMinedCoin::updateOrInsert(
                        [
                            'order_id' => $order->id
                        ],
                        [
                            'mined_coins' => DB::raw("mined_coins + $addAmountInSeconds"),
                        ]
                    );
                }
            }
            sleep(1);
        }
    }
}
