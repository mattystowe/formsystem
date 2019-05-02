<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Log;
use DB;

class Form extends Model
{

    protected $hidden = array('application_id', 'updated_at');

    public function application() {
      return $this->belongsTo('App\Application');
    }

    public function formsubmissions() {
      return $this->hasMany('App\Formsubmission');
    }

    public function formitems() {
      return $this->hasMany('\App\Formitem')->orderBy('ordering');
    }


    //======================================================
    //======================================================



    /**
     * Get a form by its uid and application_id
     *
     * @param  [type] $uid            [description]
     * @param  [type] $application_id [description]
     * @return [type]                 [description]
     */
    public function getByUid($uid,$application_id) {
      $result = $this->where('uid',$uid)->where('application_id',$application_id)->with('formitems')->get();
      if ($result->isNotEmpty()) {
        $form = $result[0];
        $form->application;
        //$form->formsubmissions;
        //iterate through form items and populate the data required to work with the form
        foreach($form->formitems as $formitem) {
          $formitem->itemtype;
          $formitem->configuration = json_decode($formitem->configuration);
          $formitem->validation = json_decode($formitem->validation);
          if($formitem->required) { $formitem->required = true; } else { $formitem->required = false; }
          //append the Data object and Files object for the formbuilder bindings
          $formitem->Data = [
            "datavalue"=>null,
            "fileuploads"=>array()
          ];
        }
        return $form;
      } else {
        return false;
      }
    }



    /**
     * Get a results list of forms by ?tag and/or ?name search.
     *
     * @param  [type] $application_id [description]
     * @param  [type] $tag            [description]
     * @param  [type] $name           [description]
     * @return [type]                 [description]
     */
    public function getByTag($application_id, $tag) {
      $result = $this->where('tag',$tag)
                     ->where('application_id', $application_id)
                     ->orderBy('name')
                     ->get();
      return $result;
    }




    /**
     * Search forms based on search terms.
     *
     *
     *
     *
     *
     * @param  [type] $application_id [description]
     * @param  [type] $searchrequest  [description]
     * @return [type]                 [description]
     */
    public function search($request) {

      //Limit for the application_id
      $forms = DB::table('forms');
      $forms->where('application_id', $request->application_id);

      //Tag based searches=========================
      if (isset($request->tags)) {

            $forms->where(function($query) use ($request) {


                  //Multiple tags
                  if (isset($request->tags['has']) && is_array($request->tags['has'])) {
                    $query->whereIn('tag',$request->tags['has']);
                  }

                  //Tags that contain
                  if (isset($request->tags['contains']) && $request->tags['contains'] != '') {
                    $query->orWhere('tag', 'like', '%' . $request->tags['contains'] . '%');
                  }


                  //Multiple Tags that start with
                  if (isset($request->tags['startswith']) && $request->tags['startswith'] != '' && count($request->tags['startswith'])>0) {
                    $query->orWhere(function ($query) use ($request) {
                      foreach ($request->tags['startswith'] as $tag) {
                        $query->where('tag', 'like', $tag . '%');
                      }
                    });
                  }

                  //Multiple Tags that end with
                  if (isset($request->tags['endswith']) && $request->tags['endswith'] != '' && count($request->tags['endswith'])>0) {
                    $query->orWhere(function ($query) use ($request) {
                      foreach ($request->tags['endswith'] as $tag) {
                        $query->where('tag', 'like', '%' . $tag);
                      }
                    });
                  }

            });

      }
      //===========================================


      //FormIds====================================
      if (isset($request->id)) {

          $forms->where(function($query) use ($request) {
            if (is_array($request->id)) {
              $query->whereIn('id',$request->id);
            }

          });

      }
      //=============================================

      //FormUIds====================================
      if (isset($request->uid)) {

          $forms->where(function($query) use ($request) {
            if (is_array($request->uid)) {
              $query->whereIn('uid',$request->uid);
            }

          });

      }
      //=============================================


      //Archived====================================
      if (isset($request->archive)) {
        $forms->where(function($query) use ($request) {

          switch ($request->archive) {
            case 'includeAll':
              $query->where('isarchived',false);
              $query->orWhere('isarchived',true);
              break;
            case 'onlyArchived':
              $query->where('isarchived',true);
              break;

            default: // includeNone
              $query->where('isarchived',false);
              break;
          }


        });
      } else {
        //default - dont include archived forms in results
        $forms->where('isarchived',false);
      }
      //============================================


      //ordering===================================
      if (isset($request->ordering)) {
        $forms->orderBy($request->ordering['by'],$request->ordering['order']);
      }
      //===========================================

      //return result
      $results = $forms->get();
      return $forms->get();


    }





}
