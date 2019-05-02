<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Form;
use App\Formitem;
use App\Application;

class ApiFormTest extends TestCase
{

  use DatabaseTransactions;

    /**
     * Given I provide a test form tag,
     *  and there are forms in the system with the tag,
     *  I should get a json response,
     *  and the response should be an array of available forms for my application with correct json structure
     *
     * @return void
     */
    public function test_get_forms_by_their_tag()
    {

      $response = $this->get('/api/forms/listbytag/testformtag001?api_token=b0d4ee07-a0d2-44c6-bb22-8bf82de8eea8');
      $response->assertJsonStructure([
            '*' => [
                'id',
                'name',
                'description',
                'uid',
                'tag',
                'createdby',
                'createdby_external_id'
            ]
        ]);

    }


    /**
     * Given I provide a form uid for test form with id:1,
     *  I should get a json response,
     *  and the json structure should be correctly formatted
     *
     *
     *
     * @return [type] [description]
     */
    public function test_get_form_by_its_uid()
    {

      //get the uid of the first test form (id:1 from test seed)
      $form = Form::find(1);
      $response = $this->get('/api/forms/get/' . $form->uid . '?api_token=b0d4ee07-a0d2-44c6-bb22-8bf82de8eea8');
      $response->assertJsonStructure([
          'id',
          'name',
          'description',
          'uid',
          'tag',
          'createdby',
          'createdby_external_id',
          'formitems' => [
            '*' => [
              'id',
              'uid',
              'ordering',
              'required',
              'configuration',
              'validation',
              'itemtype' => [
                'id',
                'name',
                'description',
                'tag'
              ]
            ]
          ]
      ]);
    }




    /**
     * Given I submit a form definition to endpoint /api/forms/add
     *  and the submitted data is in the right structure
     *  then a new form should be persisted into the forms table
     *  and form items added to the formitems table
     *  then the response should be json correctly structured.
     *
     *
     *
     *
     *
     */
    public function test_add_new_form() {


      //Set up the application to submit to.
      $application = Application::find(1); // flowtracker live
      $api_token = $application->api_key;


      //get total forms for this application account before new form added
      $total_forms = Form::where('application_id', $application->id)->count();


      //add form.
      //
      $postdata = '{
                  "api_token": "' . $api_token . '",
                  "name": "Test form",
                  "description": "This is a test form for testing.",
                  "tag": "testaddform",
                  "createdby": "anewform@test.com",
                  "createdby_external_id": 15,
                  "formitems": [
                    {
                      "ordering": 1,
                      "name": "Firstfield",
                      "required": false,
                      "configuration": {},
                      "validation": {},
                      "itemtype": {
                      	  "id": 1
                      }
                    },
                    {
                      "ordering": 2,
                      "name": "Secondfield",
                      "required": false,
                      "configuration": {},
                      "validation": {},
                      "itemtype": {
                        	  "id": 2
                        }
                    }
                  ]
                }';
      $response = $this->json('POST', '/api/forms/add', json_decode($postdata,true));
      $response->assertJsonStructure([
          'id',
          'name',
          'description',
          'uid',
          'tag',
          'createdby',
          'createdby_external_id',
          'formitems' => [
            '*' => [
              'id',
              'uid',
              'ordering',
              'required',
              'configuration',
              'validation',
              'itemtype' => [
                'id',
                'name',
                'description',
                'tag'
              ]
            ]
          ]
      ])
      ->assertJson([
              'name'=>'Test form',
              'description'=>'This is a test form for testing.',
              'tag'=>'testaddform',
              'createdby'=>'anewform@test.com',
              'createdby_external_id'=>15,
              'formitems'=>[
                '0'=>[
                  'ordering'=>1,
                  'name'=>'Firstfield',
                  'required'=>false,
                  'itemtype'=>[
                    'id'=>1
                  ]
                ],
                '1'=>[
                  'ordering'=>2,
                  'name'=>'Secondfield',
                  'required'=>false,
                  'itemtype'=>[
                    'id'=>2
                  ]
                ]
              ],
              'application'=>[
                'id'=>1,
                'name'=>'flowtracker live'
              ]
          ]);;

