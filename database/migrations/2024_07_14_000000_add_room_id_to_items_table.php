<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRoomIdToItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            // Add room_id column as foreign key
            $table->unsignedBigInteger('room_id')->nullable()->after('item_name');

            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('set null');

            // Drop the department column
            $table->dropColumn('department');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            // Add department column back
            $table->string('department')->after('item_name');

            // Drop foreign key and room_id column
            $table->dropForeign(['room_id']);
            $table->dropColumn('room_id');
        });
    }
}
