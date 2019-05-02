<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Form;
use App\Formsubmission;
use DB;
use Log;

class Formsubmission extends Model
{
  protected $hidden = array('updated_at');


    public function submissiondata() {
      return $this->hasMany('App\Submissiondata');
    }

    public function form() {
      return $this->belongsTo('App\Form');
    }

    //===================================================
    //===================================================


    /**
     * Get a list of submissions by tag and form uid
     *
     * @param  [type] $application_id [description]
     * @param  [type] $submissiontag            [description]
     * @param  [type] $formuid        [description]
     * @return [type]                 [description]
     */
    public function getByTagAndForm($application_id, $submissiontag, $formuid) {

      $submissions = DB::table('formsubmissions')
                        ->leftjoin('forms','formsubmissions.form_id','forms.id')
                        ->select('formsubmissions.*')
                        ->where('forms.application_id',$application_id)
                        ->where('forms.uid',$formuid)
                        ->where('formsubmissions.tag',$submissiontag)
                        ->get();
      return $submissions;
    }


    /**
     * Get a list of all form submissions available with a certain tag for the application
     *
     *
     * @param  [type] $submissiontag  [description]
     * @param  [type] $application_id [description]
     * @return [type]                 [description]
     */
    public function getByTag($application_id, $submissiontag) {
      $submissions = DB::table('formsubmissions')
                        ->leftjoin('forms','formsubmissions.form_id','forms.id')
                        ->select('formsubmissions.*')
                        ->where('forms.application_id',$application_id)
                        ->where('formsubmissions.tag',$submissiontag)
                        ->get();
      //append the forms for each result
      foreach($submissions as $submission) {
        $submission->Form = Form::find($submission->form_id);
      }
      return $submissions;
    }



    /**
     * Get a list of all submissions for specific form
     *
     * @param  [type] $application_id [description]
     * @param  [type] $formuid        [description]
     * @return [type]                 [description]
     */
    public function getByForm($application_id, $formuid) {

      $submissions = DB::table('formsubmissions')
                        ->leftjoin('forms','formsubmissions.form_id','forms.id')
                        ->select('formsubmissions.*')
                        ->where('forms.application_id',$application_id)
                        ->where('forms.uid',$formuid)
                        ->get();
      return $submissions;
    }





    /**
     * Get a single form submission by its uid and return with all supporting information needed for render
     *
     *
     *
     *
     * @param  [type] $application_id [description]
     * @param  [type] $submissionuid  [description]
     * @return [type]                 [description]
     */
    public function getByUid($application_id, $submissionuid) {
          $result = DB::table('formsubmissions')
                              ->leftjoin('forms','formsubmissions.form_id','forms.id')
                              ->select('formsubmissions.*','forms.uid as form_uid')
                              ->where('forms.application_id',$application_id)
                              ->where('formsubmissions.uid',$submissionuid)
                              ->get();
            if ($result->isNotEmpty()) {

              if ($submission = Formsubmission::find($result[0]->id)) {

                //Build up the elements required for the submission to be displayed
                $submission->form;
                $submission->submissiondata;



                  //loadform
                  $formclass = new Form;
                  if ($form = $formclass->getByUid($result[0]->form_uid,$application_id)) {

                      //loop round formitems and append the submission value for each
                      foreach($form->formitems as $formitem) {
                        //match the submissiondata with the input id so we can add the Data to the item
                        foreach($submission->submissiondata as $data) {
                          $data->fileuploads;
                          if ($data->formitem_id == $formitem->id) {
                            $formitem->Data = $data;
                          }
                        }


                      }


                      //Append the formsubmission object onto the form
                      $form->Formsubmission = $result[0];


                      return $form;

                  } else {
                    return false;
                  }



              } else {
                return false;
              }


            } else {
              return false;
            }

  }






