<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class AutoUpdateShipping extends Command
{
    protected $signature = 'orders:auto-shipping {--demo : Simulasikan pengiriman otomatis (resi palsu)}';
    protected $description = 'Update status pengiriman otomatis';

    public function handle(): int
    {
        // Dikirim lebih dari 7 hari -> otomatis selesai
        $done = Order::where('status', 'paid')
            ->where('shipping_status', 'shipped')
            ->where('shipped_at', '<=', now()->subDays(7))
            ->update(['shipping_status' => 'completed', 'completed_at' => now()]);

        $this->info("Otomatis selesai: {$done} pesanan.");

        // Mode demo: Diproses lebih dari 5 menit -> Dikirim (resi simulasi)
        if ($this->option('demo')) {
            $orders = Order::where('status', 'paid')
                ->where('shipping_status', 'processing')
                ->where('updated_at', '<=', now()->subMinutes(2))
                ->get();

            foreach ($orders as $order) {
                $order->update([
                    'shipping_status' => 'shipped',
                    'courier' => 'SIMULASI',
                    'tracking_number' => 'SIM' . strtoupper(Str::random(10)),
                    'shipped_at' => now(),
                ]);
            }

            $this->info("Simulasi dikirim: {$orders->count()} pesanan.");
        }

        return self::SUCCESS;
    }
}