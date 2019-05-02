<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveCheckboxgroupFromItemtypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      DB::table('itemtypes')->where('tag', 'checkboxgroup')->delete();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      DB::table('itemtypes')->insert([
        [   'tag'=>'checkboxgroup',
            'name'=>'Checkbox group',
            'description'=>'A group of checkboxes (tick boxes) for multiselect.'
        ]
      ]);
    }
}
