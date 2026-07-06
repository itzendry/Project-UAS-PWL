<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\KulinerModel;

class KulinerApi extends BaseController
{
    public function index()
    {
        $lat = $this->request->getGet('lat');
        $lng = $this->request->getGet('lng');
        $radius = $this->request->getGet('radius');

        $model = new KulinerModel();

        if (!$lat || !$lng || !$radius) {
            $data = $model
                ->select('kuliner.*, categories.nama_kategori')
                ->join('categories', 'categories.id = kuliner.category_id', 'left')
                ->where('kuliner.status', 'approved')
                ->findAll();

            return $this->response->setJSON([
                'status' => true,
                'mode'   => 'all',
                'total'  => count($data),
                'data'   => $data
            ]);
        }

        $db = \Config\Database::connect();

        $sql = "
        SELECT kuliner.*, categories.nama_kategori,
        (
            6371 * acos(
                cos(radians(?))
                * cos(radians(latitude))
                * cos(radians(longitude) - radians(?))
                + sin(radians(?))
                * sin(radians(latitude))
            )
        ) AS distance
        FROM kuliner
        LEFT JOIN categories ON categories.id = kuliner.category_id
        WHERE kuliner.status = 'approved'
        AND latitude IS NOT NULL
        AND longitude IS NOT NULL
        HAVING distance <= ?
        ORDER BY distance ASC
        ";

        $result = $db->query($sql, [
            $lat,
            $lng,
            $lat,
            $radius
        ])->getResultArray();

        return $this->response->setJSON([
            'status' => true,
            'mode'   => 'radius',
            'total'  => count($result),
            'data'   => $result
        ]);
    }

    public function detail($id)
    {
        $model = new KulinerModel();

        $data = $model
            ->select('kuliner.*, categories.nama_kategori')
            ->join('categories', 'categories.id = kuliner.category_id', 'left')
            ->where('kuliner.status', 'approved')
            ->find($id);

        if (!$data) {
            return $this->response->setStatusCode(404)
                ->setJSON([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
        }

        return $this->response->setJSON([
            'status' => true,
            'data' => $data
        ]);
    }
}
