<?php

namespace App\Controllers;

use App\Models\UsersModel;
use Google_Client;

class register extends BaseController
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
}
