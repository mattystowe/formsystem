<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Itemtype;
use App\Application;

class ApiFormItemTypesTest extends TestCase
{

  use DatabaseTransactions;
    /**
     * Given I hit the /formitemtypes/list endpoint
     *  and I have provided a valid api_token
     *  I should see a json response
     *  and json response should be in correct format of an array of Itemtype objects
     *
     *
     * @return void
     */
    public function test_get_form_item_types()
    {
      $response = $this->get('/api/formitemtypes/list?api_token=b0d4ee07-a0d2-44c6-bb22-8bf82de8eea8');
      $response->assertJsonStructure([
            '*' => [
                'id',
                'name',
                'description',
                'tag'
            ]
        ]);
    }





    /**
     * Given I hit the /formitemtypes/list endpoint
     *  and I have provided a valid api_token for "flowtracker live" application
     *  then the number of itemtypes returned should be a total of application specific and non-application specific item types.
     *
     *
     * @return [type] [description]
     */
    public function test_get_form_item_types_with_application_specific_types() {

        $api_token = 'b0d4ee07-a0d2-44c6-bb22-8bf82de8eea8';
        $application = Application::where('api_key',$api_token)->get();


        //Add a specific item to the live application for tests
        $itemtype = new Itemtype;
        $itemtype->name = 'Test Item Type';
        $itemtype->description = 'This is a test item type';
        $itemtype->tag = 'testitem';
        $itemtype->application_id = $application[0]->id;
        $itemtype->save();

        //get number of specific item types for application 1
        $specific_items = Itemtype::where('application_id',$application[0]->id);
        $generic_items = Itemtype::where('application_id',NULL);

        //echo 'Specific Item Types: ' . $specific_items->count();
        //echo ' Generic Item Types: ' . $generic_items->count();

        $response = json_decode($this->call('GET','/api/formitemtypes/list?api_token=' . $api_token)->getContent());
        $total_items = count($response);

        //echo ' total = ' . count($response);

        $this->assertEquals($specific_items->count() + $generic_items->count(), $total_items);



    }


}
