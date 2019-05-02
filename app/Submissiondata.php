<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Submissiondata extends Model
{


    public function formsubmission() {
      return $this->belongsTo('App\Formsubmission');
    }

    public function fileuploads() {
      return $this->hasMany('App\Fileupload');
    }

    public function formitem() {
      return $this->belongsTo('App\Formitem');
    }

}
