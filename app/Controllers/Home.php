<?php

namespace App\Controllers;
use App\Models\KulinerModel;

class Home extends BaseController
{
    public function index()
    {
        $kulinerModel = new KulinerModel();

        $data['kuliner'] = $kulinerModel
            ->select('kuliner.*, categories.nama_kategori')
            ->join('categories', 'categories.id = kuliner.category_id', 'left')
            ->where('status', 'approved')
            ->orderBy('id', 'DESC') 
            ->limit(6)
            ->findAll();


        return view('home', $data); 
    }
}
