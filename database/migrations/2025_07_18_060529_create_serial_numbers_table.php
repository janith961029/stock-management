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
        Schema::create('serial_numbers', function (Blueprint $table) {
            $table->id();
           // $table->json('recieved')->nullable()->change();
            $table->string('name');
            $table->string('serial_number');            
            $table->string('barcode');
            $table->string('recieved');
            $table->string('issued');
            $table->string('issue_place');  
            $table->string('issuing_type');
            $table->string('job_card_number');  
            $table->string('signal_unit');
            $table->string('remarks_issue'); 
            $table->dateTime('assigned_date');
            $table->foreignId('items_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('serial_numbers');
    }
};
