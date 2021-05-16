<?php

namespace App\Http\Controllers;

use \Illuminate\Http\Request;
use GuzzleHttp\Client;

class UserController extends Controller
{

    private $_client;

    public function __construct()
    {
        $this->_client = new Client([
            #Edit sesuai IP server service User
            'base_uri' => 'http://localhost:8000/'
        ]);
    }

    public function getUsername($username)
    {
        $username = urldecode($username);

        $response = $this->_client->request('GET', 'user/' . $username, ['http_errors' => false]);

        $result = json_decode($response->getBody()->getContents(), true);
        if ($response->getStatusCode() == 200) {
            return response()->json([
                'result' => $result,
                'message' => $response->getReasonPhrase(),
                'Code' => $response->getStatusCode()
            ]);
        } else {
            return response()->json([
                'message' => "User not found",
                'Code' => $response->getStatusCode()
            ]);
        }
    }

    public function editUser(Request $request, $username)
    {
        $username = urldecode($username);

        $getUsers = $this->_client->request('GET', 'users/');
        $dataUser = json_decode($getUsers->getBody()->getContents(), true);

        $found = in_array($username, array_column($dataUser, 'username'));
        if ($found) {

            $this->validate($request, [
                'kondisi' => 'required',
            ]);

            $kondisi = $request->input("kondisi");

            $data = [
                "kondisi" => $kondisi
            ];

            $response = $this->_client->request('PUT', 'user/' . $username, [
                'form_params' => $data
            ]);

            $result = json_decode($response->getBody()->getContents(), true);

            return response()->json([
                'result' => $result,
                'message' => 'User has been edited'
            ], 200);
        } else {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }
    }
}
