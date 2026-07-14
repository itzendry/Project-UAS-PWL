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
            
            // GEMBOK KEAMANAN: Cek apakah akun sudah diverifikasi
            if ($user['status'] !== 'active') {
                return redirect()->back()->with('error', 'Login gagal. Akun Anda belum diverifikasi. Silakan cek email Anda.');
            }

            session()->set([
                'login'   => true,
                'user_id' => $user['id'],
                'role'    => $user['role'],
                'nama'    => $user['nama']
            ]);
            return redirect()->to('/kuliner');
        }

        return redirect()->back()->with('error','Login gagal. Email atau password salah.');
    }

    public function register()
    {
        return view('register');
    }

    public function prosesRegister()
    {
        $rules = [
            'email' => [
                'rules'  => 'required|valid_email|is_unique[users.email]',
                'errors' => [
                    'is_unique'   => 'Maaf, email ini sudah terdaftar.',
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

        if (!$this->validate($rules)) {
            $errorMsg = $this->validator->getError('email') ?: $this->validator->getError('password');
            return redirect()->back()->withInput()->with('error', $errorMsg);
        }

        $model = new UserModel();
        
        $nama  = $this->request->getPost('nama');
        $email = $this->request->getPost('email');
        
        // Membuat token acak unik untuk link verifikasi
        $token = bin2hex(random_bytes(32));
        
        // Simpan ke database dengan status 'inactive'
        $model->save([
            'nama'     => $nama,
            'email'    => $email,
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'     => 'user',
            'status'   => 'inactive',
            'token'    => $token
        ]);

        // --- PROSES KIRIM EMAIL LINK VERIFIKASI ---
        $emailService = \Config\Services::email();
        $emailService->setTo($email); 
        $emailService->setSubject('🔑 Verifikasi Akun KulinerZone Anda');
        
        // Membuat URL verifikasi yang mengarah ke fungsi verifikasi()
        $linkVerifikasi = base_url("auth/verifikasi/" . $token);
        
        $pesan = "
            <div style='font-family: Arial, sans-serif; padding: 20px; background-color: #F4F7FE; border-radius: 10px;'>
                <h2 style='color: #111C43;'>Halo, " . htmlspecialchars($nama) . "!</h2>
                <p style='font-size: 16px; color: #47548C;'>Satu langkah lagi sebelum kamu bisa menjelajahi KulinerZone.</p>
                <p style='font-size: 16px; color: #47548C;'>Silakan klik tombol di bawah ini untuk memverifikasi email dan mengaktifkan akun kamu:</p>
                <br>
                <a href='" . $linkVerifikasi . "' style='background-color: #28a745; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;'>Verifikasi Akun Saya</a>
                <br><br>
                <p style='font-size: 12px; color: #A3AED0;'>Jika tombol tidak berfungsi, kamu juga bisa menyalin link berikut ke browser Anda:<br>" . $linkVerifikasi . "</p>
                <hr style='border: 0; border-top: 1px solid #dcdcdc; margin: 20px 0;'>
                <p style='font-size: 14px; color: #A3AED0;'>Salam Hangat,<br>Tim KulinerZone</p>
            </div>
        ";
        
        $emailService->setMessage($pesan);
        $emailService->send();

        return redirect()->to('/login')->with('success', 'Akun berhasil dibuat! Silakan BUKA GMAIL ANDA untuk melakukan verifikasi akun terlebih dahulu sebelum login.');
    }

    // Fungsi baru untuk memproses token saat link di email diklik
    public function verifikasi($token)
    {
        $model = new UserModel();
        
        // Cari user berdasarkan token
        $user = $model->where('token', $token)->first();
        
        if ($user) {
            // Jika token cocok, ubah status jadi 'active' dan hapus tokennya agar tidak bisa dipakai lagi
            $model->update($user['id'], [
                'status' => 'active',
                'token'  => null
            ]);
            
            return redirect()->to('/login')->with('success', 'Selamat! Email Anda berhasil diverifikasi. Silakan login.');
        } else {
            // Jika token palsu atau sudah kadaluwarsa
            return redirect()->to('/login')->with('error', 'Link verifikasi tidak valid atau sudah tidak berlaku.');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }
}