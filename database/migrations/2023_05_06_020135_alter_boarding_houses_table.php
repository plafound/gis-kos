<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterBoardingHousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('boarding_houses', function (Blueprint $table) {
            // Drop column capacity_left
            $table->dropColumn('capacity_left');

            // Add column capacity
            $table->integer('capacity')->after('type')->default(0);
            $table->integer('filled_capacity')->after('capacity')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('boarding_houses', function (Blueprint $table) {
            // Drop column capacity
            $table->dropColumn('capacity');
            $table->dropColumn('filled_capacity');

            // Add column capacity_left
            $table->integer('capacity_left')->after('type');
        });
    }
}
