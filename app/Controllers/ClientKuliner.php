<?php

namespace App\Controllers;

class ClientKuliner extends BaseController
{
    public function index()
    {
        $client = \Config\Services::curlrequest();

        $response = $client->get(
            base_url('api/kuliner')
        );

        $result = json_decode(
            $response->getBody(),
            true
        );

        return view('client_kuliner', [
            'kuliner' => $result['data']
        ]);
    }
}