<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fileupload extends Model
{
    public function submissiondata() {
      return $this->belongsTo('App\Submissiondata');
    }
}
