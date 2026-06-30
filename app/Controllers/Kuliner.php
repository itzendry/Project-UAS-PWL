<?php

namespace App\Controllers;

use App\Models\KulinerModel;
use App\Models\CategoryModel;

class Kuliner extends BaseController
{
    public function index()
    {
        $model = new KulinerModel();
        $categoryModel = new CategoryModel();

        $data['kuliner'] = $model
            ->select('kuliner.*, categories.nama_kategori')
            ->join('categories', 'categories.id = kuliner.category_id')
            ->findAll();

        $data['categories'] = $categoryModel->findAll();

        return view('kuliner_view', $data);
    }

    public function create()
    {
        $categoryModel = new CategoryModel();

        $data['categories'] = $categoryModel->findAll();

        return view('create_kuliner', $data);
    }

    public function save()
    {
        // 1. ATURAN VALIDASI
        $rules = [
            'nama_tempat' => 'required',
            'category_id' => 'required',
            'alamat'      => 'required',
            'foto'        => 'max_size[foto,2048]|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png]'
        ];

        // JIKA VALIDASI GAGAL
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal! Pastikan form terisi semua dan file foto maksimal 2MB (JPG/PNG).');
        }

        // 2. PROSES UPLOAD FOTO
        $fileFoto = $this->request->getFile('foto');
        
        // Cek apakah ada file yang diupload 
        if ($fileFoto->getError() == 4) {
            $namaFoto = 'default.png';
        } else {
            // Generate nama acak agar file tidak saling tertimpa
            $namaFoto = $fileFoto->getRandomName();
            // Pindahkan file ke folder public/uploads/kuliner
            $fileFoto->move('uploads/kuliner', $namaFoto);
        }

        // 3. SIMPAN KE DATABASE
        $model = new KulinerModel();
        $alamat = $this->request->getVar('alamat');

$client = \Config\Services::curlrequest();

$response = $client->get(
    'https://nominatim.openstreetmap.org/search',
    [
        'query' => [
            'q' => $alamat,
            'format' => 'json',
            'limit' => 1
        ],
        'headers' => [
            'User-Agent' => 'KulinerApp'
        ]
    ]
);

$geo = json_decode(
    $response->getBody(),
    true
);

$lat = null;
$lng = null;

if (!empty($geo)) {
    $lat = $geo[0]['lat'];
    $lng = $geo[0]['lon'];
}

$model->save([
    'user_id' => session()->get('user_id'),
    'category_id' => $this->request->getVar('category_id'),
    'nama_tempat' => $this->request->getVar('nama_tempat'),
    'alamat' => $alamat,
    'deskripsi' => $this->request->getVar('deskripsi'),
    'review' => $this->request->getVar('review'),
    'rating' => $this->request->getVar('rating'),
    'status' => 'approved',
    'foto' => $namaFoto,
    'latitude'  => $this->request->getPost('latitude'),
'longitude' => $this->request->getPost('longitude'),
]);

        return redirect()->to('/kuliner')->with('success', 'Data kuliner dan foto berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $model = new KulinerModel();
        $categoryModel = new CategoryModel();

        $kuliner = $model->find($id);

        // Jika data tidak ditemukan, atau jika yang login BUKAN pemilik data DAN BUKAN admin
        if (!$kuliner || ($kuliner['user_id'] != session()->get('user_id') && session()->get('role') != 'admin')) {
            return redirect()->to('/kuliner')->with('error', 'Akses ditolak! Anda tidak berhak mengedit data ini.');
        }

        $data['kuliner'] = $kuliner;
        $data['categories'] = $categoryModel->findAll();

        return view('edit_view', $data);
    }

    public function update($id)
    {
        $model = new KulinerModel();
        $kulinerLama = $model->find($id);

        // --- 🔒 GEMBOK KEAMANAN START ---
        if (!$kulinerLama || ($kulinerLama['user_id'] != session()->get('user_id') && session()->get('role') != 'admin')) {
            return redirect()->to('/kuliner')->with('error', 'Akses ditolak! Anda tidak bisa menyimpan perubahan untuk data ini.');
        }
        // --- 🔒 GEMBOK KEAMANAN END ---

        // 1. ATURAN VALIDASI
        $rules = [
            'nama_tempat' => 'required',
            'alamat'      => 'required',
            'foto'        => 'max_size[foto,2048]|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal! Cek kembali inputan dan format foto.');
        }

        // 2. PROSES UPLOAD FOTO BARU (Jika ada)
        $fileFoto = $this->request->getFile('foto');
        
