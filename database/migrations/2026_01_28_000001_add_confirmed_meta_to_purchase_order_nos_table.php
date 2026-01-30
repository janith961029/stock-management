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
        Schema::table('purchase_order_nos', function (Blueprint $table) {
            $table->unsignedBigInteger('confirmed_user')->nullable()->after('confirmed');
            $table->dateTime('confirmed_date')->nullable()->after('confirmed_user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_order_nos', function (Blueprint $table) {
            $table->dropColumn(['confirmed_user', 'confirmed_date']);
        });
    }
};
