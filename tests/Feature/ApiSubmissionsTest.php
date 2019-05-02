<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Form;
use App\Formsubmission;


class ApiSubmissionsTest extends TestCase
{


    use DatabaseTransactions;

    /**
     * Given I hit the /submissions/listbyform/{formuid} endpoint with specific form uid
     *  and the form uid is for the test form,
     *  and the test form has 2 submissions (from seeder)
     *  then I see a json response with an array of Formsubmission objects
     *
     * @return void
     */
    public function test_list_by_form()
    {


      $api_token = 'b0d4ee07-a0d2-44c6-bb22-8bf82de8eea8';
      $form = Form::find(1); // test form

      $response = $this->get('/api/submissions/listbyform/' . $form->uid . '?api_token=' . $api_token);
      $response->assertJsonStructure([
            '*' => [
                'id',
                'created_at',
                'uid',
                'form_id',
                'tag',
                'submittedby',
                'submittedby_external_id'
            ]
        ])
        ->assertJson([
                '0' => [
                  'id' => 1,
                  'form_id' =>1,
                  'tag'=> 'testsubmissiontag001',
                  'submittedby'=> 'submittedby@test.com'
                ],
                '1' => [
                  'id' => 2,
                  'form_id' =>1,
                  'tag'=> 'testsubmissiontag001',
                  'submittedby'=> 'submittedby@test.com'
                ]
            ]);



    }


    /**
     * Given I hit the /submissions/listbytag/{tag} endpoint with test tag testsubmissiontag001
     *  then I see a json response with an array of Formsubmission objects
     *  and the response has at least 2 submissions (from seeder) with specific structure
     *
     *
     * @return void
     */
    public function test_list_by_tag()
    {


      $api_token = 'b0d4ee07-a0d2-44c6-bb22-8bf82de8eea8';
      $tag = 'testsubmissiontag001';

      $response = $this->get('/api/submissions/listbytag/' . $tag . '?api_token=' . $api_token);
      $response->assertJsonStructure([
            '*' => [
                'id',
                'created_at',
                'uid',
                'form_id',
                'tag',
                'submittedby',
                'submittedby_external_id',
                'Form'=>[
                  'id',
                  'uid',
                  'name',
                  'description',
                  'tag',
                  'createdby',
                  'createdby_external_id'
                ]
            ]
        ])
        ->assertJson([
                '0' => [
                  'id' => 1,
                  'form_id' =>1,
                  'tag'=> 'testsubmissiontag001',
                  'submittedby'=> 'submittedby@test.com'
                ],
                '1' => [
                  'id' => 2,
                  'form_id' =>1,
                  'tag'=> 'testsubmissiontag001',
                  'submittedby'=> 'submittedby@test.com'
                ]
            ]);

    }





    /**
     * Given I hit the /submissions/listbytagandform/{tag}/{formuid} endpoint with specific form uid and test tag testsubmissiontag001
     *  and the test form has at least 2 submissions (from seeder)
     *  then I see a json response with an array of Formsubmission objects
     *
     * @return void
     */
    public function test_list_by_tag_and_form()
    {


      $api_token = 'b0d4ee07-a0d2-44c6-bb22-8bf82de8eea8';
      $tag = 'testsubmissiontag001';
      $form = Form::find(1); // test form

      $response = $this->get('/api/submissions/listbytagandform/' . $tag . '/' . $form->uid . '?api_token=' . $api_token);
      $response->assertJsonStructure([
            '*' => [
                'id',
                'created_at',
                'uid',
                'form_id',
                'tag',
                'submittedby',
                'submittedby_external_id'
            ]
        ])
        ->assertJson([
                '0' => [
                  'id' => 1,
                  'form_id' =>1,
                  'tag'=> 'testsubmissiontag001',
                  'submittedby'=> 'submittedby@test.com'
                ],
                '1' => [
                  'id' => 2,
                  'form_id' =>1,
                  'tag'=> 'testsubmissiontag001',
                  'submittedby'=> 'submittedby@test.com'
                ]
            ]);



    }