      //get total forms after the new form added (must be +1)
      $total_final_forms = Form::where('application_id', $application->id)->count();
      $this->assertEquals($total_forms + 1, $total_final_forms);


    }





    /**
     * Given I try and post incorrect data structure to /api/forms/add endpoint
     *  then I should expect 422 unprocessable entity response
     *
     *
     *  formitems->itemtypes[1]->id missing in example below.
     *
     */
    public function test_add_new_form_with_invalid_data_structure_post() {
      //Set up the application to submit to.
      $application = Application::find(1); // flowtracker live
      $api_token = $application->api_key;


      //get total forms for this application account before new form added
      $total_forms = Form::where('application_id', $application->id)->count();


      //add form.
      //
      $postdata = '{
                  "api_token": "' . $api_token . '",
                  "name": "Test form",
                  "description": "This is a test form for testing.",
                  "tag": "testaddform",
                  "createdby": "anewform@test.com",
                  "createdby_external_id": 15,
                  "formitems": [
                    {
                      "ordering": 1,
                      "name": "Firstfield",
                      "required": false,
                      "configuration": {},
                      "validation": {},
                      "itemtype": {
                    	   "id": 1
                      }
                    },
                    {
                      "ordering": 2,
                      "name": "Secondfield",
                      "required": false,
                      "configuration": {},
                      "validation": {},
                      "itemtype": {

                      }
                    }
                  ]
                }';
      $response = $this->json('POST', '/api/forms/add', json_decode($postdata,true));
      $response->assertStatus(422);
    }





    /**
     * Given I have edited test form id:1 and posted to endpoint api/forms/update
     *  and I have swapped the ordering of formitem 1 and formitem 2
     *  then I formitem 1 ordering should equal 2 and formitem 2 should equal 1
     *  and the ordering should be persisted to the db
     *  and I should see a response showing the same.
     *
     *
     * @return [type] [description]
     */
    public function test_update_form_definition_change_order_of_formitems() {
      //Set up the application to submit to.
      $application = Application::find(1); // flowtracker live
      $api_token = $application->api_key;

      //Get the original structure and swap the ordering of items 1 and 2 round
      $form = Form::find(1);
      $result = $form->getByUid($form->uid,$application->id);

      $result->formitems[0]['ordering'] = 2;
      $item1_id = $result->formitems[0]['id'];
      $result->formitems[1]['ordering'] = 1;
      $item2_id = $result->formitems[1]['id'];
      $result->api_token = $api_token;

      $result = json_encode($result); // casts the result back into json from eloquent model result.
      $response = $this->json('POST', '/api/forms/update', json_decode($result,true));

      //get the form again to check persisted ordering
      $item1 = Formitem::find($item1_id);
      $this->assertEquals($item1->ordering, 2);
      $item2 = Formitem::find($item2_id);
      $this->assertEquals($item2->ordering, 1);

    }





    /**
     * Given I have edited test form id:1 and posted to endpoint api/forms/update
     *  and I have removed the first formitem from my posted definition
     *  then I should see response showing 1 less formitem
     *  and the formitem I removed should no longer exist in the db
     *
     *
     *
     *
     * @return [type] [description]
     */
    public function test_update_form_definition_remove_formitems() {
      //Set up the application to submit to.
      $application = Application::find(1); // flowtracker live
      $api_token = $application->api_key;
      //Get the original structure and swap the ordering of items 1 and 2 round
      $form = Form::find(1);
      $form_definition = $form->getByUid($form->uid,$application->id);

      $original_number_formitems = $form_definition->formitems->count();

      //Remove the first formite
      $item_to_remove_id = $form_definition->formitems[0]->id;
      unset($form_definition->formitems[0]);


      $form_definition->api_token = $api_token;

      $form_definition = json_encode($form_definition); // casts the result back into json from eloquent model result.
      $response = json_decode($this->json('POST', '/api/forms/update', json_decode($form_definition,true))->getContent());

      $new_number_formitems = count($response->formitems);
      $this->assertEquals($new_number_formitems, $original_number_formitems - 1);

      $formitems = Formitem::where('id',$item_to_remove_id)->get();

      $this->assertEquals($formitems->count(), 0);


    }




    /**
     * Given I have edited test form id:1 and posted to endpoint api/forms/update
     *  and I have added a new formitem in my posted definition
     *  then I should see response showing 1 more formitem
     *  and the formitem I added should be persisted to the db
     *
     *
     *
     *
     *
     *
     */
    public function test_update_form_definition_add_formitem() {
      $application = Application::find(1); // flowtracker live
      $api_token = $application->api_key;
      //Get the original structure and swap the ordering of items 1 and 2 round
      $form = Form::find(1);
      $form_definition = $form->getByUid($form->uid,$application->id);

      $original_number_formitems = $form_definition->formitems->count();

      $newitem = [
          "ordering"=>4,
          "name"=>"New Form Item",
          "required"=>false,
          "configuration"=> [],
          "validation"=>[],
          "itemtype" =>[
            "id"=> 2
          ]
      ];

      $form_definition->formitems[] = $newitem;
      $form_definition->api_token = $api_token;

      $form_definition = json_encode($form_definition); // casts the result back into json from eloquent model result.
      $response = json_decode($this->json('POST', '/api/forms/update', json_decode($form_definition,true))->getContent());

      $new_number_formitems = count($response->formitems);
      $this->assertEquals($new_number_formitems, $original_number_formitems + 1);

      $newitemsaved = array_pop($response->formitems);
      $this->assertEquals($newitemsaved->ordering,4);
      $this->assertEquals($newitemsaved->name,'New Form Item');
      $this->assertEquals($newitemsaved->required,false);
      $this->assertEquals($newitemsaved->itemtype->id,2);

      $this->assertDatabaseHas('formitems', [
          'id' => $newitemsaved->id
      ]);

    }



}