        if ($fileFoto->getError() == 4) {
            $namaFoto = $kulinerLama['foto'] ?? 'default.png';
        } else {
            $namaFoto = $fileFoto->getRandomName();
            $fileFoto->move('uploads/kuliner', $namaFoto);
        }

        // 3. UPDATE KE DATABASE
        $model->update($id, [
            'category_id' => $this->request->getPost('category_id'),
            'nama_tempat' => $this->request->getPost('nama_tempat'),
            'alamat'      => $this->request->getPost('alamat'),
            'deskripsi'   => $this->request->getPost('deskripsi'),
            'review'      => $this->request->getPost('review'),
            'rating'      => $this->request->getPost('rating'),
            'foto'        => $namaFoto 
        ]);

        return redirect()->to('/kuliner')->with('success', 'Data berhasil diupdate!');
    }

    public function favorite($id)
    {
        $favModel = new \App\Models\FavoriteModel();
        $model = new KulinerModel();
        
        $userId = session()->get('user_id');

        //apakah data kulinernya beneran ada
        $kuliner = $model->find($id);
        if (!$kuliner) {
            return redirect()->to('/kuliner')->with('error', 'Data tidak ditemukan.');
        }

        //apakah usersudah pernah mem-favoritkan tempat ini
        $cekFav = $favModel->where('user_id', $userId)
                           ->where('kuliner_id', $id)
                           ->first();

        if ($cekFav) {
            // Jika SUDAH ADA, maka HAPUS dari favorit 
            $favModel->delete($cekFav['id']);
            return redirect()->back()->with('success', 'Berhasil dihapus dari daftar Favorit.');
        } else {
            // Jika BELUM ADA, maka TAMBAHKAN ke favorit
            $favModel->insert([
                'user_id'    => $userId,
                'kuliner_id' => $id
            ]);
            return redirect()->back()->with('success', 'Berhasil ditambahkan ke Favorit! ❤️');
        }
    }

    public function detail($id)
    {
        $kulinerModel = new \App\Models\KulinerModel();

        // Mengambil data kuliner spesifik berdasarkan ID beserta nama kategorinya
        $kuliner = $kulinerModel
            ->select('kuliner.*, categories.nama_kategori')
            ->join('categories', 'categories.id = kuliner.category_id', 'left')
            ->find($id);

        // Jika data tidak ditemukan di database, lemparkan error 404 resmi
        if (!$kuliner) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Tempat kuliner tidak ditemukan.");
        }

        $data['k'] = $kuliner;

        // Memanggil file view kuliner_detail.php
        return view('kuliner_detail', $data);
    }

    public function delete($id)
    {
        $model = new KulinerModel();

        $kuliner = $model->find($id);

        if (!$kuliner) {

            return redirect()->to('/kuliner')
                ->with('error', 'Data tidak ditemukan');
        }

        // keamanan
        if (
            $kuliner['user_id'] != session()->get('user_id')
            && session()->get('role') != 'admin'
        ) {

            return redirect()->to('/kuliner')
                ->with('error', 'Akses ditolak');
        }

        $model->delete($id);

        return redirect()->to('/kuliner')
            ->with('success', 'Data berhasil dihapus');
    }

    public function myFavorites()
    {
        $model = new KulinerModel();
        $userId = session()->get('user_id');

        // Melakukan JOIN tabel kuliner, categories, dan favorites
        $data['kuliner'] = $model
            ->select('kuliner.*, categories.nama_kategori')
            ->join('categories', 'categories.id = kuliner.category_id', 'left')
            ->join('favorites', 'favorites.kuliner_id = kuliner.id')
            ->where('favorites.user_id', $userId)
            ->findAll();

        return view('favorit_view', $data);
    }
    public function getCoordinate()
{
    $alamat = $this->request->getPost('alamat');

    if (!$alamat) {
        return $this->response->setJSON([
            'status' => false,
            'message' => 'Alamat kosong'
        ]);
    }

    $url = "https://nominatim.openstreetmap.org/search?format=json&q=" . urlencode($alamat);

    $client = \Config\Services::curlrequest();

    $response = $client->request('GET', $url, [
        'headers' => [
            'User-Agent' => 'CodeIgniter4 App'
        ]
    ]);

    $data = json_decode($response->getBody(), true);

    if (!empty($data)) {
        return $this->response->setJSON([
            'status' => true,
            'lat' => $data[0]['lat'],
            'lng' => $data[0]['lon']
        ]);
    }

    return $this->response->setJSON([
        'status' => false,
        'message' => 'Koordinat tidak ditemukan'
    ]);
}
}
