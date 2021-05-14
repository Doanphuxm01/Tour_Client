<?php


namespace App\Http\Middleware;


use Closure;

class UnauthorizedPersonnel
{
    public function handle($request, Closure $next, $guard = null)
    {

        $valid_passwords = array ("minhphuc" => "2020@minhphuc");
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