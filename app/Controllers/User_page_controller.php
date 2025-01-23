<?php

namespace App\Controllers;

class User_page_controller extends BaseController
{
    public function index()
    {
        // Validasi apakah pengguna telah login
        if (!session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Ambil data dari sesi untuk ditampilkan di view
        $data = [
            'nama_users' => session()->get('nama_users'),
            'profile'    => session()->get('profile'),
            'email'      => session()->get('email'),
        ];

        // Tampilkan halaman user dengan data pengguna
        return view('user_page', $data);
    }
}
