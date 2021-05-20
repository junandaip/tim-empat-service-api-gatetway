<?php

namespace App\Http\Controllers;

use \Illuminate\Http\Request;
use GuzzleHttp\Client;

class PeminjamanController extends Controller {
    private $_client;

    public function __construct()
    {
        $this->_client = new Client([
            #Edit sesuai IP server service Peminjaman
            'base_uri' => 'https://ms-pinjam-service.herokuapp.com/'
        ]);
    }

    public function showPinjaman($username){
        $response = $this->_client->request('GET', 'peminjamans/' . $username);
        $result = json_decode($response->getBody()->getContents(), true);

        if ($response->getStatusCode() == 200) {
            return response()->json([
                'result' => $result,
                'message' => $response->getReasonPhrase(),
                'Code' => $response->getStatusCode()
            ]);
        } else {
            return response()->json([
                'message' => "Data peminjaman tidak ditemukan",
                'Code' => $response->getStatusCode()
            ]);
        }
    }

    public function store(Request $request) {
        $this->validate($request, [
            'username' => 'required',
            'id_buku' => 'required'
        ]);

        $username = $request->input("username");
        $id_buku = $request->input("id_buku");

        $data = [
            "username" => $username,
            "id_buku" => $id_buku
        ];

        $response = $this->_client->request('POST', 'pinjaman', [
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
                'message' => "Peminjaman gagal",
                'Code' => $response->getStatusCode()
            ]);
        }
    }

    public function destroy($id) {
        $response = $this->_client->request('DELETE', 'peminjamans/' . $id, [
            'http_errors' => false,
            'form_params' => [ 
                'id' => $id 
            ]
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
                'message' => "Data gagal dihapus",
                'Code' => $response->getStatusCode()
            ]);
        }
    }
}