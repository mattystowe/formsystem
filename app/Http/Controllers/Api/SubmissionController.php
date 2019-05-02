<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Formsubmission;
use App\Submissiondata;
use App\Fileupload;
use App\Form;
use Log;
use DB;
use Ramsey\Uuid\Uuid;

class SubmissionController extends Controller
{


    /**
     * Get a list of submissions available for formuid and submission tag
     *
     *
     * @param  [type] $formuid       [description]
     * @param  [type] $submissiontag [description]
     * @return [type]                [description]
     */
    public function listByTagAndForm($submissiontag, $formuid,  Request $request) {
          Log::debug('Listing submissions by Tag: ' . $submissiontag . ' and Form: ' . $formuid . ' for application_id: ' . $request->application_id);
          $formsubmission = new Formsubmission;
          $results = $formsubmission->getByTagAndForm($request->application_id, $submissiontag,$formuid);
          return $results;
    }



    /**
     * Get a list of all form submissions available with a certain tag
     *
     *
     * @param  [type]  $submissiontag [description]
     * @param  Request $request       [description]
     * @return [type]                 [description]
     */
    public function listByTag($submissiontag, Request $request) {
      Log::debug('Listing submissions by Tag: ' . $submissiontag . ' for application_id: ' . $request->application_id);
      $formsubmission = new Formsubmission;
      $results = $formsubmission->getByTag($request->application_id, $submissiontag);
      return $results;
    }



    /**
     * List all submissions for a form
     *
     *
     *
     * @param  [type]  $formuid [description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function listByForm($formuid, Request $request) {
      Log::debug('Listing submissions by Form: ' . $formuid . ' for application_id: ' . $request->application_id);
      $formsubmission = new Formsubmission;
      $results = $formsubmission->getByForm($request->application_id, $formuid);
      return $results;
    }



    /**
     * Get a form submission by its uid alongside its definitions
     *
     *
     *
     *
     * @param  [type]  $submissionuid [description]
     * @param  Request $request       [description]
     * @return [type]                 [description]
     */
    public function getByUid($submissionuid, Request $request) {
      Log::debug('Loading submission: ' . $submissionuid . ' for application_id: ' . $request->application_id);
      $formsubmission = new Formsubmission;
      if ($result = $formsubmission->getByUid($request->application_id, $submissionuid)) {
        return $result;
      } else {
        return response('Submission not found.',400);
      }
    }




    /**
     * Handle the saving of a new submission
     *
     *
     *
     * @param Request $request [description]
     */
    public function add(Request $request) {
        if ($this->isSubmissionStructureValid($request)) {

              if ($form = Form::find($request->id)) {
                if ($form->application_id == $request->application_id) {


                        $submission = new Formsubmission;
                        $uuid4 = Uuid::uuid4();
                        $submission->uid = $uuid4->toString();
                        $submission->form_id = $form->id;
                        $submission->tag = $request->tag;
                        $submission->submittedby = $request->submittedby;
                        $submission->submittedby_external_id = $request->submittedby_external_id;
                        if ($submission->save()) {

                          //loop round the items in the request and save the data.
                          foreach ($request->formitems as $item) {
                            $itemdata = new Submissiondata;
                            $itemdata->formsubmission_id = $submission->id;
                            $itemdata->formitem_id = $item['id'];
                            $itemdata->datavalue = $item['Data']['datavalue'];
                            $itemdata->save();
                            if (is_array($item['Data']['fileuploads']) && count($item['Data']['fileuploads'])>0) {
                              //
                              //save file uploads to the data
                              foreach ($item['Data']['fileuploads'] as $file) {
                                $fileupload = new Fileupload;
                                $fileupload->submissiondata_id = $itemdata->id;
                                $fileupload->filename = $file['filename'];
                                $fileupload->filekey = $file['filekey'];
                                $fileupload->storageurl = $file['storageurl'];
                                $fileupload->bytes = $file['bytes'];
                                $fileupload->save();
                              }
                            }
                          }


                          //respond with formsubmission
                          $formsubmission = new Formsubmission;
                          $results = $formsubmission->getByUid($request->application_id, $submission->uid);
                          return $results;



                        } else {
                          //error saving
                          return response('Error saving form', 500);
                        }
                  } else {
                    //form does not belong to application
                    return response('Form not found.',400);
                  }
              } else {
                return response('Form not found.',400);
              }
        } else {
          return response('Invalid request', 422);
        }
    }





    /**
     * Is the structure of the submitted data correct
     *
     *
     *
     * @param  [type]  $databody [description]
     * @return boolean           [description]
     */
    private function isSubmissionStructureValid($request) {
      $isvalid = true;

      if (!isset($request->id)) { $isvalid = false; }
      if (!isset($request->formitems)) { $isvalid = false; }

      if (isset($request->formitems)) {
        if (!is_array($request->formitems)) { $isvalid = false; }
        //check for each of the formitems is valid.
        foreach ($request->formitems as $formitem) {

          if (!isset($formitem['id'])) { $isvalid = false; }
          if (!isset($formitem['Data'])) { $isvalid = false; } else {

              if (!isset($formitem['Data']['fileuploads'])) { $isvalid = false; }
              if (!is_array($formitem['Data']['fileuploads'])) { $isvalid = false; }

              foreach ($formitem['Data']['fileuploads'] as $file) {
                if (!isset($file['filename'])) { $isvalid = false; }
                if (!isset($file['filekey'])) { $isvalid = false; }
                if (!isset($file['storageurl'])) { $isvalid = false; }
                if (!isset($file['bytes'])) { $isvalid = false; }
              }
          }
        }
      }

      return $isvalid;
    }







    /**
     * Search for submissions
     *
     *
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function search(Request $request) {
      $submission = new Formsubmission;
      return $submission->search($request);
    }


}
