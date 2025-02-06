<?php
namespace App\Controllers;

use App\Models\PlantModel;
use App\Models\TanamanKebunModel;
use App\Models\KebunModel;

class Tanaman extends BaseController
{
    private $apiToken;
    private $baseUrl;
    protected $logger;
    protected $PlantModel;
    protected $TanamanKebunModel;
    protected $kebunModel;

    public function __construct(){
        $this->PlantModel = new PlantModel();
        $this->TanamanKebunModel = new TanamanKebunModel();
        $this->kebunModel = new KebunModel();
        $this->apiToken = env('TOKEN');
        $this->logger = \Config\Services::logger(); 
        $this->baseUrl = 'https://trefle.io/api/v1/plants';
    }

    private function Tanaman($filter = null, $currentPage = 1) {
        $client = \Config\Services::curlrequest();

        // Set maximum page limit
        if ($currentPage < 1) {
            $currentPage = 1;
        }
        if ($currentPage > 21863) {
            $currentPage = 21863;
        }

        // Prepare query parameters
        $query = [
            'token' => $this->apiToken,
            'page' => $currentPage
        ];
        if ($filter) {
            $query['filter[vegetable]'] = 'true';
        }

        // API Endpoint for fetching plant data
        $response = $client->get("{$this->baseUrl}", ['query' => $query]);

        if ($response->getStatusCode() === 200) {
            $data = json_decode($response->getBody(), true);
            return [
                'plants' => $data['data'],
                'currentPage' => $currentPage,
                'totalPages' => $data['meta']['last_page'] ?? 1
            ];
        } else {
            $this->logger->error('Failed to retrieve data from Trefle API', ['response' => $response->getBody()]);
            log_message('error', 'Gagal mengambil data dari Trefle API: ' . $response->getBody());
            return [
                'plants' => [],
                'currentPage' => $currentPage,
                'totalPages' => 1,
                'error' => 'Gagal mengambil data dari Trefle API. Silakan coba lagi nanti.'
            ];
        }
    }

    public function index(){
        $currentPage = (int) $this->request->getGet('page') ?? 1;
        $data = $this->Tanaman(null, $currentPage);
        return view('tanaman/plants', $data, ['title' => 'Tanaman']);
    }

    public function search()
    {
        $page = $this->request->getGet('page') ?? 1;
        $searchQuery = $this->request->getGet('search'); // Ambil parameter pencarian

        // Tentukan URL API berdasarkan apakah ada pencarian atau tidak
        if ($searchQuery) {
            $url = "https://trefle.io/api/v1/plants?token={$this->apiToken}&q=" . urlencode($searchQuery) . "&page={$page}";
        } else {
            $url = "{$this->baseUrl}?token={$this->apiToken}&page={$page}";
        }

        // Panggil API Trefle
        $client = \Config\Services::curlrequest();
        $response = $client->get($url);
        $data = json_decode($response->getBody(), true);

        // Pastikan data ada
        if (!isset($data['data'])) {
            return redirect()->back()->with('error', 'Tanaman tidak ditemukan.');
        }

        return view('tanaman/plants', [
            'title' => 'Tanaman',
            'plants' => $data['data'],
            'pagination' => $data['links'] ?? [],
            'searchQuery' => $searchQuery,
            'currentPage' => $page
        ]);
    }

// --=========================================|| VEGETABLE ||================================================--
    public function vegetable()
    {
        $jsonPath = FCPATH . 'tanaman/json/sayuran.json';

        // Cek apakah file JSON ada
        if (!file_exists($jsonPath)) {
            throw new \RuntimeException("File JSON tidak ditemukan: $jsonPath");
        }

        // Baca file JSON
        $jsonData = file_get_contents($jsonPath);

        // Decode JSON ke array
        $plants = json_decode($jsonData, true);

        // Ambil halaman dari parameter GET
        $page = (int) ($this->request->getGet('page') ?? 1);
        $perPage = 9; // Jumlah data per halaman setelah scroll
        $start = ($page - 1) * $perPage;

        // Total data dalam JSON
        $totalData = count($plants);

        // Ambil data berdasarkan halaman
        $plantsPaginated = array_slice($plants, $start, $perPage);

        // Jika permintaan berasal dari AJAX (untuk load lebih banyak data)
        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'plants' => $plantsPaginated,
                'hasMore' => ($start + $perPage) < $totalData, // Cek apakah masih ada data yang tersisa
            ]);
        }else {
            return view('tanaman/vegetable', [
                'title' => 'Sayuran',
                'plants' => array_slice($plants, 0, 30), 
                'totalData' => $totalData
            ]);
        }
    }