    /**
     * Given I hit the /submissions/get/{submissionuid} endpoint with uid of the first test submission
     *  then I see a json response
     *    and the response contains speficic structure
     *
     *
     *
     * @return [type] [description]
     */
    public function test_get() {
        $api_token = 'b0d4ee07-a0d2-44c6-bb22-8bf82de8eea8';

        $submission = Formsubmission::find(1);

        $response = $this->get('/api/submissions/get/' . $submission->uid . '?api_token=' . $api_token);
        $response->assertJsonStructure([
            'name',
            'description',
            'uid',
            'tag',
            'createdby',
            'createdby_external_id',
            'Formsubmission'=>[
              'id',
              'created_at',
              'updated_at',
              'uid',
              'form_id',
              'tag',
              'submittedby',
              'submittedby_external_id'
            ],
            'formitems'=> [
              '*'=>[
                'id',
                'uid',
                'ordering',
                'name',
                'required',
                'configuration',
                'validation',
                'Data'=> [
                  'datavalue',
                  'fileuploads'=>[]
                ]
              ]
            ],
            'application'=>[
              'id',
              'uid',
              'name'
            ]
        ])
        ->assertJson([
            'id' => 1,
            'name' =>'Test form',
            'tag' => 'testformtag001',
            'description' => 'This is a test form for testing.',
            'Formsubmission'=>[
              'id'=>1,
              'form_id'=>1,
              'tag'=>'testsubmissiontag001',
              'submittedby'=>'submittedby@test.com'
            ],
            'formitems'=>[
              '0'=>[
                'id'=>1,
                'ordering'=>1,
                'name'=>'Firstname',
                'required'=>true,
                'Data'=>[
                  'id'=>1,
                  'datavalue'=>'TestFirstname'
                ],
                'itemtype'=>[
                  'name'=>'Text',
                  'tag'=>'text'
                ]
              ],
              '1'=>[
                'id'=>2,
                'ordering'=>2,
                'name'=>'Lastname',
                'required'=>true,
                'Data'=>[
                  'id'=>2,
                  'datavalue'=>'TestLastname'
                ],
                'itemtype'=>[
                  'name'=>'Text',
                  'tag'=>'text'
                ]
              ],
              '2'=>[
                'id'=>3,
                'ordering'=>3,
                'name'=>'Description',
                'required'=>true,
                'Data'=>[
                  'id'=>3,
                  'datavalue'=>'This is a test description'
                ],
                'itemtype'=>[
                  'name'=>'Text area',
                  'tag'=>'textarea'
                ]
              ]
            ],
            'application' => [
              'id'=>1,
              'name'=>'flowtracker live'
            ]
            ]);


    }





