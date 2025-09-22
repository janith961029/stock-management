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
        Schema::create('titlenames', function (Blueprint $table) {
            $table->id();
            $table->string('title_no');
            $table->string('title_name');
            $table->string('relevant_store_id');
            $table->dateTime('create_date');
            $table->string('user_id');
            $table->dateTime('modified_date');
            $table->string('modified_user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('titlenames');
    }
};
