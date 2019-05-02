<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Application;
use DB;

class ApiSubmissionsSearchTest extends TestCase
{


  private $expected_returned_submissions_structure = [
      '*'=>[
        'id',
        'created_at',
        'updated_at',
        'uid',
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
  ];




    /**
     * Given that I have submitted search params to api/submissions/search
     *  and have specified 1 form tag PRIVATE_SUPPLIER001
     *  I should see results in correct json format
     *  and I should see 1 result from the seed data
     *
     *
     *
     * @return [type] [description]
     */
    public function test_get_by_formtag_only() {
      //Set up the application to submit to.
      $application = Application::find(1); // flowtracker live
      $api_token = $application->api_key;


      //add form.
      //
      $postdata = '{
                    "api_token": "' . $api_token . '",
                    "forms": {
                    	"tags": {
                  	  	"has":["PRIVATE_SUPPLIER001"],
                  	  	"contains": "",
                  		"startswith": [],
                  		"endswith": []
                  	  }
                    },
                    "ordering": {
                    	"by": "created_at",
                    	"order": "asc"
                    }
                  }';
      $response = $this->json('POST', '/api/submissions/search', json_decode($postdata,true));

      $response->assertJsonStructure($this->expected_returned_submissions_structure);
      $results = json_decode($response->getContent());
      $this->assertEquals(count($results),1);


    }

    

    /**
     * Given that I have submitted search params to api/submissions/search
     *  and have specified 1 submission tag PROP001
     *  and have specified 2 form tags ORG001 and PRIVATE_ORG001
     *  and have specified to also incude form tags starting with SUPPLIER
     *  then I should see results in correct json format
     *  and I should see 3 submission results from the seed data.
     *  and the submissions returned should NOT be from forms tagged with PRIVATE_SUPPLIER001
     *
     *  Real world scenario - eg - Client needing to get all form submissions for property 001 for Forms
     *  that the Organisation own (public and private) and also supplier 001 own publically.  Must not show submissions for forms
     *  that are owned privately by supplier.
     *
     *
     * @return [type] [description]
     */
    public function test_get_by_multiple_tags_plus_startswith_1()
    {

      //Set up the application to submit to.
      $application = Application::find(1); // flowtracker live
      $api_token = $application->api_key;


      //add form.
      //
      $postdata = '{
                    "api_token": "' . $api_token . '",
                    "submissions": {
                  	  "tags": {
                  	  	"has":["PROP001"],
                  	  	"contains": "",
                  		"startswith": [],
                  		"endswith": []
                  	  }
                    },
                    "forms": {
                    	"tags": {
                  	  	"has":["ORG001","PRIVATE_ORG001"],
                  	  	"contains": "",
                  		"startswith": ["SUPP"],
                  		"endswith": []
                  	  }
                    },
                    "ordering": {
                    	"by": "created_at",
                    	"order": "asc"
                    }
                  }';
      $response = $this->json('POST', '/api/submissions/search', json_decode($postdata,true));

      $response->assertJsonStructure($this->expected_returned_submissions_structure);
      $response->assertJson([
        '0'=>[
          'submittedby'=>'Person 1'
        ],
        '1'=>[
          'submittedby'=>'Person 3'
        ],
        '2'=>[
          'submittedby'=>'Person 4'
        ]
      ]);

      $results = json_decode($response->getContent());

      $this->assertEquals(count($results),3);

      //list of forms
      $formIds = array();
      foreach ($results as $submission) {
        $formIds[] = $submission->form_id;
      }

      $forms = DB::table('forms')->whereIn('id',$formIds)->where('tag','=','PRIVATE_SUPPLIER001')->get();

      $this->assertEquals(count($forms), 0);


    }






    /**
     * Given that I have submitted search params to api/submissions/search
     *  and have specified 1 submission tag PROP001
     *  and have specified 3 form tags ORG001 and SUPPLIER001 and PRIVATE_SUPPLIER001
     *  and have specified to also incude form tags starting with SUPPLIER
     *  then I should see results in correct json format
     *  and I should see 3 submission results from the seed data.
     *  and the submissions returned should NOT be from forms tagged with PRIVATE_ORG001
     *
     *  Real world scenario - eg - Supplier needing to get all form submissions for property 001 for Forms
     *  that the supplier owns (public and private) and also organisation 001 own publically.  Must not show submissions for forms
     *  that are owned privately by organisation001.
     *
     *
     * @return [type] [description]
     */
    public function test_get_by_multiple_tags_plus_startswith_2()
    {

      //Set up the application to submit to.
      $application = Application::find(1); // flowtracker live
      $api_token = $application->api_key;


      //add form.
      //
      $postdata = '{
                    "api_token": "' . $api_token . '",
                    "submissions": {
                  	  "tags": {
                  	  	"has":["PROP001"],
                  	  	"contains": "",
                  		"startswith": [],
                  		"endswith": []
                  	  }
                    },
                    "forms": {
                    	"tags": {
                  	  	"has":["ORG001","SUPPLIER001","PRIVATE_SUPPLIER001"],
                  	  	"contains": "",
                  		"startswith": ["SUPP"],
                  		"endswith": []
                  	  }
                    },
                    "ordering": {
                    	"by": "created_at",
                    	"order": "asc"
                    }
                  }';
      $response = $this->json('POST', '/api/submissions/search', json_decode($postdata,true));

      $response->assertJsonStructure($this->expected_returned_submissions_structure);
      $response->assertJson([
        '0'=>[
          'submittedby'=>'Person 1'
        ],
        '1'=>[
          'submittedby'=>'Person 2'
        ],
        '2'=>[
          'submittedby'=>'Person 3'
        ]
      ]);

      $results = json_decode($response->getContent());

      $this->assertEquals(count($results),3);

      //list of forms
      $formIds = array();
      foreach ($results as $submission) {
        $formIds[] = $submission->form_id;
      }

      $forms = DB::table('forms')->whereIn('id',$formIds)->where('tag','=','PRIVATE_ORG001')->get();

      $this->assertEquals(count($forms), 0);


    }


}
