<?php
namespace App\Controllers;
use App\Models\UsersModel;
use App\Models\KebunModel;
use App\Models\TanamanKebunModel;
use Google_Client;

class Pengguna extends BaseController
{
    private $googleClient;
    protected $users;
    protected $kebunModel;
    protected $tanamanKebunModel;

    public function __construct()
    {
        $this->googleClient = new Google_Client();
        $this->users = new UsersModel();
        $this->kebunModel = new KebunModel();
        $this->tanamanKebunModel = new TanamanKebunModel();
        $this->googleClient->setClientId(env('Clientid'));
        $this->googleClient->setClientSecret(env('ClientSecret'));
        $this->googleClient->setRedirectUri('http://localhost:8080/login/proses');
        $this->googleClient->setRedirectUri('http://localhost:8080/register/proses');
        $this->googleClient->addScope('email');
        $this->googleClient->addScope('profile');
        $this->googleClient->setPrompt('select_account'); 
    }

// --=========================================|| LOGIN ||================================================--

    // LOGIN GOOGLE
    public function index_login()
{
    // Hapus token dari sesi untuk memastikan pengguna dapat memilih akun baru
    if (session()->has('access_token')) {
        $this->googleClient->revokeToken(session()->get('access_token'));
        session()->remove('access_token');
    }
    
    $data['link'] = $this->googleClient->createAuthUrl();
    return view('pengguna/login_page', $data, ['title' => 'Login']);
}


    public function proses_login(){
        $token = $this->googleClient->fetchAccessTokenWithAuthCode($this->request->getvar('code'));
        if (!isset($token['error'])) {
            $this->googleClient->setAccessToken($token['access_token']);
            session()->set('access_token', $token['access_token']);
    
            $googleService = new \Google_Service_Oauth2($this->googleClient);
            $data = $googleService->userinfo->get();
    
            // Simpan data ke dalam array sesi
            $row = [
                'id_user'     => $data['id'],
                'nama_users'  => $data['name'],
                'email'       => $data['email'],
                'profile'     => $data['picture'],
                'logged_in'   => true,
            ];
            session()->set($row);
    
            $userExists = $this->users->where('id_user', $data['id'])->first();
            if (!$userExists) {
                $this->users->save($row);
            }
            
            return redirect()->to('/user_page');
        }
        return redirect()->to('/login')->with('error', 'Autentikasi gagal.');
    }

    //LOGIN BIASA
    public function auth(){
        // Validasi input
        $rules = [
            'email' => 'required',
            'password' => 'required'
        ];
    
        if(!$this->validate($rules)){
            session()->setFlashdata('errors', $this->validator->getErrors());
            return view("pengguna/login_page");
        }
    
        // Ambil inputan
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
    
        //cek email
        $user = $this->users->get_user_email($email);

        // Jika email tidak ditemukan
        if (!$user) {
            session()->setFlashdata('error', 'Email tidak ditemukan');
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
            'nama_users' =>$user->nama_users,
            'email' => $user->email,
            'profile' => $user->profile,
            'logged_in' => true
        ]);
        
