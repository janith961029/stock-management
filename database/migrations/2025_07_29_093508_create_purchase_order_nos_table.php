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
        Schema::create('purchase_order_nos', function (Blueprint $table) {
            $table->id();
            $table->string('issue_place')->nullable();
            $table->string('sup_id')->nullable();
            $table->string('vote_id')->nullable(); // removed extra space
            $table->string('rcvd_to')->nullable();
            $table->string('amount')->nullable();
            $table->string('p_order_remarks');
            $table->string('user_id')->nullable();
            $table->string('create_date')->nullable();
            $table->string('is_active')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_order_nos');
    }
};
