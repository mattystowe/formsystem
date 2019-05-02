<?php

namespace App\Http\Middleware;

use Closure;
use App\Application;

class ValidateApiToken
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

      $body = $request->all();

      //find application
      $app = Application::where('api_key',$request->api_token)->get();

      if ($app->isNotEmpty()) {
          if ($app[0]->api_key != $request->api_token) {
            return response('Invalid api token.', 401);
          }
      } else {
        return response('Invalid api token.', 401);
      }



      //pass the application id/ref through the request so its available for controllers
      //
      //$request->attributes->add(['application_id' => $app[0]->id]);
      $request->merge([
            'application_id' => $app[0]->id
        ]);
      return $next($request);
    }
}
