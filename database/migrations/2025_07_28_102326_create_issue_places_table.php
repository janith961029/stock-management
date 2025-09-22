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
        Schema::create('issue_places', function (Blueprint $table) {
        $table->id();
        $table->string('issue_place')->nullable();
        $table->string('place_discription')->nullable();
        $table->dateTime('create_date')->nullable(); // removed extra space
        $table->string('create_user')->nullable();
        $table->string('issuing_type')->nullable();
        $table->dateTime('edit_date')->nullable();
        $table->string('edit_user')->nullable();
        $table->string('is_q5_unit')->nullable();
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('issue_places');
    }
};
