<?php

namespace App\Http\Middleware;

use App\Elibs\Debug;
use App\Elibs\Helper;
use App\Http\Models\Customer;
use App\Http\Models\Member;
use Closure;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class WebAuthBasic
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {

        $valid_passwords = array ("kayn.pro" => "2020@kayn.pro");
        $valid_users = array_keys($valid_passwords);

        $user = @$_SERVER['PHP_AUTH_USER'];
        $pass = @$_SERVER['PHP_AUTH_PW'];

        $validated = (in_array($user, $valid_users)) && ($pass == $valid_passwords[$user]);

        if (!$validated) {
            header('WWW-Authenticate: Basic realm="Sakura and Nobita"');
            header('HTTP/1.0 401 Unauthorized');
            die ("Not authorized");
        }
        return $next($request);
    }

}
