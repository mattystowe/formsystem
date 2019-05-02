<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $hidden = array('created_at', 'updated_at','api_key');

    public function forms() {
      return $this->hasMany('App\Form');
    }
}
