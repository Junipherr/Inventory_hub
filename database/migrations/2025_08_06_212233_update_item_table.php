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
    Schema::table('items', function (Blueprint $table) {
        $table->date('purchase_date')->nullable();
        $table->decimal('purchase_price', 10, 2)->nullable();
        $table->date('warranty_expires')->nullable();
        $table->string('condition')->nullable();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
   {
    Schema::table('items', function (Blueprint $table) {
        $table->dropColumn(['purchase_date', 'purchase_price', 'warranty_expires', 'condition']);
    });
}
};
