<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('extraction_logs', function (Blueprint $table) {
            $table->id();

            // One row per uploaded PDF file
            $table->string('file_name');
            $table->boolean('success')->default(false);
            $table->text('error')->nullable();

            // Parsed order data (nullable when success = false)
            $table->string('order_number')->nullable();
            $table->text('ship_to')->nullable();       // full address string
            $table->unsignedSmallInteger('item_count')->default(0);

            // Full parsed result + items stored as JSON for flexibility
            $table->json('items')->nullable();          // array of item objects
            $table->json('raw_result')->nullable();     // full parser output

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('extraction_logs');
    }
};
