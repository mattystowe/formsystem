<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Ramsey\Uuid\Uuid;

class CreateApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('uid')->unique()->index();
            $table->string('name');
            $table->string('api_key')->unique()->index();
        });

        // Live
        $uuid4 = Uuid::uuid4();
        DB::table('applications')->insert(
          array(
              'name' => 'live',
              'uid' => $uuid4->toString(),
              'api_key' => 'b0d4ee07-a0d2-44c6-bb22-8bf82de8eea3'
          )
        );

        // CI
        $uuid4 = Uuid::uuid4();
        DB::table('applications')->insert(
          array(
              'name' => 'ci',
              'uid' => $uuid4->toString(),
              'api_key' => '1282279e-4891-4f33-a130-b4257aa19757'
          )
        );

        // dev
        $uuid4 = Uuid::uuid4();
        DB::table('applications')->insert(
          array(
              'name' => 'dev',
              'uid' => $uuid4->toString(),
              'api_key' => 'bc50981a-4ea5-4bdd-9bbe-a50db3ffab84'
          )
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('applications');
    }
}
