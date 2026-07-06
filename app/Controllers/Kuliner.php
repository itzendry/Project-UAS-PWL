<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\FavoriteModel;
use App\Models\KulinerPhotoModel;
use App\Models\KulinerModel;
use App\Models\ReviewModel;

class Kuliner extends BaseController
{
    public function index()
    {
        return view('kuliner_view', $this->listData(true));
    }

    public function publicBrowse()
    {
        return view('kuliner_view', $this->listData(false));
    }

    public function create()
    {
        return redirect()->to('/kuliner');
    }

    public function save()
    {
        $rules = [
            'nama_tempat' => 'required|min_length[3]',
            'category_id' => 'required|is_natural_no_zero',
            'alamat' => 'required|min_length[5]',
            'review' => 'permit_empty|max_length[1000]',
            'rating' => 'permit_empty|integer|greater_than_equal_to[1]|less_than_equal_to[5]',
            'fotos.0' => 'permit_empty|max_size[fotos.0,2048]|is_image[fotos.0]|mime_in[fotos.0,image/jpg,image/jpeg,image/png]',
            'fotos.1' => 'permit_empty|max_size[fotos.1,2048]|is_image[fotos.1]|mime_in[fotos.1,image/jpg,image/jpeg,image/png]',
            'fotos.2' => 'permit_empty|max_size[fotos.2,2048]|is_image[fotos.2]|mime_in[fotos.2,image/jpg,image/jpeg,image/png]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal. Pastikan data wajib terisi dan foto maksimal 2MB.');
        }

        $alamat = $this->request->getPost('alamat');
        $lat = $this->request->getPost('latitude');
        $lng = $this->request->getPost('longitude');

        if (! $lat || ! $lng) {
            [$lat, $lng] = $this->geocodeAddress($alamat);
        }

        $photos = $this->storePhotos('fotos', 3);
        $foto = $photos[0] ?? 'default.png';
        $isAdmin = session()->get('role') === 'admin';

        $kulinerModel = new KulinerModel();
        $kulinerModel->insert([
            'user_id' => session()->get('user_id'),
            'category_id' => $this->request->getPost('category_id'),
            'nama_tempat' => $this->request->getPost('nama_tempat'),
            'alamat' => $alamat,
            'deskripsi' => $this->request->getPost('deskripsi'),
            'review' => $this->request->getPost('review'),
            'rating' => (int) ($this->request->getPost('rating') ?: 0),
            'status' => $isAdmin ? 'approved' : 'pending',
            'foto' => $foto,
            'latitude' => $lat,
            'longitude' => $lng,
        ]);

        $kulinerId = $kulinerModel->getInsertID();
        $this->savePhotoRows($kulinerId, $photos);

        if ($this->request->getPost('review')) {
            (new ReviewModel())->insert([
                'user_id' => session()->get('user_id'),
                'kuliner_id' => $kulinerId,
                'review' => $this->request->getPost('review'),
                'rating' => (int) ($this->request->getPost('rating') ?: 5),
            ]);
            $this->refreshRating($kulinerId);
        }

        $message = $isAdmin
            ? 'Data kuliner berhasil ditambahkan.'
            : 'Data kuliner berhasil dikirim dan menunggu persetujuan admin.';

