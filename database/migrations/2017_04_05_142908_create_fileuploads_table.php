<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFileuploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fileuploads', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('submissiondata_id');
            $table->text('filename')->nullable();
            $table->text('filekey')->nullable();
            $table->text('storageurl')->nullable();
            $table->bigInteger('bytes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fileuploads');
    }
}
