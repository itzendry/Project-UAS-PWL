<?php

namespace App\Controllers;
use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        return view('login');
    }

    public function prosesLogin()
    {
        $model = new UserModel();
        $user = $model->where('email', $this->request->getPost('email'))->first();

        if ($user && password_verify($this->request->getPost('password'), $user['password'])) {
            session()->set([
                'login'   => true,
                'user_id' => $user['id'],
                'role'    => $user['role'],
                'nama'    => $user['nama'] // <--- TAMBAHKAN BARIS INI
            ]);
            return redirect()->to('/kuliner');
        }

        return redirect()->back()->with('error','Login gagal. Email atau password salah.');
    }

    // FUNGSI REGISTER
    public function register()
    {
        return view('register');
    }

    public function prosesRegister()
    {
        // 1. ATURAN VALIDASI
        $rules = [
            'email' => [
                'rules'  => 'required|valid_email|is_unique[users.email]',
                'errors' => [
                    'is_unique'   => 'Maaf, email ini sudah terdaftar. Silakan gunakan email lain.',
                    'valid_email' => 'Format email tidak valid.',
                    'required'    => 'Email wajib diisi.'
                ]
            ],
            'password' => [
                'rules'  => 'required|min_length[8]',
                'errors' => [
                    'required'   => 'Password wajib diisi.',
                    'min_length' => 'Password minimal harus 8 karakter.'
                ]
            ]
        ];

        //VALIDASI
        if (!$this->validate($rules)) {
            $errorMsg = $this->validator->getError('email') ?: $this->validator->getError('password');
            return redirect()->back()->withInput()->with('error', $errorMsg);
        }

        $model = new UserModel();
        
        //hash
        $model->save([
            'nama'     => $this->request->getPost('nama'),  // <--- PASTIKAN BARIS INI ADA
            'email'    => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'     => 'user'
        ]);

        return redirect()->to('/login')->with('success', 'Akun berhasil dibuat! Silakan login.');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }
}