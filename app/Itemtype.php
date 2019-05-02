<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Itemtype extends Model
{
  protected $hidden = array('created_at', 'updated_at','application_id');


  public function getAll($application_id = null) {
    if ($application_id == null) {
      return $this->all();
    } else {
      //limit by application id
      return $this->where('application_id',NULL)->orWhere('application_id',$application_id)->orderBy('name')->get();
    }
  }

}
