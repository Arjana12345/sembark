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
        Schema::create('client', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID (primary key)
            $table->string('client_name'); // VARCHAR equivalent column
            $table->string('client_email')->nullable(); // TEXT equivalent, allows NULL
            $table->integer('user_id'); // DECIMAL equivalent
            
        });
     
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::dropIfExists('client');
    }
};
