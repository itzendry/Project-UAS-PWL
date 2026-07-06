<?php

namespace App\Controllers;

use App\Models\KulinerModel;
use App\Models\ReviewModel;
use App\Models\UserModel;

class Admin extends BaseController
{
    public function dashboard()
    {
        $kulinerModel = new KulinerModel();
        $reviewModel = new ReviewModel();
        $userModel = new UserModel();

        return view('admin/dashboard', [
            'totalKuliner' => $kulinerModel->countAllResults(),
            'pendingKuliner' => $kulinerModel->where('status', 'pending')->countAllResults(),
            'totalReview' => $reviewModel->countAllResults(),
            'totalUser' => $userModel->where('role', 'user')->countAllResults(),
            'topKuliner' => $kulinerModel
                ->select('kuliner.*, categories.nama_kategori')
                ->join('categories', 'categories.id = kuliner.category_id', 'left')
                ->where('kuliner.status', 'approved')
                ->orderBy('kuliner.rating', 'DESC')
                ->limit(5)
                ->findAll(),
        ]);
    }

    public function kuliner()
    {
        return view('admin/kuliner', [
            'kuliner' => (new KulinerModel())
                ->select('kuliner.*, categories.nama_kategori, users.nama AS nama_user')
                ->join('categories', 'categories.id = kuliner.category_id', 'left')
                ->join('users', 'users.id = kuliner.user_id', 'left')
                ->orderBy('kuliner.created_at', 'DESC')
                ->findAll(),
        ]);
    }

    public function approve($id)
    {
        (new KulinerModel())->update($id, ['status' => 'approved']);

        return redirect()->back()->with('success', 'Tempat kuliner berhasil disetujui.');
    }

    public function reject($id)
    {
        (new KulinerModel())->update($id, ['status' => 'rejected']);

        return redirect()->back()->with('success', 'Tempat kuliner berhasil ditolak.');
    }

    public function reviews()
    {
        return view('admin/reviews', [
            'reviews' => (new ReviewModel())
                ->select('reviews.*, kuliner.nama_tempat, users.nama AS nama_user')
                ->join('kuliner', 'kuliner.id = reviews.kuliner_id', 'left')
                ->join('users', 'users.id = reviews.user_id', 'left')
                ->orderBy('reviews.created_at', 'DESC')
                ->findAll(),
        ]);
    }

    public function deleteReview($id)
    {
        $reviewModel = new ReviewModel();
        $review = $reviewModel->find($id);

        if (! $review) {
            return redirect()->back()->with('error', 'Review tidak ditemukan.');
        }

        $kulinerId = $review['kuliner_id'];
        $reviewModel->delete($id);
        $this->refreshRating($kulinerId);

        return redirect()->back()->with('success', 'Review berhasil dihapus.');
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
}
