<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassificationHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('classification_history', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->text('classfication_nbc_result');
            $table->text('classfication_modified_result');
            $table->text('total_document');
            $table->text('total_term');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('classification_history');
    }
}
