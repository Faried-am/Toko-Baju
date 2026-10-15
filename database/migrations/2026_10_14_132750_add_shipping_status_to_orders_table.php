<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::table('orders', function (Blueprint $table) {
        $table->string('shipping_status', 20)->default('pending')->after('status');
        $table->string('courier', 50)->nullable()->after('shipping_status');
        $table->string('tracking_number', 100)->nullable()->after('courier');
        $table->timestamp('shipped_at')->nullable()->after('tracking_number');
        $table->timestamp('completed_at')->nullable()->after('shipped_at');
    });
}

public function down(): void
{
    Schema::table('orders', function (Blueprint $table) {
        $table->dropColumn(['shipping_status', 'courier', 'tracking_number', 'shipped_at', 'completed_at']);
    });
}};