// ------------------------------------------------++BARENG++-----------------------------------------------
    // public function vegetable()
    // {
    //     // Load data pertama kali (30 data pertama)
    //     $data['plants'] = $this->getPlants(0, 30); // Ambil 30 data pertama
    //     return view('vegetable', $data);
    // }

    // public function loadMore($offset)
    // {
    //     // Ambil 30 data berikutnya berdasarkan offset
    //     $plants = $this->getPlants($offset, 30);

    //     // Jika tidak ada data lagi, kembalikan array kosong
    //     if (empty($plants)) {
    //         return $this->response->setJSON([]);
    //     }

    //     // Kirim data dalam format JSON
    //     return $this->response->setJSON($plants);
    // }

    // private function getPlants($offset, $limit)
    // {
    //     // Load file JSON
    //     $jsonPath = FCPATH . 'tanaman/json/sayuran.json';
    //     $jsonData = file_get_contents($jsonPath);
    //     $allPlants = json_decode($jsonData, true);

    //     // Ambil data sesuai offset dan limit
    //     return array_slice($allPlants, $offset, $limit);
    // }
// ------------------------------------------------++Bareng++---------------------------------------------------
    // public function index(){
    //     $client = \Config\Services::curlrequest();
    //     $plantModel = new PlantModel();

    //     // Ambil halaman saat ini dari parameter URL, default 1
    //     $currentPage = (int) $this->request->getGet('page') ?? 1;
    //     if ($currentPage < 1) {
    //         $currentPage = 1;
    //     }

    //     // API Endpoint untuk mengambil data tanaman sayuran
    //     $response = $client->get("{$this->baseUrl}/plants", [
    //         'query' => [
    //             'filter[vegetable]' => 'true',
    //             'token' => $this->apiToken,
    //             'page' => $currentPage
    //         ]
    //     ]);

    //     if ($response->getStatusCode() === 200) {
    //         $data = json_decode($response->getBody(), true);

    //         if (!empty($data['data'])) {
    //             foreach ($data['data'] as $plant) {
    //                 // Periksa apakah data sudah ada di database berdasarkan trefle_id
    //                 $existingPlant = $plantModel->where('trefle_id', $plant['id'])->first();

    //                 if (!$existingPlant) {
    //                     // Simpan data ke database, termasuk URL gambar
    //                     $plantModel->insert([
    //                         'trefle_id' => $plant['id'],
    //                         'common_name' => $plant['common_name'] ?? 'Unknown',
    //                         'scientific_name' => $plant['scientific_name'] ?? 'Unknown',
    //                         'family' => $plant['family'] ?? null,
    //                         'genus' => $plant['genus'] ?? null,
    //                         'image_url' => $plant['image_url'] ?? ($plant['images']['original']['url'] ?? null) // Pastikan path ini benar
    //                     ]);                        
    //                 }
    //             }
    //         }

    //         return view('plants', [
    //             'plants' => $data['data'],
    //             'currentPage' => $currentPage,
    //             'totalPages' => $data['meta']['total_pages'] ?? 1
    //         ]);
    //     } else {
    //         // Log error atau tampilkan pesan error
    //         log_message('error', 'Gagal mengambil data dari Trefle API: ' . $response->getBody());
    //         return view('plants', [
    //             'plants' => [],
    //             'currentPage' => $currentPage,
    //             'totalPages' => 1,
    //             'error' => 'Gagal mengambil data dari Trefle API. Silakan coba lagi nanti.'
    //         ]);
    //     }
    // }



// public function ambildata(){
//         $client = \Config\Services::curlrequest();

//         // Ambil halaman saat ini dari parameter URL, default 1
//         $currentPage = (int) $this->request->getGet('page') ?? 1;
//         if ($currentPage < 1) {
//             $currentPage = 1;
//         }

//         // API Endpoint untuk mengambil data tanaman sayuran
//         $response = $client->get("{$this->baseUrl}/plants", [
//             'query' => [
//                 'filter[vegetable]' => 'true',
//                 'token' => $this->apiToken,
//                 'page' => $currentPage
//             ]
//         ]);

