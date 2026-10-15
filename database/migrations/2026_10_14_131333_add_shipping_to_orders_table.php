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
        $table->string('recipient_name')->nullable()->after('status');
        $table->string('phone', 20)->nullable()->after('recipient_name');
        $table->text('address')->nullable()->after('phone');
        $table->string('city', 100)->nullable()->after('address');
        $table->string('postal_code', 10)->nullable()->after('city');
        $table->string('note')->nullable()->after('postal_code');
    });
}

public function down(): void
{
    Schema::table('orders', function (Blueprint $table) {
        $table->dropColumn(['recipient_name', 'phone', 'address', 'city', 'postal_code', 'note']);
    });
}
};