    /**
     * Given I am submitting a form with data to /api/submissions/add endpoint
     *  and the form is for test form id:1 (from seed)
     *  and field [Firstname] is set to TestSubmissionFirstname
     *  and field [Lastname] is set to TestSubmissionLastname
     *  and field [Description] is set to TestSubmissionDescription
     *  then I should see a json response
     *  and I should expect a new submissions uid as part of the response
     *  and I should see formitems with correct Data as part of the response
     *  and the new formitem data should be persisted to db.
     *
     *
     *
     */
    public function test_add_new_submission() {
      $api_token = 'b0d4ee07-a0d2-44c6-bb22-8bf82de8eea8';

      $data = '{
                        "api_token": "' . $api_token . '",
                        "tag": "TestSubmissionTag1234",
                        "submittedby": "test@somebody.com",
                        "submittedby_external_id": 25,
                        "id": 1,
                        "formitems": [
                          {
                            "id": 1,
                            "Data": {
                              "datavalue": "TestSubmissionFirstname",
                              "fileuploads": []
                            }
                          },
                          {
                            "id": 2,
                            "Data": {
                              "datavalue": "TestSubmissionLastname",
                              "fileuploads": []
                            }
                          },
                          {
                            "id": 3,
                            "Data": {
                              "datavalue": "TestSubmissionDescription",
                              "fileuploads": []
                            }
                          }
                        ]
                      }';
        $data = json_decode($data,true);
        $response = $this->json('POST', '/api/submissions/add', $data);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'Formsubmission'=>[
              'id',
              'created_at',
              'updated_at',
              'uid',
              'form_id',
              'tag',
              'submittedby',
              'submittedby_external_id'
            ],
            'formitems'=> [
              '*'=>[
                'Data'=> [
                  'id',
                  'datavalue',
                  'fileuploads'=>[]
                ]
              ]
            ]
        ]);

        $response->assertJson([
            'Formsubmission'=>[
              'tag'=>'TestSubmissionTag1234',
              'submittedby'=>'test@somebody.com',
              'submittedby_external_id'=> 25
            ],
            'formitems'=>[
              '0'=>[
                'Data'=>[
                  'datavalue'=>'TestSubmissionFirstname'
                ]
              ],
              '1'=>[
                'Data'=>[
                  'datavalue'=>'TestSubmissionLastname'
                ]
              ],
              '2'=>[
                'Data'=>[
                  'datavalue'=>'TestSubmissionDescription'
                ]
              ]
            ]
          ]);

          //check data persisted
          $responsecontent = json_decode($response->getContent());

          $itemdata1 = $responsecontent->formitems[0]->Data;
          $this->assertDatabaseHas('submissiondatas', [
              'id' => $itemdata1->id,
              'datavalue'=>'TestSubmissionFirstname'
          ]);
          $itemdata2 = $responsecontent->formitems[1]->Data;
          $this->assertDatabaseHas('submissiondatas', [
              'id' => $itemdata2->id,
              'datavalue'=>'TestSubmissionLastname'
          ]);
          $itemdata3 = $responsecontent->formitems[2]->Data;
          $this->assertDatabaseHas('submissiondatas', [
              'id' => $itemdata3->id,
              'datavalue'=>'TestSubmissionDescription'
          ]);

    }


    /**
     * Given I am submitting a form with data to /api/submissions/add endpoint
     *  and the form is for test form id:1 (from seed)
     *  and field [Firstname] is set to TestSubmissionFirstname
     *  and field [Lastname] is set to TestSubmissionLastname
     *  and field [Description] is set to TestSubmissionDescription
     *  and field [Description] has 2 file uploads attached
     *  then I should see a json response
     *  and I should expect a new submissions uid as part of the response
     *  and I should see formitems with correct Data as part of the response
     *  and the new formitem data should be persisted to db.
     *  and the new files should be persisted to db.
     *
     *
     *
     *
     */
    public function test_add_new_submission_with_files() {
      $api_token = 'b0d4ee07-a0d2-44c6-bb22-8bf82de8eea8';

      $data = '{
                        "api_token": "' . $api_token . '",
                        "tag": "TestSubmissionTag1234",
                        "submittedby": "test@somebody.com",
                        "submittedby_external_id": 25,
                        "id": 1,
                        "formitems": [
                          {
                            "id": 1,
                            "Data": {
                              "datavalue": "TestSubmissionFirstname",
                              "fileuploads": []
                            }
                          },
                          {
                            "id": 2,
                            "Data": {
                              "datavalue": "TestSubmissionLastname",
                              "fileuploads": []
                            }
                          },
                          {
                            "id": 3,
                            "Data": {
                              "datavalue": "TestSubmissionDescription",
                              "fileuploads": [
                                {
                              		"filename": "somefiletest1.jpg",
                              		"filekey": "somefilekey1",
                              		"storageurl": "https://storageurl.com/1",
                              		"bytes": 123456
                              	},
                                {
                              		"filename": "somefiletest2.jpg",
                              		"filekey": "somefilekey2",
                              		"storageurl": "https://storageurl.com/2",
                              		"bytes": 1234567
                              	}
                              ]
                            }
                          }
                        ]
                      }';
        $data = json_decode($data,true);
        $response = $this->json('POST', '/api/submissions/add', $data);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'Formsubmission'=>[
              'id',
              'created_at',
              'updated_at',
              'uid',
              'form_id',
              'tag',
              'submittedby',
              'submittedby_external_id'
            ],
            'formitems'=> [
              '*'=>[
                'Data'=> [
                  'id',
                  'datavalue',
                  'fileuploads'=>[]
                ]
              ]
            ]
        ]);

        $response->assertJson([
            'Formsubmission'=>[
              'tag'=>'TestSubmissionTag1234',
              'submittedby'=>'test@somebody.com',
              'submittedby_external_id'=> 25
            ],
            'formitems'=>[
              '0'=>[
                'Data'=>[
                  'datavalue'=>'TestSubmissionFirstname'
                ]
              ],
              '1'=>[
                'Data'=>[
                  'datavalue'=>'TestSubmissionLastname'
                ]
              ],
              '2'=>[
                'Data'=>[
                  'datavalue'=>'TestSubmissionDescription',
                  'fileuploads'=>[
                    '0'=>[
                        'filename'=>'somefiletest1.jpg',
                        'filekey'=>'somefilekey1',
                        'storageurl'=>'https://storageurl.com/1',
                        'bytes'=>123456
                    ],
                    '1'=>[
                        'filename'=>'somefiletest2.jpg',
                        'filekey'=>'somefilekey2',
                        'storageurl'=>'https://storageurl.com/2',
                        'bytes'=>1234567
                    ]
                  ]
                ]
              ]
            ]
          ]);

          //check data persisted
          $responsecontent = json_decode($response->getContent());

          $itemdata1 = $responsecontent->formitems[0]->Data;
          $this->assertDatabaseHas('submissiondatas', [
              'id' => $itemdata1->id,
              'datavalue'=>'TestSubmissionFirstname'
          ]);
          $itemdata2 = $responsecontent->formitems[1]->Data;
          $this->assertDatabaseHas('submissiondatas', [
              'id' => $itemdata2->id,
              'datavalue'=>'TestSubmissionLastname'
          ]);
          $itemdata3 = $responsecontent->formitems[2]->Data;
          $this->assertDatabaseHas('submissiondatas', [
              'id' => $itemdata3->id,
              'datavalue'=>'TestSubmissionDescription'
          ]);

          $file1 = $responsecontent->formitems[2]->Data->fileuploads[0];
          $file2 = $responsecontent->formitems[2]->Data->fileuploads[1];
          $this->assertDatabaseHas('fileuploads', [
              'id' => $file1->id,
              'filename'=>'somefiletest1.jpg',
              'filekey'=>'somefilekey1',
              'storageurl'=>'https://storageurl.com/1',
              'bytes'=>123456
          ]);
          $this->assertDatabaseHas('fileuploads', [
              'id' => $file2->id,
              'filename'=>'somefiletest2.jpg',
              'filekey'=>'somefilekey2',
              'storageurl'=>'https://storageurl.com/2',
              'bytes'=>1234567
          ]);

    }



    /**
     * Given I am submitting a form with data to /api/submissions/add endpoint
     *  and the submitted data structure is incorrect
     *  then I should see a 422 invalid response.
     *
     *
     *
     */
    public function test_add_new_submission_invalid_request_structure_should_fail() {
      $api_token = 'b0d4ee07-a0d2-44c6-bb22-8bf82de8eea8';

      $data = '{
                        "api_token": "' . $api_token . '",
                        "tag": "TestSubmissionTag1234",
                        "submittedby": "test@somebody.com",
                        "submittedby_external_id": 25,
                        "id": 1,
                        "formitems": [
                          {
                            "id": 1,
                            "Datas": {
                              "datavalue": "TestSubmissionFirstname",
                              "fileuploads": []
                            }
                          },
                          {
                            "id": 2
                          },
                          {
                            "id": 3,
                            "Data": {
                              "datavalue": "TestSubmissionDescription",
                              "fileuploads": [
                                {
                              		"filename": "somefiletest1.jpg",
                              		"filekey": "somefilekey1",
                              		"storageurl": "https://storageurl.com/1",
                              		"bytes": 123456
                              	},
                                {
                              		"filekey": "somefilekey2",
                              		"storageurl": "https://storageurl.com/2",
                              		"bytes": 1234567
                              	}
                              ]
                            }
                          }
                        ]
                      }';
        $data = json_decode($data,true);
        $response = $this->json('POST', '/api/submissions/add', $data);
        $response->assertStatus(422);
    }




}
