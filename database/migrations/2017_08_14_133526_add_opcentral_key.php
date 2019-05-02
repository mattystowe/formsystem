<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Ramsey\Uuid\Uuid;

class AddOpcentralKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      //Op Live
      $uuid4 = Uuid::uuid4();
      DB::table('applications')->insert(
        array(
            'name' => 'newsystem',
            'uid' => $uuid4->toString(),
            'api_key' => '36ff7066-57dd-4750-87f7-92c160d57453'
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
        DB::table('applications')->where('name', '=', 'opcentral')->delete();
    }
}
