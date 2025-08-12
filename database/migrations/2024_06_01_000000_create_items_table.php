<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('item_name');
            $table->string('category_id');
            $table->unsignedBigInteger('room_id')->nullable();
            $table->integer('quantity')->default(1);
            $table->text('description')->nullable();
            $table->string('qr_code')->nullable();
            $table->timestamp('last_checked_at')->nullable();
            $table->timestamps();
            
            // Foreign key constraint - will only work after rooms table exists
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
}