        // Tampilkan pesan sukses
        session()->setFlashdata('success', 'Login berhasil!');
        return redirect()->to('/user_page');
    }

    // -----------------------------------++USER_PAGE++----------------------------------------
    public function home(){
        // Validasi apakah pengguna telah login
        if (!session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Ambil data dari sesi untuk ditampilkan di view
        $data = [
            'title' => 'Dashboard',
            'nama_users' => session()->get('nama_users'),
            'profile'    => session()->get('profile'),
            'email'      => session()->get('email'),
        ];

        // Tampilkan halaman user dengan data pengguna
        return view('pengguna/user_page', $data);
    }

// --=========================================|| REGISTER ||================================================--

    // REGISTER DISINI
    public function index_regis()
    {
        $data['link']=$this->googleClient->CreateAuthUrl();
        return view('pengguna/register_page', $data, ['title' => 'Registrasi']);
    }

    //REGIS GOOGLE
    public function proses_regis()
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
            session()->set('logged_in', true);
            session()->setFlashdata('success', 'Login berhasil!');
            return redirect()->to('/user_page');
        }   
    }

    //REGIS BIASA
    public function regis_auth()
    {
        // Aturan validasi untuk form registrasi
        $rules = [
            'nama_users' => 'required|alpha_space|min_length[3]|max_length[20]',
            'email' => 'required|valid_email', 
            'password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[password]',
        ];

        // Pesan error khusus
        $messages = [
            'nama_users' => [
                'alpha_space' => 'Nama hanya boleh berisi huruf',
                'min_length' => 'Nama harus memiliki minimal 3 karakter.',
                'max_length' => 'Nama tidak boleh lebih dari 20 karakter.',
            ],
            'email' => [
                'is_unique' => 'Email ini sudah terdaftar. Silakan gunakan email lain.',
            ],
            'password' => [
                'min_length' => 'Password harus memiliki minimal 6 karakter.',
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
        
        $user = $this->users->where('email', $email)->first();
        if ($user && $user['deleted_at'] === null) {
            // Email sudah terdaftar dan akun tidak dihapus
            session()->setFlashdata('error', ['Email ini sudah terdaftar. Silakan gunakan email lain.']);
            return redirect()->to('/register');
        }
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
// --=========================================|| LOGOUT ||================================================--
    // LOGOUT DISINI
    public function logout()
    {
        if (session()->get('access_token')) {
            $this->googleClient->revokeToken(session()->get('access_token')); // Revoke token
            session()->remove('access_token'); // Hapus access token dari sesi
        }
    
        // Hapus semua sesi pengguna
        session()->destroy();
    
        return redirect()->to('/')->with('message', 'Anda telah berhasil logout.');
    }
    
// --=========================================|| PANEL ||================================================--
    public function dashboard(): string
    {
        return view('home',['title' => 'Home Page']);
    }
    
    public function services(): string
    {
        return view('services',['title' => 'Layanan']);
    }

    // --======================================|| PROFILE ||==================================================--
        public function editProfile($id_user)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = $this->users->find($id_user);

        if (!$user) {
            return redirect()->to('/user_page')->with('error', 'Pengguna tidak ditemukan.');
        }

        return view('pengguna/update_profile', [
            'title' => 'Edit Profil',
            'user' => $user
        ]);
    }

    public function updateProfile($id_user)
    {
        // Ambil data pengguna yang sedang diupdate
        $userLama = $this->users->find($id_user);

        $existingUser = $this->users->getUserByEmailExcludingSelf($this->request->getPost('email'), $id_user);
        
        if ($existingUser) {
            return redirect()->to('/Pengguna/editProfile/' . $id_user)
                            ->withInput()
                            ->with('error', ['Email sudah digunakan, silakan gunakan email lain.']);
        }

        // Aturan validasi
        $rules = [
            'nama_users' => 'required|max_length[50]',
            'email' => [
                'rules' => 'required|valid_email',
                'errors' => [
                    'required' => 'Email wajib diisi.',
                    'valid_email' => 'Format email tidak valid.'
                ]
            ],
            'profile' => 'is_image[profile]|mime_in[profile,image/jpg,image/jpeg,image/png]|max_size[profile,2048]',
        ];

        // Jika password diisi, validasi harus sama dengan confirm_password
        if ($this->request->getPost('password')) {
            $rules['password'] = [
                'rules' => 'required|min_length[6]',
                'errors' => [
                    'required' => 'Password wajib diisi.',
                    'min_length' => 'Password minimal 6 karakter.'
                ]
            ];
            $rules['confirm_password'] = [
                'rules' => 'required|matches[password]',
                'errors' => [
                    'required' => 'Konfirmasi password wajib diisi.',
                    'matches' => 'Password dan konfirmasi password harus sama.'
                ]
            ];
        }

        if (!$this->validate($rules)) {
            return redirect()->to('/Pengguna/editProfile/' . $id_user)
                        ->withInput()
                        ->with('error', $this->validator->getErrors());
        }

        // Data yang akan diupdate
        $data = [
            'nama_users' => $this->request->getPost('nama_users'),
            'email' => $this->request->getPost('email'),
        ];

        // Jika user mengubah password, lakukan hashing sebelum update
        if ($this->request->getPost('password')) {
            $data['password'] = password_hash($this->request->getPost('password'), PASSWORD_BCRYPT);
        }

        // Handle file upload
        $file = $this->request->getFile('profile');

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move('uploads/profile', $newName);
            
            // Hapus foto lama jika bukan default
            if ($userLama->profile && $userLama->profile != 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png') {
                @unlink($userLama->profile); // Hapus file lama
            }

            // Set path file baru dalam database
            $data['profile'] = $newName;
        }

        // Update user data
        $this->users->update($id_user, $data);

        // Update session jika user yang sedang login yang diupdate
        if (session()->get('id_user') == $id_user) {
            session()->set($data);
        }

        return redirect()->to('/Pengguna/editProfile/' . $id_user)->with('success', 'Profil berhasil diperbarui.');
    }

    public function deleteProfile($id_user)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Ambil data pengguna berdasarkan ID
        $user = $this->users->asArray()->find($id_user);

        if (!$user) {
            return redirect()->to('/Pengguna/editProfile/' . $id_user)->with('error', 'Pengguna tidak ditemukan.');
        }

        // Hapus kebun yang dimiliki oleh user menggunakan model Kebun
        $kebunList = $this->kebunModel->where('id_user', $id_user)->findAll();

        foreach ($kebunList as $kebun) {
            // Hapus gambar kebun jika ada dan bukan gambar default
            if (!empty($kebun['poto_kebun']) && $kebun['poto_kebun'] != 'uploads/kebun/default.jpg') {
                $potoKebunPath = FCPATH . $kebun['poto_kebun'];
                if (file_exists($potoKebunPath)) {
                    unlink($potoKebunPath); // Hapus gambar kebun
                }
            }
            // Hapus tanaman yang terkait dengan kebun ini menggunakan model TanamanKebun
            $this->tanamanKebunModel->where('id_kebun', $kebun['id_kebun'])->delete();
        }
        // Hapus semua kebun milik user
        $this->kebunModel->where('id_user', $id_user)->delete();

        // **Hapus gambar profil jika bukan default dan file ada**
        if (!empty($user['profile']) && !filter_var($user['profile'], FILTER_VALIDATE_URL)) {
            $profilePath = FCPATH . 'uploads/profile/' . $user['profile']; 

            if (file_exists($profilePath)) {
                unlink($profilePath);
            }
        }

        // Hapus akun pengguna
        $this->users->delete($id_user);

        // Jika akun yang dihapus adalah akun yang sedang login, logout user
        if (session()->get('id_user') == $id_user) {
            session()->destroy();
            session()->setFlashdata('success', 'Akun Anda berhasil dihapus.');
            return redirect()->to('/login');
        }

        return redirect()->to('/login')->with('success', 'Akun berhasil dihapus.');
    }
}