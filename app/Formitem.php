<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Formitem extends Model
{
  protected $hidden = array(
    'created_at',
    'updated_at',
    'form_id',
    'itemtype_id'
    );

    public function form() {
      return $this->belongsTo('App\Form');
    }

    public function itemtype() {
      return $this->belongsTo('App\Itemtype');
    }
}
