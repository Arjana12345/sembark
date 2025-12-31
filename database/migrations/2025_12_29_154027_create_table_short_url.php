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
        Schema::create('short_url', function (Blueprint $table) {
            $table->id();
            $table->string('short_url'); // VARCHAR column
            $table->text('long_url')->nullable(); // TEXT column that can be null
            $table->integer('user_id')->nullable(); // DECIMAL with precision 8, scale 2
            $table->timestamps(); // Adds
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('short_url', function (Blueprint $table) {
            Schema::dropIfExists('short_url');
        });
    }
};
