<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Itemtype;


class FormItemTypesController extends Controller
{

    /**
     * Get a list of all available form item types
     *
     *
     * @return [type] [description]
     */
    public function getAll(Request $request) {
      $itemtype = new Itemtype;
      $itemtypes = $itemtype->getAll($request->application_id);
      return $itemtypes;
    }
}
