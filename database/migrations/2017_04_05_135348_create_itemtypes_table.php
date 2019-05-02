<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemtypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('itemtypes', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('application_id')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('tag');
        });

        //Initial Item Types populate db
        //
        DB::table('itemtypes')->insert([
          [   'tag'=>'text',
              'name'=>'Text',
              'description'=>'Text input single line.'
          ],
          [   'tag'=>'textarea',
              'name'=>'Text area',
              'description'=>'Text input multiple lines.'
          ],
          [   'tag'=>'richtext',
              'name'=>'Rich Text Editor input',
              'description'=>'A rich text editor input area.'
          ],
          [   'tag'=>'number',
              'name'=>'Number',
              'description'=>'Numeric input single line.'
          ],
          [   'tag'=>'checkbox',
              'name'=>'Checkbox',
              'description'=>'Checkbox (tick box).'
          ],
          [   'tag'=>'checkboxgroup',
              'name'=>'Checkbox group',
              'description'=>'A group of checkboxes (tick boxes) for multiselect.'
          ],
          [   'tag'=>'select',
              'name'=>'Select dropdown',
              'description'=>'Dropdown selection box.'
          ],
          [   'tag'=>'radiogroup',
              'name'=>'Radio box group',
              'description'=>'A group of radio select boxes.'
          ],
          [   'tag'=>'email',
              'name'=>'Email',
              'description'=>'Email address.'
          ],
          [   'tag'=>'date',
              'name'=>'Date',
              'description'=>'A date select calendar'
          ],
          [   'tag'=>'time',
              'name'=>'Time',
              'description'=>'A time of day input.'
          ],
          [   'tag'=>'uploadfile',
              'name'=>'File upload',
              'description'=>'A file upload tool.'
          ],
          [   'tag'=>'uploadimage',
              'name'=>'Image upload',
              'description'=>'An image upload tool.'
          ],
          [   'tag'=>'signature',
              'name'=>'Signature',
              'description'=>'A signature signing area.'
          ],
          [   'tag'=>'layouttitle',
              'name'=>'Layout Section Title',
              'description'=>'A title for a section.'
          ],
          [   'tag'=>'layoutdivider',
              'name'=>'Layout Section Divider',
              'description'=>'A divider for sections.'
          ],
          [   'tag'=>'layoutrichtext',
              'name'=>'Layout text',
              'description'=>'Layout rich text for a section.'
          ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('itemtypes');
    }
}