  /**
   * Search submissions
   *
   *
   *
   * @param  [type] $request [description]
   * @return [type]          [description]
   */
  public function search($request) {

    $submissions = DB::table('formsubmissions');
    $submissions->leftjoin('forms','formsubmissions.form_id','forms.id');
    $submissions->select('formsubmissions.*');
    $submissions->where('forms.application_id',$request->application_id);


    //Submission===============================
    if (isset($request->submissions)) {
      $submissions->where(function ($query) use ($request) {


              //tags start
              if (isset($request->submissions['tags'])) {

                $query->where(function ($query) use ($request) {


                  //Multiple Tags
                  if (isset($request->submissions['tags']['has']) && is_array($request->submissions['tags']['has']) && count($request->submissions['tags']['has'])>0 ) {
                    $query->whereIn('formsubmissions.tag',$request->submissions['tags']['has']);
                  }

                  //Tags that contain
                  if (isset($request->submissions['tags']['contains']) && $request->submissions['tags']['contains'] != '') {
                    $query->orWhere('formsubmissions.tag', 'like', '%' . $request->submissions['tags']['contains'] . '%');
                  }

                  //Multiple Tags that start with
                  if (isset($request->submissions['tags']['startswith']) && $request->submissions['tags']['startswith'] != '' && count($request->submissions['tags']['startswith'])>0) {
                    $query->orWhere(function ($query) use ($request) {
                              foreach($request->submissions['tags']['startswith'] as $tag) {
                              $query->orWhere('formsubmissions.tag', 'like', $tag . '%');
                            }

                    });
                  }

                  //Multiple Tags that end with
                  if (isset($request->submissions['tags']['endswith']) && $request->submissions['tags']['endswith'] != '' && count($request->submissions['tags']['endswith'])>0) {
                    $query->orWhere(function ($query) use ($request) {
                              foreach($request->submissions['tags']['endswith'] as $tag) {
                              $query->orWhere('formsubmissions.tag', 'like', '%' . $tag);
                            }

                    });
                  }

                });

              } // tags end


              //
              //
              //
              //Add other formsubmissions filters here if required to add to the query
              //
              //


      });
    }
    //Submission filters end====================








    //Form filters start==========================
    if (isset($request->forms)) {
      $submissions->where(function ($query) use ($request) {


        //Form id
        if (isset($request->forms['id']) && $request->forms['id'] != '') {
          $query->where('forms.id',$request->forms['id']);
        }

        //Form uid
        if (isset($request->forms['uid']) && $request->forms['uid'] != '') {
          $query->where('forms.uid',$request->forms['uid']);
        }


        if (isset($request->forms['tags'])) { // for tags start
            $query->where(function ($query) use ($request) {

                  //Multiple Tags
                  if (isset($request->forms['tags']['has']) && is_array($request->forms['tags']['has'])) {
                    if (count($request->forms['tags']['has'])>0) {
                      $query->whereIn('forms.tag',$request->forms['tags']['has']);
                    }
                  }


                  //tags that contains
                  if (isset($request->forms['tags']['contains']) && $request->forms['tags']['contains'] != '') {
                      $query->orWhere('forms.tag', 'like', '%' . $request->forms['tags']['contains'] . '%');
                  }


                  //Tags Multiple Startswith
                  if (isset($request->forms['tags']['startswith']) && is_array($request->forms['tags']['startswith'])) {
                    if (count($request->forms['tags']['startswith'])>0) {
                        $query->orWhere(function ($query) use ($request) {
                                  foreach($request->forms['tags']['startswith'] as $tag) {
                                  $query->orWhere('forms.tag', 'like', $tag . '%');
                                }

                        });
                    }
                  }

                  //tags Multiple Endswith
                  if (isset($request->forms['tags']['endswith']) && is_array($request->forms['tags']['endswith'])) {
                    if (count($request->forms['tags']['endswith'])>0) {
                        $query->orWhere(function ($query) use ($request) {
                                  foreach($request->forms['tags']['endswith'] as $tag) {
                                  $query->orWhere('forms.tag', 'like', '%' . $tag);
                                }

                        });
                    }
                  }


          });
        } // form tags end


        //add more form filters here if required to build up the query within the forms group



      });
    }
    //form filters end=========================



    //ordering===================================
    if (isset($request->ordering)) {
      $submissions->orderBy($request->ordering['by'],$request->ordering['order']);
    }
    //===========================================

    $results = $submissions->get();

    //append the form result for each submission
    foreach ($results as $submission) {
      $submission->Form = Form::find($submission->form_id);
    }
    
    return $results;
  }



}
