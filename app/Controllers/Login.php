<?php
namespace App\Controllers;
use App\Models\UsersModel;
use Google_Client;

class Login extends BaseController
{
    protected $googleClient;
    protected $users;

    public function __construct()
    {
        $this->googleClient = new Google_Client();
        $this->users = new UsersModel();

        $this->googleClient->setClientId('30366827025-fddjqoeop0kfuibv7egj8abj8efbo6lj.apps.googleusercontent.com');
        $this->googleClient->setClientSecret('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');
        $this->googleClient->setRedirectUri('http://localhost:8080/login/proses');
        $this->googleClient->addScope('email');
        $this->googleClient->addScope('profile');
    }

    public function index(){
        if (session()->get('access_token')) {
            $this->googleClient->revokeToken(session()->get('access_token'));
            session()->remove('access_token');
        }
    
        $data['link'] = $this->googleClient->createAuthUrl();
        return view('login_page', $data);
    }

    public function proses(){
        $token = $this->googleClient->fetchAccessTokenWithAuthCode($this->request->getvar('code'));
        if (!isset($token['error'])) {
            $this->googleClient->setAccessToken($token['access_token']);
            session()->set('access_token', $token['access_token']);
        
            $googleService = new \Google_Service_Oauth2($this->googleClient);
            $data = $googleService->userinfo->get();
        
            $row = [
                'id_user' => $data['id'],
                'nama_users' => $data['name'],
                'email' => $data['email'],
                'profile' => $data['picture'],
            ];
            $this->users->save($row);
        
            session()->set($row);
            return view('user_page');
        }
    }

    public function logout(){
        // Revoke the token if exists
        if ($accessToken = session()->get('access_token')) {
            $this->googleClient->revokeToken($accessToken);
        }

        // Destroy session
        session()->destroy();

        // Redirect to home or login page
        return redirect()->to('/');
    }

    public function auth(){
        // Validasi input
        $rules = [
            'nama_users' => 'required',
            'password' => 'required'
        ];

        if(!$this->validate($rules)){
            session()->setFlashdata('errors',$this->validator->getErrors());
            return view("login_page");
        }

        // Ambil inputan
        $username = $this->request->getPost('nama_users');
        $password = $this->request->getPost('password');

        //cek username
        $user = $this->users->get_user_username($username);

        // Jika username tidak ditemukan
        if (!$user) {
            session()->setFlashdata('error', 'Username tidak ditemukan');
            return redirect()->to('/login')->withInput();
        }

        // Verifikasi password
        if (!password_verify($password, $user->password)) {
            session()->setFlashdata('error', 'Password salah');
            return redirect()->to('/login')->withInput();
        }

        // Set session
        session()->set([
            'id_user' => $user->id_user,
            'nama_users' => $user->nama_users,
            'logged_in' => true
        ]);
        return redirect()->to('/');
    }
}
