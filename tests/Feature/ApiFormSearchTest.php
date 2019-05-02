<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ApiFormSearchTest extends TestCase
{


    private $expected_returned_forms_structure = [
                '*' => [
                    'id',
                    'name',
                    'description',
                    'uid',
                    'tag',
                    'createdby',
                    'createdby_external_id'
                ]
            ];


    /**
     * Given that I have submitted a search request to /api/forms/search
     *  and the tags include tagtest001 and tagtest002
     *  I should see json results with a collection of form objects
     *  I should see results containing 2 form items ordered by form name
     *
     */
    public function test_get_by_multiple_tags_assert_for_2_tags()
    {



        $api_token = 'b0d4ee07-a0d2-44c6-bb22-8bf82de8eea8';
        $data = '{
                "api_token": "' . $api_token . '",
                "tags": {
                	"has":[
                		"tagtest001",
                		"tagtest002"
                	],
                	"contains": "",
              	  "startswith": [],
              	  "endswith": []
                },
                "ordering": {
                	"by": "name",
                	"order": "asc"
                }
              }';
        $response = $this->json('POST', '/api/forms/search', json_decode($data,true));
        $response->assertJsonStructure($this->expected_returned_forms_structure);
        $forms = json_decode($response->getContent());
        //2 forms returned
        $this->assertEquals(count($forms), 2);

        $this->assertEquals($forms[0]->name,'Form 001' );
        $this->assertEquals($forms[1]->name,'Form 002' );

    }





    /**
     * Given that I have submitted a search request to /api/forms/search
     *  and the tags include only a single tag tagtest002
     *  I should see json results with a collection of form objects
     *  I should see results containing 1 form item for form with name Form 002
     *
     */
    public function test_get_by_multiple_tags_assert_for_1_tag()
    {


        $api_token = 'b0d4ee07-a0d2-44c6-bb22-8bf82de8eea8';
        $data = '{
                "api_token": "' . $api_token . '",
                "tags": {
                	"has":[
                		"tagtest002"
                	],
                	"contains": "",
              	  "startswith": [],
              	  "endswith": []
                },
                "ordering": {
                	"by": "name",
                	"order": "asc"
                }
              }';
        $response = $this->json('POST', '/api/forms/search', json_decode($data,true));
        $response->assertJsonStructure($this->expected_returned_forms_structure);
        $forms = json_decode($response->getContent());
        //2 forms returned
        $this->assertEquals(count($forms), 1);

        $this->assertEquals($forms[0]->name,'Form 002' );

    }





    /**
     * Given that I have submitted a search request to /api/forms/search
     *  and the tags include tagtest001 and tagtest002
     *  and I specify the tags can end with 02
     *  I should see json results with a collection of form objects
     *  I should see results containing 1 form item with name Form 002
     *
     */
    public function test_get_by_multiple_tags_assert_for_2_tags_ending_in_02()
    {



        $api_token = 'b0d4ee07-a0d2-44c6-bb22-8bf82de8eea8';
        $data = '{
                "api_token": "' . $api_token . '",
                "tags": {
                	"has":[
                		"tagtest001",
                		"tagtest002"
                	],
                	"contains": "",
              	  "startswith": [],
              	  "endswith": ["02"]
                },
                "ordering": {
                	"by": "name",
                	"order": "asc"
                }
              }';
        $response = $this->json('POST', '/api/forms/search', json_decode($data,true));
        $response->assertJsonStructure($this->expected_returned_forms_structure);
        $forms = json_decode($response->getContent());
        //2 forms returned
        $this->assertEquals(count($forms), 2);

        $this->assertEquals($forms[0]->name,'Form 001' );

    }


    /**
     * Given that I have submitted a search request to /api/forms/search
     * and I specify tags ending with string:A
     * I should see json results with a collection of form objects
     * I should see results containing 1 form item with name Form 003
     *
     *
     * @return [type] [description]
     */
    public function test_get_tags_ending_with_string() {
      $api_token = 'b0d4ee07-a0d2-44c6-bb22-8bf82de8eea8';
      $data = '{
              "api_token": "' . $api_token . '",
              "tags": {
                "contains": "",
                "startswith": [],
                "endswith": ["A"]
              },
              "ordering": {
                "by": "name",
                "order": "asc"
              }
            }';
      $response = $this->json('POST', '/api/forms/search', json_decode($data,true));
      $response->assertJsonStructure($this->expected_returned_forms_structure);
      $forms = json_decode($response->getContent());
      //2 forms returned
      $this->assertEquals(count($forms), 1);

      $this->assertEquals($forms[0]->name,'Form 003' );
    }




    /**
     * Given that I have submitted a search request to /api/forms/search
     * and I specify 3 tags tagtest001, tagtest002, tagtestA
     * and I specify ordering by form name DESC
     * I should see json results with a collection of form objects
     * I should see results containing 3 form item with the last item being form name Form 001
     */
    public function test_get_by_multiple_tags_assert_for_3_tags_ordered_by_name_desc() {
      $api_token = 'b0d4ee07-a0d2-44c6-bb22-8bf82de8eea8';
      $data = '{
              "api_token": "' . $api_token . '",
              "tags": {
                "has":[
                  "tagtest001",
                  "tagtest002",
                  "tagtestA"
                ],
                "contains": "",
                "startswith": [],
                "endswith": []
              },
              "ordering": {
                "by": "name",
                "order": "desc"
              }
            }';
      $response = $this->json('POST', '/api/forms/search', json_decode($data,true));
      $response->assertJsonStructure($this->expected_returned_forms_structure);
      $forms = json_decode($response->getContent());
      //2 forms returned
      $this->assertEquals(count($forms), 3);

      $this->assertEquals($forms[2]->name,'Form 001' );
    }




    /**
     * Given that I have submitted a search request to /api/forms/search
     *  and I specify 3 multiple tags SUPPLIER001 and PRIVATE_SUPPLIER001 and ORG001
     *  and tags can also start with SUPPLIER
     *  I should get a response in the correct json format
     *
     * Real world example on flowtracker - Supplier getting all available forms owned by himself privately and publically
     * alongside forms published publically by the organisation or other suppliers
     *
     *
     *
     * @return [type] [description]
     */
    public function test_get_by_multiple_tags_and_ends_with()
    {



        $api_token = 'b0d4ee07-a0d2-44c6-bb22-8bf82de8eea8';
        $data = '{
                "api_token": "' . $api_token . '",
                "tags": {
                	"has":[
                		"SUPPLIER001",
                		"PRIVATE_SUPPLIER001",
                    "ORG001"
                	],
                	"contains": "",
              	  "startswith": ["SUPPLIER"],
              	  "endswith": []
                },
                "ordering": {
                	"by": "name",
                	"order": "asc"
                }
              }';
        $response = $this->json('POST', '/api/forms/search', json_decode($data,true));
        $response->assertJsonStructure($this->expected_returned_forms_structure);
        $forms = json_decode($response->getContent());
        //2 forms returned
        $this->assertEquals(count($forms), 3);

    }





    /**
     * Given I specify the archive parameter to only include archived forms
     *  then I get a json response
     *  and the json response only contains 1 form with name Archived001
     *
     *
     *
     *
     *
     * @return [type] [description]
     */
    public function test_get_only_archived_forms() {
      $api_token = 'b0d4ee07-a0d2-44c6-bb22-8bf82de8eea8';
      $data = '{
              "api_token": "' . $api_token . '",
              "tags": {
                "has":[
                  "ARCHIVETEST"
                ],
                "contains": "",
                "startswith": [],
                "endswith": []
              },
              "ordering": {
                "by": "name",
                "order": "asc"
              },
              "archive":"onlyArchived"
            }';
      $response = $this->json('POST', '/api/forms/search', json_decode($data,true));
      $response->assertJsonStructure($this->expected_returned_forms_structure);
      $forms = json_decode($response->getContent());
      //2 forms returned
      $this->assertEquals(count($forms), 1);
      $this->assertEquals($forms[0]->name, "Archived001");
    }





    /**
     * Given I specify the archive parameter to include archived AND nonarchived forms
     *  then I get a json response
     *  and the json response only contains 2 Forms
     *
     *
     *
     *
     *
     * @return [type] [description]
     */
    public function test_get_archived_and_nonarchived_forms() {
      $api_token = 'b0d4ee07-a0d2-44c6-bb22-8bf82de8eea8';
      $data = '{
              "api_token": "' . $api_token . '",
              "tags": {
                "has":[
                  "ARCHIVETEST"
                ],
                "contains": "",
                "startswith": [],
                "endswith": []
              },
              "ordering": {
                "by": "name",
                "order": "asc"
              },
              "archive":"includeAll"
            }';
      $response = $this->json('POST', '/api/forms/search', json_decode($data,true));
      $response->assertJsonStructure($this->expected_returned_forms_structure);
      $forms = json_decode($response->getContent());
      //2 forms returned
      $this->assertEquals(count($forms), 2);
    }





    public function test_exclude_archived_forms_by_default() {
      $api_token = 'b0d4ee07-a0d2-44c6-bb22-8bf82de8eea8';
      $data = '{
              "api_token": "' . $api_token . '",
              "tags": {
                "has":[
                  "ARCHIVETEST"
                ],
                "contains": "",
                "startswith": [],
                "endswith": []
              },
              "ordering": {
                "by": "name",
                "order": "asc"
              }
            }';
      $response = $this->json('POST', '/api/forms/search', json_decode($data,true));
      $response->assertJsonStructure($this->expected_returned_forms_structure);
      $forms = json_decode($response->getContent());
      //2 forms returned
      $this->assertEquals(count($forms), 1);
      $this->assertEquals($forms[0]->name, "NotArchived001");
    }




    /**
     * Given I specify an array of 3 form ids in the form search request
     *  then I get a json response
     *  and the json response should contain 3 Forms for specified Ids in the seed.
     *
     *
     *
     */
    public function test_search_multiple_formids() {
      $api_token = 'b0d4ee07-a0d2-44c6-bb22-8bf82de8eea8';
      $data = '{
              "api_token": "' . $api_token . '",
              "id": [1,2,3],
              "ordering": {
                "by": "id",
                "order": "asc"
              }
            }';
      $response = $this->json('POST', '/api/forms/search', json_decode($data,true));
      $response->assertJsonStructure($this->expected_returned_forms_structure);
      $forms = json_decode($response->getContent());
      //3 forms returned
      $this->assertEquals(count($forms), 3);
      //with ids in following order
      $this->assertEquals($forms[0]->id, 1);
      $this->assertEquals($forms[1]->id, 2);
      $this->assertEquals($forms[2]->id, 3);
    }



    /**
     * Given I specify an array of 2 form uids in the form search request
     *  then I get a json response
     *  and the json response should contain 2 Forms for specified uIds in the seed.
     *
     *
     *
     */
    public function test_search_multiple_formuids() {
      $api_token = 'b0d4ee07-a0d2-44c6-bb22-8bf82de8eea8';
      $data = '{
              "api_token": "' . $api_token . '",
              "uid": [
                "UIDTEST001",
                "UIDTEST002"
              ],
              "ordering": {
                "by": "id",
                "order": "asc"
              }
            }';
      $response = $this->json('POST', '/api/forms/search', json_decode($data,true));
      $response->assertJsonStructure($this->expected_returned_forms_structure);
      $forms = json_decode($response->getContent());
      //3 forms returned
      $this->assertEquals(count($forms), 2);
      //with ids in following order
      $this->assertEquals($forms[0]->uid, "UIDTEST001");
      $this->assertEquals($forms[1]->uid, "UIDTEST002");
    }



}
