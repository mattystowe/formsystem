<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Application;
class IndexController extends Controller
{
    public function index(Request $request) {
      $application = Application::find($request->application_id);
      return $application;
    }
}
