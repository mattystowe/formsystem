<?php

use Illuminate\Database\Seeder;
use App\Form;
use App\Formsubmission;
use Ramsey\Uuid\Uuid;

class FormSearchTestSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      //FORM====================================================
      $form = new Form;
      $form->application_id = 1; // flowtracker live
      $form->name = "Form 001";
      $form->description = "This is a test form for testing.";
      $uuid4 = Uuid::uuid4();
      $form->uid = $uuid4->toString();
      $form->tag = "tagtest001";
      $form->createdby = "createdby@test.com";
      $form->createdby_external_id = 1;
      $form->save();

      //FORM====================================================
      $form = new Form;
      $form->application_id = 1; // flowtracker live
      $form->name = "Form 002";
      $form->description = "This is a test form for testing.";
      $uuid4 = Uuid::uuid4();
      $form->uid = $uuid4->toString();
      $form->tag = "tagtest002";
      $form->createdby = "createdby@test.com";
      $form->createdby_external_id = 1;
      $form->save();

      //FORM====================================================
      $form = new Form;
      $form->application_id = 1; // flowtracker live
      $form->name = "Form 003";
      $form->description = "This is a test form for testing.";
      $uuid4 = Uuid::uuid4();
      $form->uid = $uuid4->toString();
      $form->tag = "tagtestA";
      $form->createdby = "createdby@test.com";
      $form->createdby_external_id = 1;
      $form->save();




      //SEARCH TESTS===============================================
      //
      //
      //
      //
      //
      //
      $form = new Form;
      $form->application_id = 1; // flowtracker live
      $form->name = "SearchTestForm001";
      $form->description = "This is a test form for testing.";
      $uuid4 = Uuid::uuid4();
      $form->uid = $uuid4->toString();
      $form->tag = "SUPPLIER001";
      $form->createdby = "createdby@test.com";
      $form->createdby_external_id = 1;
      $form->save();


                  $submission = new Formsubmission;
                  $uuid4 = Uuid::uuid4();
                  $submission->uid = $uuid4->toString();
                  $submission->form_id = $form->id;
                  $submission->tag = "PROP001";
                  $submission->submittedby = "Person 1";
                  $submission->submittedby_external_id = 1;
                  $submission->save();


      $form = new Form;
      $form->application_id = 1; // flowtracker live
      $form->name = "SearchTestForm002";
      $form->description = "This is a test form for testing.";
      $uuid4 = Uuid::uuid4();
      $form->uid = $uuid4->toString();
      $form->tag = "PRIVATE_SUPPLIER001";
      $form->createdby = "createdby@test.com";
      $form->createdby_external_id = 1;
      $form->save();

                  $submission = new Formsubmission;
                  $uuid4 = Uuid::uuid4();
                  $submission->uid = $uuid4->toString();
                  $submission->form_id = $form->id;
                  $submission->tag = "PROP001";
                  $submission->submittedby = "Person 2";
                  $submission->submittedby_external_id = 1;
                  $submission->save();



      $form = new Form;
      $form->application_id = 1; // flowtracker live
      $form->name = "SearchTestForm003";
      $form->description = "This is a test form for testing.";
      $uuid4 = Uuid::uuid4();
      $form->uid = $uuid4->toString();
      $form->tag = "ORG001";
      $form->createdby = "createdby@test.com";
      $form->createdby_external_id = 1;
      $form->save();

                  $submission = new Formsubmission;
                  $uuid4 = Uuid::uuid4();
                  $submission->uid = $uuid4->toString();
                  $submission->form_id = $form->id;
                  $submission->tag = "PROP001";
                  $submission->submittedby = "Person 3";
                  $submission->submittedby_external_id = 1;
                  $submission->save();



      $form = new Form;
      $form->application_id = 1; // flowtracker live
      $form->name = "SearchTestForm004";
      $form->description = "This is a test form for testing.";
      $uuid4 = Uuid::uuid4();
      $form->uid = $uuid4->toString();
      $form->tag = "PRIVATE_ORG001";
      $form->createdby = "createdby@test.com";
      $form->createdby_external_id = 1;
      $form->save();

                  $submission = new Formsubmission;
                  $uuid4 = Uuid::uuid4();
                  $submission->uid = $uuid4->toString();
                  $submission->form_id = $form->id;
                  $submission->tag = "PROP001";
                  $submission->submittedby = "Person 4";
                  $submission->submittedby_external_id = 1;
                  $submission->save();







    //ARCHIVE TEST========================================
    $form = new Form;
    $form->application_id = 1; // flowtracker live
    $form->name = "Archived001";
    $form->description = "This is a test form for testing.";
    $uuid4 = Uuid::uuid4();
    $form->uid = $uuid4->toString();
    $form->tag = "ARCHIVETEST";
    $form->createdby = "createdby@test.com";
    $form->createdby_external_id = 1;
    $form->isarchived = true;
    $form->save();

    $form = new Form;
    $form->application_id = 1; // flowtracker live
    $form->name = "NotArchived001";
    $form->description = "This is a test form for testing.";
    $uuid4 = Uuid::uuid4();
    $form->uid = $uuid4->toString();
    $form->tag = "ARCHIVETEST";
    $form->createdby = "createdby@test.com";
    $form->createdby_external_id = 1;
    $form->save();


    //multiple UIDs search test=============================
    $form = new Form;
    $form->application_id = 1; // flowtracker live
    $form->name = "MultipleUIDTest1";
    $form->description = "This is a test form for testing.";
    $form->uid = "UIDTEST001";
    $form->tag = "UIDTEST";
    $form->createdby = "createdby@test.com";
    $form->createdby_external_id = 1;
    $form->save();

    $form = new Form;
    $form->application_id = 1; // flowtracker live
    $form->name = "MultipleUIDTest2";
    $form->description = "This is a test form for testing.";
    $form->uid = "UIDTEST002";
    $form->tag = "UIDTEST";
    $form->createdby = "createdby@test.com";
    $form->createdby_external_id = 1;
    $form->save();






    }
}
