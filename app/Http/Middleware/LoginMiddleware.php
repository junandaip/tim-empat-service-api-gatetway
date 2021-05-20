<?php

namespace App\Http\Middleware;

use GuzzleHttp\Client;

use Closure;

class LoginMiddleware
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
        $_client = new Client([
            #Edit sesuai IP server service User
            'base_uri' => 'https://mservice-user-service.herokuapp.com'
        ]);
        $getUsers = $_client->request('GET', 'users/');
        $dataUser = json_decode($getUsers->getBody()->getContents(), true);
        $token = $request->input('token');

        $found = in_array($token, array_column($dataUser, 'token'));
        if ($token) {
            $check = $found;
            if (!$check) {
                $out = [
                    "message" => "Token Tidak Valid",
                    "code" => 401
                ];
                return response()->json($out, $out['code']);
            } else {
                return $next($request);
            }
        } else {
            $out = [
                "message" => "Masukkan Token",
                "code" => 401
            ];
            return response()->json($out, $out['code']);
        }
    }
}