//         if ($response->getStatusCode() === 200) {
//             $data = json_decode($response->getBody(), true);

//             if (!empty($data['data'])) {
//                 foreach ($data['data'] as $plant) {
//                     // Periksa apakah data sudah ada di database berdasarkan trefle_id
//                     $existingPlant = $this->PlantModel->where('trefle_id', $plant['id'])->first();

//                     if (!$existingPlant) {
//                         // Simpan data ke database, termasuk URL gambar
//                         dd($existingPlant);die;
//                         $PlantModel->insert([
//                             'trefle_id' => $plant['id'],
//                             'common_name' => $plant['common_name'] ?? 'Unknown',
//                             'scientific_name' => $plant['scientific_name'] ?? 'Unknown',
//                             'family' => $plant['family'] ?? null,
//                             'genus' => $plant['genus'] ?? null,
//                             'image_url' => $plant['image_url'] ?? ($plant['images']['original']['url'] ?? null) // Pastikan path ini benar
//                         ]);                        
//                     }
//                 }
//             }
            
//             return view('plants', [
//                 'plants' => $data['data'],
//                 'currentPage' => $currentPage,
//                 'totalPages' => $data['meta']['total_pages'] ?? 1
//             ]);
//         } else {
//             // Log error atau tampilkan pesan error
//             log_message('error', 'Gagal mengambil data dari Trefle API: ' . $response->getBody());
//             return view('plants', [
//                 'plants' => [],
//                 'currentPage' => $currentPage,
//                 'totalPages' => 1,
//                 'error' => 'Gagal mengambil data dari Trefle API. Silakan coba lagi nanti.'
//             ]);
//         }
//     }

public function ambildata()
{
    $client = \Config\Services::curlrequest();

    // Ambil halaman saat ini dari parameter URL, default 1
    $currentPage = (int) ($this->request->getGet('page') ?? 1);
    if ($currentPage < 1) {
        $currentPage = 1;
    }

    // API Endpoint untuk mengambil data tanaman sayuran
    $response = $client->get($this->baseUrl, [
        'query' => [
            'token' => $this->apiToken,
            'page' => $currentPage
        ]
    ]);

    // dd($response); die;
    if ($response->getStatusCode() === 200) {
        $data = json_decode($response->getBody(), true);

        // dd($data); die;
        if (!empty($data['data'])) {
            foreach ($data['data'] as $plant) {
                // Periksa apakah data sudah ada di database berdasarkan trefle_id
                $existingPlant = $this->PlantModel->where('trefle_id', $plant['id'])->first();
                if (!$existingPlant) {
                    // Tentukan URL gambar dengan fallback jika tidak tersedia
                    $imageUrl = $plant['image_url'] ?? ($plant['images']['original']['url'] ?? null);
                    // Simpan data ke database
                    $this->PlantModel->insert([
                        'trefle_id' => $plant['id'],
                        'common_name' => $plant['common_name'] ?? 'Unknown',
                        'scientific_name' => $plant['scientific_name'] ?? 'Unknown',
                        'family' => $plant['family'] ?? null,
                        'genus' => $plant['genus'] ?? null,
                        'image_url' => $imageUrl
                    ]);
                }
            }
        }

        return view('/tanaman/plants', [
            'plants' => $data['data'],
            'currentPage' => $currentPage,
            'totalPages' => $data['meta']['total_pages'] ?? 1
        ]);
    } else {
        // Log error atau tampilkan pesan error
        log_message('error', 'Gagal mengambil data dari Trefle API: ' . $response->getBody());
        return view('/tanaman/plants', [
            'plants' => [],
            'currentPage' => $currentPage,
            'totalPages' => 1,
            'error' => 'Gagal mengambil data dari Trefle API. Silakan coba lagi nanti.'
        ]);
    }
}


