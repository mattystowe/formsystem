<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormitemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formitems', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('form_id');
            $table->string('uid')->unique()->index();
            $table->integer('itemtype_id');
            $table->integer('ordering');
            $table->text('name');
            $table->boolean('required');
            $table->longtext('configuration');
            $table->longtext('validation');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('formitems');
    }
}
