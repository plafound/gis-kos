<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterBoardingHousesTableForDistricts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('boarding_houses', function (Blueprint $table) {
            $table->foreignId('district_id')
                ->nullable()
                ->after('address')
                ->constrained('districts')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('postal_code')
                ->nullable()
                ->after('district_id')
                ->default('00000');
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
            // drop foreign key
            $table->dropForeign(['district_id']);

            // drop column
            $table->dropColumn('district_id');
            $table->dropColumn('postal_code');
        });
    }
}