// --=========================================|| TANAMAN-KEBUN ||================================================--
    public function formTambah($id_kebun)
    {
        // Ambil data tanaman dari tabel master tanaman
        $dataTanaman = $this->PlantModel->findAll();
        return view('tanaman/TambahTanaman', [
            'title' => 'Form Tambah',
            'id_kebun' => $id_kebun,
            'dataTanaman' => $dataTanaman,
        ]);
    }
    
    public function tambah($kebunId)
    {
        $dataTanaman = $this->PlantModel->findAll();
        $data = [
            'title' => 'Tambah Tanaman',
            'noFooter' => true,
            'id_kebun' => $kebunId,
            'dataTanaman' => $dataTanaman,
        ];
        return view('tanaman/TambahTanaman', $data);
    }

    public function simpanTanaman()
    {
        // Validasi input tanaman
        $rules = [
            'id_tanaman' => 'required',
            'id_kebun' => 'required',
            'benih' => 'required|numeric',
            'cara_menanam' => 'required',
            'kondisi_matahari' => 'required',
            'tanggal_mulai' => 'required|valid_date',
            'tanggal_selesai' => 'required|valid_date',
            'deskripsi' => 'required',
        ];
        
        if (!$this->validate($rules)) {
            $validation = \Config\Services::validation();
            return redirect()->back()->withInput()->with('error', 'Input tidak valid')->with('validation_errors', $validation->getErrors());
        }

        // Ambil data tanaman
        $dataTanaman = [
            'id_tanaman' => $this->request->getPost('id_tanaman'),
            'id_kebun' => $this->request->getPost('id_kebun'),
            'id_user' => session()->get('id_user'),
            'benih' => $this->request->getPost('benih'),
            'cara_menanam' => $this->request->getPost('cara_menanam'),
            'kondisi_matahari' => $this->request->getPost('kondisi_matahari'),
            'tanggal_mulai' => $this->request->getPost('tanggal_mulai'),
            'tanggal_selesai' => $this->request->getPost('tanggal_selesai'),
            'deskripsi' => $this->request->getPost('deskripsi'),
        ];
        // Simpan tanaman ke kebun
        $this->TanamanKebunModel->insert($dataTanaman);

        // Update status kebun menjadi "selesai"
        $this->kebunModel->update($dataTanaman['id_kebun'], ['status' => 'selesai']);

        return redirect()->to('/kebun/detail/'. $dataTanaman['id_kebun'])->with('success', 'Tanaman berhasil ditambahkan.');
    }
    
    // Method untuk menampilkan detail tanaman
    public function detail($id_tanaman_kebun)
    {
        $detailtanaman = $this->TanamanKebunModel->getTanamanDetailById($id_tanaman_kebun);
        if (!$detailtanaman) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Tanaman dengan tidak ditemukan.");
        }

        // Ambil tanggal mulai dan tanggal selesai dari data tanaman
        $tanggalMulai = strtotime($detailtanaman['tanggal_mulai']);
        $tanggalSelesai = strtotime($detailtanaman['tanggal_selesai']);
        $tanggalSekarang = time();  // Waktu sekarang

        // Menghitung jumlah total hari (dari tanggal mulai ke tanggal selesai)
        $jumlahHari = ($tanggalSelesai - $tanggalMulai) / (60 * 60 * 24);  // Hasil dalam hari

        // Menghitung jumlah hari yang sudah berlalu dari tanggal mulai hingga hari ini
        $hariYangBerjalan = ($tanggalSekarang - $tanggalMulai) / (60 * 60 * 24);

        // Menghitung progress hari berdasarkan hari yang telah berlalu dibandingkan dengan jumlah total hari
        if ($hariYangBerjalan < 0) {
            // Jika hari yang berlalu kurang dari 0 (artinya belum dimulai), progress = 0
            $progressHari = 0;
        } elseif ($hariYangBerjalan > $jumlahHari) {
            // Jika hari yang berlalu melebihi jumlah total hari (artinya sudah selesai), progress = 100
            $progressHari = 100;
        } else {
            // Jika tanggal sekarang berada di antara tanggal mulai dan selesai, hitung progress
            $progressHari = ($hariYangBerjalan / $jumlahHari) * 100;
        }

        if ($hariYangBerjalan < 0) {
        // Jika hari yang berlalu kurang dari 0 (artinya belum dimulai), progress = 0
        $progresBar = 0;
        } elseif ($hariYangBerjalan > $jumlahHari) {
            // Jika hari yang berlalu melebihi jumlah total hari (artinya sudah selesai), progress = 100
            $progresBar = 100;
        } else {
            // Jika tanggal sekarang berada di antara tanggal mulai dan selesai, hitung progress
            $progresBar = ($hariYangBerjalan / $jumlahHari) * 100;
        }

        // Menambahkan jumlah hari dan progress ke data
        $data = [
            'title' => 'Detail Tanaman',
            'tanaman' => $detailtanaman,
            'jumlahHari' => round($jumlahHari),  // Pembulatan untuk jumlah hari
            'progressHari' => round($progressHari),  // Pembulatan untuk progress berdasarkan jumlah hari
            'progressBar' => round($progresBar),  // Pembulatan untuk progress bar
        ];
        // dd($data);die;
        return view('tanaman/Detail_Tanaman', $data);
    }
    
    public function edit($id)
    {
        // Ambil data tanaman berdasarkan ID
        $tanaman = $this->TanamanKebunModel->getDetailTanaman($id);

        // Jika data tidak ditemukan, lempar error 404
        if (!$tanaman) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Tanaman tidak ditemukan");
        }
        // dd($tanaman);
        return view('tanaman/update_tanaman', [
            'title' => 'Form Edit Tanaman',
            'tanaman' => $tanaman,
        ]);
    }
        
    public function update($id)
    {
        $id = $this->request->getPost('id');

        if (!is_numeric($id) || !$id) {
            return redirect()->back()->with('error', 'ID tanaman tidak valid.');
        };

        // Ambil data tanaman_kebun berdasarkan ID
        $tanamanKebun = $this->TanamanKebunModel->getDetailTanaman($id);

        // Cek apakah data ditemukan
        if (!$tanamanKebun) {
            return redirect()->back()->with('error', 'Data tanaman tidak ditemukan.');
        }

        // Ambil id_user yang sedang login
        $user = session()->get('id_user');

        $userCheck = $this->TanamanKebunModel->where('id', $id)
                                        ->where('id_user', $user)
                                        ->first();

        // Jika tidak ditemukan, berarti pengguna tidak memiliki izin
        if (!$userCheck) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mengupdate data ini.');
        }

        // Aturan validasi
        $rules = [
            'id_tanaman' => 'required',
            'id_user' => 'required',
            'id_kebun' => 'required',
            'benih' => 'required|numeric',
            'cara_menanam' => 'required',
            'kondisi_matahari' => 'required',
            'tanggal_mulai' => 'required|valid_date',
            'tanggal_selesai' => 'required|valid_date',
            'deskripsi' => 'required',
        ];

        // Validasi input
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Validasi gagal, periksa kembali input Anda.');
        }
        // Ambil data yang sudah divalidasi
        $tanaman = [
            'id_tanaman' => $this->request->getPost('id_tanaman'),
            'id_kebun' => $this->request->getPost('id_kebun'),
            'benih' => $this->request->getPost('benih'),
            'cara_menanam' => $this->request->getPost('cara_menanam'),
            'kondisi_matahari' => $this->request->getPost('kondisi_matahari'),
            'tanggal_mulai' => $this->request->getPost('tanggal_mulai'),
            'tanggal_selesai' => $this->request->getPost('tanggal_selesai'),
            'deskripsi' => $this->request->getPost('deskripsi')
        ];

        try {
            // Update data
            $this->TanamanKebunModel->update($id, $tanaman);
            session()->setFlashdata('success', 'Data tanaman berhasil diperbarui!');
            return redirect()->to('/tanaman/detail/' . $this->request->getPost('id'));
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Gagal memperbarui data tanaman: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    // Method untuk menghapus tanaman dari kebun
    public function delete($id)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Cek apakah tanaman dalam kebun ada berdasarkan ID
        $tanamanKebun = $this->TanamanKebunModel->find($id);
                                                                            
        if (!$tanamanKebun) {
            // Jika data tanaman tidak ditemukan, redirect dengan pesan error
            return redirect()->back()->with('error', 'Data tanaman tidak ditemukan.');
        }

        // Simpan ID kebun untuk redirect setelah hapus
        $id_kebun = $tanamanKebun['id_kebun'];

        // Hapus tanaman dari tabel tanaman_kebun berdasarkan ID
        if ($this->TanamanKebunModel->delete($id)) {
            // Redirect ke halaman kebun setelah tanaman berhasil dihapus
            return redirect()->to('/kebun/detail/' . $id_kebun)->with('success', 'Tanaman berhasil dihapus.');
        }

        // Jika gagal menghapus tanaman, redirect kembali dengan pesan error
        return redirect()->back()->with('error', 'Gagal menghapus tanaman.');
    }
}