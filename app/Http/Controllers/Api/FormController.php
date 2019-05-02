<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Form;
use App\Formitem;
use Log;
use Ramsey\Uuid\Uuid;
use DB;


class FormController extends Controller
{


    /**
     * Get a form definition by its uid.
     *
     *
     *
     *
     * @param  [type]  $uid     [description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getForm($uid, Request $request) {
      Log::debug("getting form: " . $uid. " for application id " . $request->application_id);
      $form = new Form;
      if ($result = $form->getByUid($uid,$request->application_id)) {
        return $result;
      } else {
        return response('Form not found',400);
      }
    }



    /**
     * Get a results list of forms by ?tag and/or ?name search.
     *
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function listByTag($formtag, Request $request) {
      Log::debug("Searching forms with tag: " . $formtag . " for application id " . $request->application_id);
      $form = new Form;
      $result = $form->getByTag($request->application_id, $formtag);
      return $result;

    }



    /**
     * Add a new form and save its definitions.  Return the form.
     *
     *
     *
     *
     * @param Request $request [description]
     */
    public function addForm(Request $request) {
      Log::debug('Adding form to application_id ' . $request->application_id);

      if ($this->isNewFormRequestValid($request)) {
            $form = new Form;
            $form->application_id = $request->application_id;
            $uuid4 = Uuid::uuid4();
            $form->uid = $uuid4->toString();
            $form->name = $request->name;
            $form->description = $request->description;
            $form->tag = $request->tag;
            $form->createdby = $request->createdby;
            $form->createdby_external_id = $request->createdby_external_id;
            if ($form->save()) {

              //work through items and add to the form.
              foreach ($request->formitems as $formitem) {
                $item = new Formitem;
                $uuid4 = Uuid::uuid4();
                $item->uid = $uuid4->toString();
                $item->itemtype_id = $formitem['itemtype']['id'];
                $item->ordering = $formitem['ordering'];
                if (!isset($formitem['name'])) { $formitem['name'] = ""; }
                $item->name = $formitem['name'];
                $item->required = $formitem['required'];
                $item->configuration = json_encode($formitem['configuration']);
                $item->validation = json_encode($formitem['validation']);
                $form->formitems()->save($item);
              }


              //return the form in the correct format
              $result = $form->getByUid($form->uid,$request->application_id);
              return $result;


            } else {
              return response('Error saving form',500);
            }
      } else {
        return response('Invalid request', 422);
      }
    }


    /**
     * Validate the newform request input is correct
     *
     *
     *
     * @param  [type]  $request [description]
     * @return boolean          [description]
     */
    private function isNewFormRequestValid($request) {
      $isvalid = true;

      if (!isset($request->name)) { $isvalid = false; }
      if (!isset($request->description)) { $isvalid = false; }
      if (!isset($request->formitems)) { $isvalid = false; }

      if (isset($request->formitems)) {
        if (!is_array($request->formitems)) { $isvalid = false; }
        //check for each of the formitems is valid.
        foreach ($request->formitems as $formitem) {

          if (!isset($formitem['ordering'])) { $isvalid = false; }
          if (!isset($formitem['ordering'])) { $isvalid = false; }
          if (!isset($formitem['configuration'])) { $isvalid = false; }
          if (!isset($formitem['validation'])) { $isvalid = false; }
          if (!isset($formitem['itemtype'])) { $isvalid = false; }
          if (!is_array($formitem['itemtype'])) { $isvalid = false; }
          if (!isset($formitem['itemtype']['id'])) { $isvalid = false; }



        }
      }

      return $isvalid;
    }







    /**
     * Update an existing form definition
     *
     *
     *
     *
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function updateForm(Request $request) {
        Log::debug('Updating form ' . $request->uid . ' for application_id ' . $request->application_id);
        if ($form = Form::find($request->id)) {
          if ($form->application_id == $request->application_id) {

            //Make basic changes
            $form->name = $request->name;
            $form->description = $request->description;
            $form->tag = $request->tag;
            $form->isarchived = $request->isarchived;
            $form->save();


            //Remove the formitems that no longer feature in the form..
            //
            //

            $item_ids_to_keep = array();
            foreach ($request->formitems as $item) {
              if (isset($item['id'])) {
                $item_ids_to_keep[] = $item['id'];
              }
            }

            foreach($form->formitems as $formitem) {
              if (!in_array($formitem->id, $item_ids_to_keep)) {
                Log::debug('Delete formitem: ' . $formitem->id);
                $formitem->delete();
              }
            }

            //loop through form items in the request and update or add new.
            foreach ($request->formitems as $item) {
              if (!isset($item['id'])) {
                //add new formitem
                $formitem = new Formitem;
                $formitem->form_id = $form->id;
                $uuid4 = Uuid::uuid4();
                $formitem->uid = $uuid4->toString();
                $formitem->ordering = $item['ordering'];
                if (!isset($item['name'])) { $item['name'] = ""; }
                $formitem->name = $item['name'];
                $formitem->required = $item['required'];
                $formitem->configuration = json_encode($item['configuration']);
                $formitem->validation = json_encode($item['validation']);
                $formitem->itemtype_id = $item['itemtype']['id'];
                $formitem->save();
                Log::debug('Added new formitem to form ' . $form->id);

              } else {
                //update existing form Item
                if ($formitem = Formitem::find($item['id'])) {
                  Log::debug('Updating formitem ' . $formitem->id);
                  $formitem->ordering = $item['ordering'];
                  $formitem->name = $item['name'];
                  $formitem->required = $item['required'];
                  $formitem->configuration = json_encode($item['configuration']);
                  $formitem->validation = json_encode($item['validation']);
                  $formitem->save();
                }

              }
            }




            //return the form in the correct format
            $result = $form->getByUid($form->uid,$request->application_id);
            return $result;


          } else {
            //form and application id do not match
            return response('Form not found for the application in the request.',401);
          }
        } else {
          //form not found
          return response('Form not found', 400);
        }

    }






    /**
     * Search for forms
     *
     *
     *
     *
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function search(Request $request) {


        $form = new Form;
        return $form->search($request);


    }



}
