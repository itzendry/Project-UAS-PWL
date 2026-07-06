<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthRole implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (! session()->get('login')) {
            return redirect()->to('/login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $roles = $arguments ?? [];
        if ($roles !== [] && ! in_array(session()->get('role'), $roles, true)) {
            return redirect()->to('/kuliner')
                ->with('error', 'Akses ditolak. Halaman ini hanya untuk role tertentu.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
