<?php

use Illuminate\Database\Seeder;
use App\Form;
use App\Formitem;
use App\Formsubmission;
use App\Submissiondata;
use Ramsey\Uuid\Uuid;


class FormsTableSeeder extends Seeder
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
        $form->application_id = 1;
        $form->name = "Test form";
        $form->description = "This is a test form for testing.";
        $uuid4 = Uuid::uuid4();
        $form->uid = $uuid4->toString();
        $form->tag = "testformtag001";
        $form->createdby = "createdby@test.com";
        $form->createdby_external_id = 1;
        $form->save();

        //FORM ITEMS -
        //Firstname text
        //Lastname text
        //Description textarea

        $uuid4 = Uuid::uuid4();
        $firstname = new Formitem([
          'form_id'=>$form->id,
          'uid' => $uuid4->toString(),
          'itemtype_id' => 1, // text
          'ordering' => 1,
          'name' => 'Firstname',
          'required' => true,
          'configuration' => '{}',
          'validation' => '{}'
        ]);
        $firstname->save();

        $uuid4 = Uuid::uuid4();
        $lastname = new Formitem([
          'form_id'=>$form->id,
          'uid' => $uuid4->toString(),
          'itemtype_id' => 1, // text
          'ordering' => 2,
          'name' => 'Lastname',
          'required' => true,
          'configuration' => '{}',
          'validation' => '{}'
        ]);
        $lastname->save();

        $uuid4 = Uuid::uuid4();
        $description = new Formitem([
          'form_id'=>$form->id,
          'uid' => $uuid4->toString(),
          'itemtype_id' => 2, // text area
          'ordering' => 3,
          'name' => 'Description',
          'required' => true,
          'configuration' => '{}',
          'validation' => '{}'
        ]);
        $description->save();


        //========================================
        //SUBMISSION 1
        $submission = new Formsubmission;
        $uuid4 = Uuid::uuid4();
        $submission->uid = $uuid4->toString();
        $submission->form_id = $form->id;
        $submission->tag = 'testsubmissiontag001';
        $submission->submittedby = 'submittedby@test.com';
        $submission->submittedby_external_id = 1;
        $submission->save();

        //Submission Data
        $data = new Submissiondata;
        $data->formsubmission_id = $submission->id;
        $data->formitem_id = $firstname->id;
        $data->datavalue = "TestFirstname";
        $data->save();

        $data = new Submissiondata;
        $data->formsubmission_id = $submission->id;
        $data->formitem_id = $lastname->id;
        $data->datavalue = "TestLastname";
        $data->save();

        $data = new Submissiondata;
        $data->formsubmission_id = $submission->id;
        $data->formitem_id = $description->id;
        $data->datavalue = "This is a test description";
        $data->save();

        //========================================
        //SUBMISSION 2
        $submission = new Formsubmission;
        $uuid4 = Uuid::uuid4();
        $submission->uid = $uuid4->toString();
        $submission->form_id = $form->id;
        $submission->tag = 'testsubmissiontag001';
        $submission->submittedby = 'submittedby@test.com';
        $submission->submittedby_external_id = 1;
        $submission->save();

        //Submission Data
        $data = new Submissiondata;
        $data->formsubmission_id = $submission->id;
        $data->formitem_id = $firstname->id;
        $data->datavalue = "TestFirstname2";
        $data->save();

        $data = new Submissiondata;
        $data->formsubmission_id = $submission->id;
        $data->formitem_id = $lastname->id;
        $data->datavalue = "TestLastname2";
        $data->save();

        $data = new Submissiondata;
        $data->formsubmission_id = $submission->id;
        $data->formitem_id = $description->id;
        $data->datavalue = "This is a second test submission.";
        $data->save();






    }

}
