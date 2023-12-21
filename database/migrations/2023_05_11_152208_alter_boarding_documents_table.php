<?php

use App\Models\BoardingDocuments;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterBoardingDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Truncate boarding documents table
        BoardingDocuments::truncate();

        Schema::table('boarding_documents', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
            
            $table->foreignId('boarding_id')
                ->nullable()
                ->after('id')
                ->constrained('boarding_houses')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Truncate boarding documents table
        BoardingDocuments::truncate();

        Schema::table('boarding_documents', function (Blueprint $table) {
            $table->dropForeign(['boarding_id']);
            $table->dropColumn('boarding_id');

            $table->foreignId('user_id')
                ->nullable()
                ->after('id')
                ->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }
}
