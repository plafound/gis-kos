<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToBoardingDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('boarding_documents', function (Blueprint $table) {
            $table->integer('status')->default(1)->after('document_path')->comment('1: pending, 2: approved, 3: rejected');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('boarding_documents', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
