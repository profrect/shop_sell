<?php

namespace App\Console\Commands;

use App\Models\V1\OrderModel;
use App\Models\V1\OrdersPay;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Command\Command as CommandAlias;

class CancelExpiredOrders extends Command
{
    protected $signature   = 'orders:cancel-expired';
    protected $description = '取消超时未支付订单';

    /**
     * 执行命令
     * @return int
     * @throws \Throwable
     */
    public function handle(): int
    {
        DB::transaction(function () {
            $orders = OrderModel::where('status', OrderModel::STATUS_PAYING)
                ->where('expired_time', '<=', now())
                ->lockForUpdate()
                ->get();

            foreach ($orders as $order) {
                $order->update([
                    'status'       => OrderModel::STATUS_CANCEL,
                    'updated_time' => now(),
                ]);
                // 如果有未支付的订单项，则取消订单
                OrdersPay::where('order_id', $order->id)->update(['status' => OrdersPay::PAY_STATUS_EXPIRE,'update_time' => now()]);

                $this->info("订单 {$order->id} 已取消");
            }
        });

        return CommandAlias::SUCCESS;
    }
}


