<?php

namespace App\Http\Controllers;

use \Illuminate\Http\Request;
use GuzzleHttp\Client;

class AuthController extends Controller
{
    private $_client;

    public function __construct()
    {
        $this->_client = new Client([
            #Edit sesuai IP server service User
            'base_uri' => 'https://mservice-user-service.herokuapp.com/'
        ]);
    }

    public function register(Request $request)
    {

        $this->validate($request, [
            'username' => 'required',
            'password' => 'required',
        ]);

        $username = $request->input("username");
        $password = $request->input("password");

        $data = [
            "username" => $username,
            "password" => $password,
            "role" => 1,
            "kondisi" => 1
        ];

        $response = $this->_client->request('POST', 'register', [
            'http_errors' => false,
            'form_params' => $data
        ]);
        $result = json_decode($response->getBody()->getContents(), true);
        if ($response->getStatusCode() == 201) {
            return response()->json([
                'result' => $result,
                'message' => $response->getReasonPhrase(),
                'Code' => $response->getStatusCode()
            ]);
        } else {
            return response()->json([
                'message' => "Error in registering user",
                'Code' => $response->getStatusCode()
            ]);
        }
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required'
        ]);

        $username = $request->input("username");
        $password = $request->input("password");

        $data = [
            "username" => $username,
            "password" => $password,
        ];

        $response = $this->_client->request('POST', 'login', [
            'http_errors' => false,
            'form_params' => $data
        ]);

        $result = json_decode($response->getBody()->getContents(), true);

        if ($response->getStatusCode() == 200) {
            return response()->json([
                'result' => $result,
                'message' => $response->getReasonPhrase(),
                'Code' => $response->getStatusCode()
            ]);
        } else {
            return response()->json([
                'message' => "Login failed",
                'Code' => $response->getStatusCode()
            ]);
        }
    }

    public function logout(Request $request)
    {
        $this->validate($request, [
            'username' => 'required',
        ]);

        $username = $request->input("username");

        $data = [
            "username" => $username
        ];

        $response = $this->_client->request('POST', 'logout', [
            'form_params' => $data
        ]);

        $result = json_decode($response->getBody()->getContents(), true);

        if ($response->getStatusCode() == 200) {
            return response()->json([
                'result' => $result,
                'message' => $response->getReasonPhrase(),
                'Code' => $response->getStatusCode()
            ]);
        } else {
            return response()->json([
                'message' => "Logout failed",
                'Code' => $response->getStatusCode()
            ]);
        }
    }
}