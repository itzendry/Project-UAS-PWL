<?php

namespace App\Controllers;

use App\Models\CategoryModel;

class Category extends BaseController
{
    public function index()
    {
        return view('admin/categories', [
            'categories' => (new CategoryModel())->orderBy('nama_kategori', 'ASC')->findAll(),
        ]);
    }

    public function save()
    {
        if (! $this->validate(['nama_kategori' => 'required|min_length[3]|max_length[100]'])) {
            return redirect()->back()->withInput()->with('error', 'Nama kategori wajib diisi minimal 3 karakter.');
        }

        (new CategoryModel())->save([
            'id' => $this->request->getPost('id') ?: null,
            'nama_kategori' => $this->request->getPost('nama_kategori'),
        ]);

        return redirect()->to('/admin/categories')->with('success', 'Kategori berhasil disimpan.');
    }

    public function delete($id)
    {
        (new CategoryModel())->delete($id);

        return redirect()->to('/admin/categories')->with('success', 'Kategori berhasil dihapus.');
    }
}
