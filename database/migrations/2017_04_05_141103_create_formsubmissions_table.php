<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormsubmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formsubmissions', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('uid')->unique()->index();
            $table->integer('form_id');
            $table->string('tag')->nullable();
            $table->string('submittedby')->nullable();
            $table->integer('submittedby_external_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('formsubmissions');
    }
}
