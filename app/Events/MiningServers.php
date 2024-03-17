<?php

namespace App\Events;

use App\Models\ActiveMinedCoin;
use App\Models\Order;
use App\Models\UserCoinBalance;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;


class MiningServers implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

    public $message;
    public $data;
    public function __construct()
    {

        for ($i = 0; $i < 60; $i++) {
            $orders = Order::approved()
                ->where('period_remain', '>=', 1)
                // ->where('last_paid', '<=', Carbon::now()->subHours(24)->toDateTimeString())
                ->get();

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
            sleep(1);
        }
    }


    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('mining');
        // return new PrivateChannel('channel-name');
    }
}
