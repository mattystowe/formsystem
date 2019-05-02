<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubmissiondatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('submissiondatas', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('formsubmission_id');
            $table->integer('formitem_id');
            $table->longtext('datavalue')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('submissiondatas');
    }
}
