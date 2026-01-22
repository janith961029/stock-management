<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('serial_numbers', function (Blueprint $table) {
            $table->unique('serial_number', 'serial_numbers_serial_number_unique');
            $table->unique('barcode', 'serial_numbers_barcode_unique');
        });
    }

    public function down(): void
    {
        Schema::table('serial_numbers', function (Blueprint $table) {
            $table->dropUnique('serial_numbers_serial_number_unique');
            $table->dropUnique('serial_numbers_barcode_unique');
        });
    }
};