        return redirect()->to('/kuliner')->with('success', $message);
    }

    public function edit($id)
    {
        $kuliner = (new KulinerModel())->find($id);

        if (! $kuliner || ! $this->canManageKuliner($kuliner)) {
            return redirect()->to('/kuliner')->with('error', 'Akses ditolak. Anda tidak berhak mengedit data ini.');
        }

        return view('edit_view', [
            'kuliner' => $kuliner,
            'categories' => (new CategoryModel())->findAll(),
        ]);
    }

    public function update($id)
    {
        $model = new KulinerModel();
        $kuliner = $model->find($id);

        if (! $kuliner || ! $this->canManageKuliner($kuliner)) {
            return redirect()->to('/kuliner')->with('error', 'Akses ditolak. Anda tidak bisa menyimpan perubahan ini.');
        }

        $rules = [
            'nama_tempat' => 'required|min_length[3]',
            'category_id' => 'required|is_natural_no_zero',
            'alamat' => 'required|min_length[5]',
            'foto' => 'permit_empty|max_size[foto,2048]|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal. Cek kembali input dan format foto.');
        }

        $foto = $this->storePhoto('foto', $kuliner['foto'] ?? 'default.png');

        $model->update($id, [
            'category_id' => $this->request->getPost('category_id'),
            'nama_tempat' => $this->request->getPost('nama_tempat'),
            'alamat' => $this->request->getPost('alamat'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'foto' => $foto,
            'latitude' => $this->request->getPost('latitude') ?: $kuliner['latitude'],
            'longitude' => $this->request->getPost('longitude') ?: $kuliner['longitude'],
        ]);

        return redirect()->to('/kuliner')->with('success', 'Data berhasil diupdate.');
    }

    public function detail($id)
    {
        return $this->showDetail($id, true);
    }

    public function publicDetail($id)
    {
        return $this->showDetail($id, false);
    }

    public function delete($id)
    {
        $model = new KulinerModel();
        $kuliner = $model->find($id);

        if (! $kuliner) {
            return redirect()->to('/kuliner')->with('error', 'Data tidak ditemukan.');
        }

        if (! $this->canManageKuliner($kuliner)) {
            return redirect()->to('/kuliner')->with('error', 'Akses ditolak.');
        }

        $model->delete($id);

        return redirect()->to('/kuliner')->with('success', 'Data berhasil dihapus.');
    }

    public function favorite($id)
    {
        $favModel = new FavoriteModel();
        $kuliner = (new KulinerModel())->find($id);
        $userId = session()->get('user_id');

        if (! $kuliner) {
            return redirect()->to('/kuliner')->with('error', 'Data tidak ditemukan.');
        }

        $favorite = $favModel->where('user_id', $userId)->where('kuliner_id', $id)->first();

        if ($favorite) {
            $favModel->delete($favorite['id']);
            return redirect()->back()->with('success', 'Berhasil dihapus dari favorit.');
        }

        $favModel->insert(['user_id' => $userId, 'kuliner_id' => $id]);

        return redirect()->back()->with('success', 'Berhasil ditambahkan ke favorit.');
    }

    public function myFavorites()
    {
        $userId = session()->get('user_id');

        return view('favorit_view', [
            'kuliner' => (new KulinerModel())
                ->select('kuliner.*, categories.nama_kategori')
                ->join('categories', 'categories.id = kuliner.category_id', 'left')
                ->join('favorites', 'favorites.kuliner_id = kuliner.id')
                ->where('favorites.user_id', $userId)
                ->where('kuliner.status', 'approved')
                ->findAll(),
        ]);
    }

    public function addReview($kulinerId)
    {
        if (! $this->validate([
            'review' => 'required|min_length[3]|max_length[1000]',
            'rating' => 'required|integer|greater_than_equal_to[1]|less_than_equal_to[5]',
        ])) {
            return redirect()->back()->withInput()->with('error', 'Review dan rating wajib diisi.');
        }

        (new ReviewModel())->insert([
            'user_id' => session()->get('user_id'),
            'kuliner_id' => $kulinerId,
            'review' => $this->request->getPost('review'),
            'rating' => (int) $this->request->getPost('rating'),
        ]);

        $this->refreshRating($kulinerId);

        return redirect()->back()->with('success', 'Review berhasil ditambahkan.');
    }

    public function updateReview($reviewId)
    {
        $reviewModel = new ReviewModel();
        $review = $reviewModel->find($reviewId);

        if (! $review || (int) $review['user_id'] !== (int) session()->get('user_id')) {
            return redirect()->back()->with('error', 'Akses edit review ditolak.');
        }

        if (strtotime($review['created_at']) < strtotime('-24 hours')) {
            return redirect()->back()->with('error', 'Review hanya dapat diedit dalam 24 jam setelah dibuat.');
        }

        if (! $this->validate([
            'review' => 'required|min_length[3]|max_length[1000]',
            'rating' => 'required|integer|greater_than_equal_to[1]|less_than_equal_to[5]',
        ])) {
            return redirect()->back()->withInput()->with('error', 'Review dan rating wajib diisi.');
        }

        $reviewModel->update($reviewId, [
            'review' => $this->request->getPost('review'),
            'rating' => (int) $this->request->getPost('rating'),
        ]);

        $this->refreshRating($review['kuliner_id']);

        return redirect()->back()->with('success', 'Review berhasil diperbarui.');
    }

    public function markClosed($id)
    {
        (new KulinerModel())->update($id, ['status' => 'pending']);

        return redirect()->back()->with('success', 'Laporan tutup permanen dikirim untuk divalidasi admin.');
    }

    public function getCoordinate()
    {
        $alamat = $this->request->getPost('alamat');

        if (! $alamat) {
            return $this->response->setJSON(['status' => false, 'message' => 'Alamat kosong.']);
        }

        [$lat, $lng] = $this->geocodeAddress($alamat);

        if ($lat && $lng) {
            return $this->response->setJSON(['status' => true, 'lat' => $lat, 'lng' => $lng]);
        }

        return $this->response->setJSON(['status' => false, 'message' => 'Koordinat tidak ditemukan.']);
    }

    private function listData(bool $requireLogin): array
    {
        $model = new KulinerModel();
        $builder = $model
            ->select('kuliner.*, categories.nama_kategori')
            ->join('categories', 'categories.id = kuliner.category_id', 'left');

        if (session()->get('role') !== 'admin') {
            $builder->where('kuliner.status', 'approved');
        }

        if ($search = $this->request->getGet('q')) {
            $builder->groupStart()
                ->like('kuliner.nama_tempat', $search)
                ->orLike('kuliner.alamat', $search)
                ->groupEnd();
        }

        if ($categoryId = $this->request->getGet('category_id')) {
            $builder->where('kuliner.category_id', $categoryId);
        }

        if ($rating = $this->request->getGet('rating')) {
            $builder->where('kuliner.rating >=', (int) $rating);
        }

        return [
            'kuliner' => $builder->orderBy('kuliner.created_at', 'DESC')->paginate(9),
            'pager' => $model->pager,
            'categories' => (new CategoryModel())->orderBy('nama_kategori', 'ASC')->findAll(),
            'requireLogin' => $requireLogin,
        ];
    }

    private function showDetail($id, bool $requireLogin)
    {
        $kuliner = (new KulinerModel())
            ->select('kuliner.*, categories.nama_kategori')
            ->join('categories', 'categories.id = kuliner.category_id', 'left')
            ->find($id);

        if (! $kuliner || ($kuliner['status'] !== 'approved' && session()->get('role') !== 'admin')) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Tempat kuliner tidak ditemukan.');
        }

        $reviews = (new ReviewModel())
            ->select('reviews.*, users.nama AS nama_user')
            ->join('users', 'users.id = reviews.user_id', 'left')
            ->where('reviews.kuliner_id', $id)
            ->orderBy('reviews.created_at', 'DESC')
            ->findAll();

        $photos = [];
        if (\Config\Database::connect()->tableExists('kuliner_photos')) {
            $photos = (new KulinerPhotoModel())->where('kuliner_id', $id)->findAll();
        }

        return view('kuliner_detail', [
            'k' => $kuliner,
            'reviews' => $reviews,
            'photos' => $photos,
            'requireLogin' => $requireLogin,
        ]);
    }

    private function geocodeAddress(string $alamat): array
    {
        try {
            $response = \Config\Services::curlrequest()->get('https://nominatim.openstreetmap.org/search', [
                'query' => ['q' => $alamat, 'format' => 'json', 'limit' => 1],
                'headers' => ['User-Agent' => 'KulinerApp-CI4/1.0'],
                'timeout' => 8,
            ]);

            $geo = json_decode($response->getBody(), true);
            if (! empty($geo[0]['lat']) && ! empty($geo[0]['lon'])) {
                return [$geo[0]['lat'], $geo[0]['lon']];
            }
        } catch (\Throwable $e) {
            log_message('error', 'Nominatim geocoding gagal: ' . $e->getMessage());
        }

        return [null, null];
    }

    private function storePhoto(string $field, string $fallback = 'default.png'): string
    {
        $file = $this->request->getFile($field);
        if (! $file || $file->getError() === UPLOAD_ERR_NO_FILE) {
            return $fallback;
        }

        $name = $file->getRandomName();
        $path = FCPATH . 'uploads/kuliner';
        $thumbPath = $path . '/thumbs';

        if (! is_dir($path)) {
            mkdir($path, 0775, true);
        }
        if (! is_dir($thumbPath)) {
            mkdir($thumbPath, 0775, true);
        }

        $file->move($path, $name);

        $image = \Config\Services::image();
        $image->withFile($path . '/' . $name)
            ->fit(800, 800, 'center')
            ->save($path . '/' . $name, 85);

        $image->withFile($path . '/' . $name)
            ->fit(300, 220, 'center')
            ->save($thumbPath . '/' . $name, 80);

        return $name;
    }

    private function storePhotos(string $field, int $limit): array
    {
        $files = $this->request->getFiles()[$field] ?? [];
        if (! is_array($files)) {
            return [];
        }

        $stored = [];
        foreach (array_slice($files, 0, $limit) as $index => $file) {
            if (! $file || $file->getError() === UPLOAD_ERR_NO_FILE) {
                continue;
            }

            $tmpField = $field . '.' . $index;
            // Reuse the same storage logic after validation has accepted the file.
            $name = $file->getRandomName();
            $path = FCPATH . 'uploads/kuliner';
            $thumbPath = $path . '/thumbs';

            if (! is_dir($path)) {
                mkdir($path, 0775, true);
            }
            if (! is_dir($thumbPath)) {
                mkdir($thumbPath, 0775, true);
            }

            $file->move($path, $name);
            $image = \Config\Services::image();
            $image->withFile($path . '/' . $name)->fit(800, 800, 'center')->save($path . '/' . $name, 85);
            $image->withFile($path . '/' . $name)->fit(300, 220, 'center')->save($thumbPath . '/' . $name, 80);

            $stored[] = $name;
        }

        return $stored;
    }

    private function savePhotoRows($kulinerId, array $photos): void
    {
        if ($photos === [] || ! \Config\Database::connect()->tableExists('kuliner_photos')) {
            return;
        }

        $photoModel = new KulinerPhotoModel();
        foreach ($photos as $photo) {
            $photoModel->insert([
                'kuliner_id' => $kulinerId,
                'foto' => $photo,
            ]);
        }
    }

    private function refreshRating($kulinerId): void
    {
        $row = (new ReviewModel())
            ->selectAvg('rating', 'avg_rating')
            ->where('kuliner_id', $kulinerId)
            ->first();

        (new KulinerModel())->update($kulinerId, [
            'rating' => $row && $row['avg_rating'] !== null ? round((float) $row['avg_rating']) : 0,
        ]);
    }

    private function canManageKuliner(array $kuliner): bool
    {
        return session()->get('role') === 'admin'
            || (int) $kuliner['user_id'] === (int) session()->get('user_id');
    }
}
