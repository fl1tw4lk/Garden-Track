<?php

namespace App\Controllers;

use App\Models\UsersModel;
use Google_Client;

class Register extends BaseController
{
    protected $googleClient;
    protected $users;

    public function __construct()
    {
        $this->googleClient = new Google_Client();
        $this->users = new UsersModel();

        $this->googleClient->setClientId('30366827025-fddjqoeop0kfuibv7egj8abj8efbo6lj.apps.googleusercontent.com');
        $this->googleClient->setClientSecret('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');
        $this->googleClient->setRedirectUri('http://localhost:8080/register/proses');
        $this->googleClient->addScope('email');
        $this->googleClient->addScope('profile');
    }

    public function index()
    {
        $data['link']=$this->googleClient->CreateAuthUrl();
        return view('register_page', $data);
    }

    public function proses()
    {
        $token = $this->googleClient->fetchAccessTokenWithAuthCode($this->request->getvar('code'));
        if(!isset($token['error'])){
            $this->googleClient->setAccessToken($token['access_token']);
            $googleService = new \Google_Service_Oauth2($this->googleClient);
            $data = $googleService->userinfo->get();
            

            $row=[
                'id_user'=>$data['id'],
                'nama_users'=>$data['name'],
                'email'=>$data['email'],
                'profile'=>$data['picture'],
            ];
            $this->users->save($row);

            session()->set($row);
            return view('user_page');
        }   
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }

    public function regis_auth(){
        // Aturan validasi untuk form registrasi
        $rules = [
            'nama_users' => 'required',
            'email' => 'required|valid_email|is_unique[users.email]', 
            'password' => 'required',
            'confirm_password' => 'required|matches[password]',
        ];

        // Pesan error khusus email
        $messages = [
            'email' => [
                'is_unique' => 'Email ini sudah terdaftar. Silakan gunakan email lain.',
            ],
        ];

        // Validasi inputan
        if (!$this->validate($rules, $messages)) {
            session()->setFlashdata('error', $this->validator->getErrors());
            return redirect()->to('/register');
        }

        // Mendapatkan data input dari form
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $confirmPassword = $this->request->getPost('confirm_password');

        // Jika password dan confirm password tidak cocok
        if ($password !== $confirmPassword) {
            session()->setFlashdata('error', ['Password dan konfirmasi password tidak cocok.']);
            return redirect()->to('/register')->withInput()->with('password', ''); 
        }

        // Validasi email)
        if (strpos($email, '@') === false) {
            session()->setFlashdata('error', ['Email harus mengandung karakter "@"']);
            return redirect()->to('/register');
        }

        // Jika semua validasi lolos, maka kita lakukan proses penyimpanan user
        $passwordHash = password_hash($password, PASSWORD_BCRYPT); 
        // membuat id 
        do {
            // membuat random id 
            $id_user = 'user_' . uniqid() . '_' . random_int(1000, 9999);
            
            // cek apa id sudah ada
            $user = $this->users->where('id_user', $id_user)->first();
        } while ($user); // Ulangi jika ID sudah ada

        $data = [
            'id_user' => $id_user,
            'nama_users' => $this->request->getPost('nama_users'),
            'email' => $email,
            'password' => $passwordHash,
            'profile' => 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png'
        ];

        // Jalankan query insert dari model
        $simpan = $this->users->insert_user($data);

        if ($simpan) {
            // Jika insert berhasil, tampilkan pesan sukses dan arahkan ke halaman login
            session()->setFlashdata('success', 'Berhasil membuat akun');
            return redirect()->to('/login');
        } else {
            // Jika gagal insert, beri peringatan
            session()->setFlashdata('error1', ['Gagal membuat akun. Silakan coba lagi.']);
            return redirect()->to('/register');
        }
    }
}
