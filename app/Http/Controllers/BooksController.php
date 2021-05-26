<?php

namespace App\Http\Controllers;


use \Illuminate\Http\Request;
use GuzzleHttp\Client;

class BooksController extends Controller
{
    private $_client;

    public function __construct()
    {
        $this->_client = new Client([
            #Edit sesuai IP server service Book
            'base_uri' => 'https://ms-books-service.herokuapp.com/'
        ]);
    }

    public function index()
    {
        $response = $this->_client->request('GET', 'book');
        $books = json_decode($response->getBody()->getContents(), true);
        if ($response->getStatusCode() == 200) {
            return response()->json([
                'result' => $books,
                'message' => $response->getReasonPhrase(),
                'Code' => $response->getStatusCode()
            ]);
        } else {
            return response()->json([
                'message' => "Book not found",
            ]);
        }
    }

    public function getId($id)
    {
        $response = $this->_client->request('GET', 'book/id/' . $id, ['http_errors' => true]);
        $result = json_decode($response->getBody()->getContents(), true);

        if ($response->getStatusCode() == 200) {
            return response()->json([
                'result' => $result,
                'message' => $response->getReasonPhrase(),
                'Code' => $response->getStatusCode()
            ]);
        } else {
            return response()->json([
                'message' => "Book not found",
                'Code' => $response->getStatusCode()
            ]);
        }
    }

    public function getJudul($judul)
    {

        $judul = urldecode($judul);
        $response = $this->_client->request('GET', 'book/judul/' . $judul, ['http_errors' => true]);
        $result = json_decode($response->getBody()->getContents(), true);

        if ($response->getStatusCode() == 200) {
            return response()->json([
                'result' => $result,
                'message' => $response->getReasonPhrase(),
                'Code' => $response->getStatusCode()
            ]);
        } else {
            return response()->json([
                'message' => "Book not found",
                'Code' => $response->getStatusCode()
            ]);
        }
    }

    public function createBuku(Request $request)
    {
        $this->validate($request, [
            'judul' => 'required',
            'penulis' => 'required',
            'kategori' => 'required',
            'stock' => 'required'
        ]);

        $judul = $request->input("judul");
        $penulis = $request->input("penulis");
        $kategori = $request->input("kategori");
        $stock = $request->input("stock");

        $data = [
            "judul" => $judul,
            "penulis" => $penulis,
            "kategori" => $kategori,
            "stock" =>  $stock
        ];

        $response = $this->_client->request('POST', 'book', [
            'http_errors' => true,
            'form_params' => $data
        ]);
        $result = json_decode($response->getBody()->getContents(), true);
        if ($response->getStatusCode() == 201) {
            return response()->json([
                'result' => $result,
                'message' => $response->getReasonPhrase(),
                'Code' => $response->getStatusCode()
            ], 201);
        } else {
            return response()->json([
                'message' => "Error in creating book",
                'Code' => $response->getStatusCode()
            ]);
        }
    }

    public function updateBuku(Request $request, $id)
    {
        $getBuku = $this->_client->request('GET', 'book');
        $dataBuku = json_decode($getBuku->getBody()->getContents(), true);

        $found = in_array($id, array_column($dataBuku, 'id'));
        if ($found) {

            $this->validate($request, [
                'judul' => 'required',
                'penulis' => 'required',
                'kategori' => 'required',
                'stock' => 'required'
            ]);

            $judul = $request->input("judul");
            $penulis = $request->input("penulis");
            $kategori = $request->input("kategori");
            $stock = $request->input("stock");

            $data = [
                "judul" => $judul,
                "penulis" => $penulis,
                "kategori" => $kategori,
                "stock" =>  $stock
            ];

            $response = $this->_client->request('PUT', 'book/' . $id, [
                'form_params' => $data
            ]);

            $result = json_decode($response->getBody()->getContents(), true);

            return response()->json([
                'result' => $result,
                'message' => 'Book has been edited'
            ], 200);
        } else {
            return response()->json([
                'message' => 'Book not found'
            ], 404);
        }
    }

    public function deleteById($id)
    {
        $response = $this->_client->request('DELETE', 'book/' . $id, [
            'http_errors' => true,
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
                'message' => "Error in deleting book",
                'Code' => $response->getStatusCode()
            ]);
        }
    }
}
