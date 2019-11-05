<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateClassificationHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('classification_history', function (Blueprint $table) {
            $table->renameColumn('classfication_nbc_result', 'classification_nbc_result');
            $table->renameColumn('classfication_modified_result', 'classification_modified_result');

            $table->integer('prediction_nbc')->after('classfication_modified_result');
            $table->integer('prediction_modified')->after('prediction_nbc');
            $table->integer('real_category')->after('prediction_modified');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
