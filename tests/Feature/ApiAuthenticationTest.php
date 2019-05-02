<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ApiAuthenticationTest extends TestCase
{

  use DatabaseTransactions;

  
    /**
     * Given I make a call using invalid api_key,
     *   I should see a 401 unauthorised response from the server.
     *
     * @return void
     */
    public function test_response_should_be_401_unauthorised_for_invalid_api_key()
    {
      $response = $this->get('/api/?api_token=someinvalidapikey');
      $response->assertStatus(401);
    }


    /**
     * Given I make a call using no api_key,
     *   I should see a 401 unauthorised response from the server.
     *
     * @return void
     */
    public function test_response_should_be_401_unauthorised_for_no_api_key() {
      $response = $this->get('/api');
      $response->assertStatus(401);
    }



    /**
     * Given I make a call to the api / with a valid api_token,
     *  and the api_token is for the application "flowtracker live"
     *  then I should see a json response,
     *  and the response should contain name:'flowtracker live'
     *
     *
     * @return [type] [description]
     */
    public function test_response_should_be_application_with_valid_api_key() {

      //$response = $this->json('GET', '/api/?api_token=b0d4ee07-a0d2-44c6-bb22-8bf82de8eea8');
      $response = $this->get('/api/?api_token=b0d4ee07-a0d2-44c6-bb22-8bf82de8eea8');
      $response
          ->assertStatus(200)
          ->assertJson([
              'name' => 'flowtracker live',
          ]);
    }
}
