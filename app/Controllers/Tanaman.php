<?php
namespace App\Controllers;

use App\Models\PlantModel;
use App\Models\TanamanKebunModel;
use App\Models\KebunModel;
use \DateTime; // Tambahkan import ini

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

    private function Tanaman($searchQuery = null) {
        $client = \Config\Services::curlrequest();
    
        // Define category explicitly as 'vegetable'
        $category = 'vegetable'; 
    
        // Setup query parameters with token
        $query = [
            'token' => $this->apiToken
        ];
    
        // Add search query if present
        if ($searchQuery) {
            $query['q'] = urlencode($searchQuery);
        }
    
        // Apply filter for vegetable category (using URL-encoded parameter)
        $query['filter%5Bvegetable%5D'] = ''; // Leave the value empty (instead of 'true')
    
        $allPlants = [];
        $currentPage = 1;
    
        // Try to fetch data with a reasonable timeout
        try {
            // Loop to retrieve all pages (or until a limit is reached)
            while (true) {
                $query['page'] = $currentPage;
    
                // Send API request with query parameters and timeout
                $response = $client->get($this->baseUrl, [
                    'query'   => $query,
                    'timeout' => 60 // Timeout set to 60 seconds
                ]);
    
                // Check if the response is successful
                if ($response->getStatusCode() === 200) {
                    $data = json_decode($response->getBody(), true);
    
                    // If no more data, break the loop
                    if (empty($data['data'])) {
                        break;
                    }
    
                    // Merge the current page data into the allPlants array
                    $allPlants = array_merge($allPlants, $data['data']);
                    $currentPage++;
    
                    // Stop if we've retrieved more than 100 items (adjust as needed)
                    if (count($allPlants) > 100) {
                        break;
                    }
    
                    // *** New condition: Stop if we've fetched more than 10 pages ***
                    if ($currentPage > 10) {
                        break;
                    }
                } else {
                    throw new \Exception("Error API Response: " . $response->getBody());
                }
            }
        } catch (\Exception $e) {
            $this->logger->error('Failed to retrieve data from Trefle API: ' . $e->getMessage());
            log_message('error', 'Gagal mengambil data dari Trefle API: ' . $e->getMessage());
            return [
                'plants' => [],
                'error'  => 'Gagal mengambil data dari Trefle API. Silakan coba lagi nanti.'
            ];
        }
    
        // Filter plants that have a common name and an image URL
        $filteredPlants = array_filter($allPlants, function($plant) {
            return !empty($plant['common_name']) && !empty($plant['image_url']);
        });
    
        // If a search query is provided, further filter the results based on the query
        if ($searchQuery) {
            $filteredPlants = array_filter($filteredPlants, function($plant) use ($searchQuery) {
                return stripos($plant['common_name'], $searchQuery) !== false;
            });
        }
    
        // If no plants are found after filtering, return an error message
        if (empty($filteredPlants)) {
            return [
                'plants' => [],
                'error'  => 'Tanaman tidak ditemukan atau tidak memiliki nama umum dan gambar.'
            ];
        }
    
        // Now apply pagination on the filtered results
        $perPage = 20; // Number of items per page
        $totalItems = count($filteredPlants);
        $totalPages = ceil($totalItems / $perPage);
    
        // Get current page from GET parameters; default to 1 if not set
        $currentPageRequest = (int) $this->request->getGet('page');
        if ($currentPageRequest < 1) {
            $currentPageRequest = 1;
        } elseif ($currentPageRequest > $totalPages) {
            $currentPageRequest = $totalPages;
        }
        $offset = ($currentPageRequest - 1) * $perPage;
        $plantsPaginated = array_slice($filteredPlants, $offset, $perPage);
    
        // Cache the full filtered result for 1 hour (optional)
        $cacheKey = md5($category . $searchQuery);
        $cache = \Config\Services::cache();
        $cache->save($cacheKey, [
            'plants'      => $filteredPlants,
            'searchQuery' => $searchQuery ?? ''
        ], 3600);
    
        return [
            'plants'      => $plantsPaginated,
            'searchQuery' => $searchQuery ?? '',
            'totalItems'  => $totalItems,
            'totalPages'  => $totalPages,
            'currentPage' => $currentPageRequest,
        ];
    }

    public function index() {
        // Define category explicitly as 'vegetable'
        $category = 'vegetable';
        $searchQuery = $this->request->getGet('search'); // Get search query parameter
    
        // Fetch plant data based on category and search query, with pagination applied
        $data = $this->Tanaman($searchQuery);
    
        // If there is an error, redirect back with error message
        if (!empty($data['error'])) {
            return redirect()->back()->with('error', $data['error']);
        }
    
        // Pass pagination data along with the plants to the view
        return view('tanaman/plants', [
            'title'       => 'Tanaman',
            'plants'      => $data['plants'],
            'searchQuery' => $data['searchQuery'] ?? '',
            'totalItems'  => $data['totalItems'],
            'totalPages'  => $data['totalPages'],
            'currentPage' => $data['currentPage']
        ]);
    }
    
    public function search() {
        // The category is implicitly 'vegetable' in Tanaman(), so we only get the search query.
        $searchQuery = $this->request->getGet('search'); // Get search query parameter
    
        // Fetch plant data based on the search query (with 'vegetable' filter applied in Tanaman())
        $data = $this->Tanaman($searchQuery);
    
        // If no plants are found, show an error message (using flashdata for example)
        if (empty($data['plants'])) {
            return redirect()->back()->with('error', 'Tanaman tidak ditemukan atau tidak memiliki nama umum dan gambar.');
        }
    
        // Return the view with the paginated plants and pagination information
        return view('tanaman/plants', [
            'title'       => 'Tanaman',
            'plants'      => $data['plants'],
            'searchQuery' => $data['searchQuery'] ?? '',
            'totalItems'  => $data['totalItems'],
            'totalPages'  => $data['totalPages'],
            'currentPage' => $data['currentPage']
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

        // Filter data untuk menghapus yang memiliki common_name atau image_url null
        $filteredPlants = array_filter($plants, function ($plant) {
            return !is_null($plant['common_name']) && !is_null($plant['image_url']);
        });

        // Reindex array agar key mulai dari 0 setelah filter
        $filteredPlants = array_values($filteredPlants);

        // Ambil halaman dari parameter GET
        $page = (int) ($this->request->getGet('page') ?? 1);
        $perPage = 12; 
        $start = ($page - 1) * $perPage;
        

        // Total data setelah filter
        $totalData = count($filteredPlants);

        // Ambil data berdasarkan halaman
        $plantsPaginated = array_slice($filteredPlants, $start, $perPage);

        // Jika permintaan berasal dari AJAX (untuk load lebih banyak data)
        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'plants' => $plantsPaginated,
                'hasMore' => ($start + $perPage) < $totalData, // Cek apakah masih ada data yang tersisa
            ]);
        } else {
            return view('tanaman/vegetable', [
                'title' => 'Sayuran',
                'plants' => array_slice($filteredPlants, 0, 30), 
                'totalData' => $totalData
            ]);
        }
    }

    public function loadVegetables()
    {
        $jsonPath = FCPATH . 'tanaman/json/sayuran.json';

        if (!file_exists($jsonPath)) {
            return $this->response->setJSON([
                'error' => 'File JSON tidak ditemukan'
            ]);
        }

        $jsonData = file_get_contents($jsonPath);
        $plants = json_decode($jsonData, true);

        $filteredPlants = array_filter($plants, function ($plant) {
            return !is_null($plant['common_name']) && !is_null($plant['image_url']);
        });

        $filteredPlants = array_values($filteredPlants);

        $page = (int) ($this->request->getGet('page') ?? 1);
        $perPage = 12;
        $start = ($page - 1) * $perPage;
        $totalData = count($filteredPlants);
        $plantsPaginated = array_slice($filteredPlants, $start, $perPage);

        // Tambahkan log untuk debugging
        log_message('debug', 'Load Vegetables: Page ' . $page . ' Data: ' . json_encode($plantsPaginated));

        return $this->response->setJSON([
            'plants' => $plantsPaginated,
            'hasMore' => ($start + $perPage) < $totalData
        ]);
    }

    public function search_vegetable()
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

        // Ambil query pencarian dari parameter GET
        $query = trim($this->request->getGet('search'));

        // Jika ada query pencarian, filter data
        if (!empty($query)) {
            $filteredPlants = array_filter($plants, function ($plant) use ($query) {
                // Cek apakah query ditemukan di common_name atau scientific_name (case-insensitive)
                return (stripos($plant['common_name'], $query) !== false) || (stripos($plant['scientific_name'], $query) !== false);
            });
        } else {
            $filteredPlants = $plants;
        }

        // Reindex array setelah filter agar indeks dimulai dari 0
        $filteredPlants = array_values($filteredPlants);

        // Pagination: ambil parameter halaman dan hitung slice array
        $page = (int) ($this->request->getGet('page') ?? 1);
        $perPage = 12;
        $start = ($page - 1) * $perPage;
        $totalData = count($filteredPlants);
        $plantsPaginated = array_slice($filteredPlants, $start, $perPage);

        // Jika request berasal dari AJAX, kembalikan response JSON
        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'plants'  => $plantsPaginated,
                'hasMore' => ($start + $perPage) < $totalData,
            ]);
        } else {
            // Untuk request biasa, tampilkan view dengan data yang difilter
            return view('tanaman/vegetable', [
                'title'       => 'Hasil Pencarian Sayuran',
                'plants'      => $plantsPaginated,
                'totalData'   => $totalData,
                'searchQuery' => $query,
            ]);
        }
    }


// ------------------------------------------------++BARENG++-----------------------------------------------
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
    public function tambah($kebunId){
        if (!session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }
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
    
        // Ambil data tanggal
        $tanggalMulai = $this->request->getPost('tanggal_mulai');
        $tanggalSelesai = $this->request->getPost('tanggal_selesai');
        
        // Pengecekan untuk tanggal mulai dan selesai tidak boleh dari masa lalu
        $today = date('Y-m-d');
        if ($tanggalMulai < $today || $tanggalSelesai < $today) {
            return redirect()->back()->withInput()->with('error', 'Tanggal mulai dan tanggal selesai tidak boleh dari masa lalu.');
        }
    
        // Pengecekan tanggal mulai tidak boleh lebih besar dari tanggal selesai
        if ($tanggalMulai > $tanggalSelesai) {
            return redirect()->back()->withInput()->with('error', 'Tanggal mulai tidak boleh lebih besar dari tanggal selesai.');
        }
    
        // Pengecekan tanggal selesai minimal 1 hari setelah tanggal mulai
        $date1 = new \DateTime($tanggalMulai);
        $date2 = new \DateTime($tanggalSelesai);        
        $interval = $date1->diff($date2);
        if ($interval->days < 1) {
            return redirect()->back()->withInput()->with('error', 'Tanggal selesai minimal 1 hari setelah tanggal mulai.');
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
        if (!session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $detailtanaman = $this->TanamanKebunModel->getTanamanDetailById($id_tanaman_kebun);
        if (!$detailtanaman) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Tanaman tidak ditemukan.");
        }

        // Konversi tanggal ke timestamp
        $tanggalMulai = strtotime(date('Y-m-d', strtotime($detailtanaman['tanggal_mulai'])));
        $tanggalSekarang = strtotime(date('Y-m-d')); 
        $tanggalSelesai = strtotime(date('Y-m-d', strtotime($detailtanaman['tanggal_selesai'])));

        // Menghitung jumlah total hari
        $jumlahHari = ($tanggalSelesai - $tanggalMulai) / (60 * 60 * 24);

        // Menghitung hari yang telah berlalu
        $hariYangBerjalan = ($tanggalSekarang - $tanggalMulai) / (60 * 60 * 24);

        // Menghitung progress hari & progress bar
        if ($tanggalSekarang < $tanggalMulai) {
            $progressHari = 0;
            $progresBar = 0;
        } elseif ($tanggalSekarang >= $tanggalSelesai) {
            $progressHari = $jumlahHari;
            $progresBar = 100;
        } else {
            $progressHari = $hariYangBerjalan;
            $progresBar = ($progressHari / $jumlahHari) * 100;
        }

        // Data untuk view
        $data = [
            'title' => 'Detail Tanaman',
            'tanaman' => $detailtanaman,
            'jumlahHari' => $jumlahHari,
            'progressHari' => $progressHari,
            'progressBar' => round($progresBar)
        ];
        return view('tanaman/Detail_Tanaman', $data);
    }

    
    public function edit($id)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }
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

        // Ambil data tanggal
        $tanggalMulai = $this->request->getPost('tanggal_mulai');
        $tanggalSelesai = $this->request->getPost('tanggal_selesai');
        
        // Pengecekan untuk tanggal mulai dan selesai tidak boleh dari masa lalu
        $today = date('Y-m-d');
        if ($tanggalMulai < $today || $tanggalSelesai < $today) {
            return redirect()->back()->withInput()->with('error', 'Tanggal mulai dan tanggal selesai tidak boleh dari masa lalu.');
        }
    
        // Pengecekan tanggal mulai tidak boleh lebih besar dari tanggal selesai
        if ($tanggalMulai > $tanggalSelesai) {
            return redirect()->back()->withInput()->with('error', 'Tanggal mulai tidak boleh lebih besar dari tanggal selesai.');
        }
    
        // Pengecekan tanggal selesai minimal 1 hari setelah tanggal mulai
        $date1 = new \DateTime($tanggalMulai);
        $date2 = new \DateTime($tanggalSelesai);        
        $interval = $date1->diff($date2);
        if ($interval->days < 1) {
            return redirect()->back()->withInput()->with('error', 'Tanggal selesai minimal 1 hari setelah tanggal mulai.');
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
    //===================================================================================//
    //============================= IMAGE PROCESSING ====================================//
    //===================================================================================//
    public function form_deteksi()
    {
        return view('tanaman/upload_gambar');
    }

    public function hasil()
    {
        $image = $this->request->getFile('gambar');

        if ($image->isValid() && !$image->hasMoved()) {
            $newName = $image->getRandomName();
            $image->move(ROOTPATH . 'public/uploads', $newName);

            $pythonFile = ROOTPATH . 'app/ThirdParty/PythonModel/predict.py';
            $imagePath = ROOTPATH . 'public/uploads/' . $newName;

            //pindahkeun kadieu
            // $command = 'python3 ' . escapeshellarg($pythonFile) . ' ' . escapeshellarg($imagePath) . ' 2> ' . escapeshellarg(ROOTPATH . 'writable/logs/error.log');
            $command = ' ' . escapeshellarg($pythonFile) . ' ' . escapeshellarg($imagePath) . ' 2> ' . escapeshellarg(ROOTPATH . 'writable/logs/error.log');

            $output = shell_exec($command);
            $log = file_get_contents(ROOTPATH . 'writable/logs/error.log');
            echo "<pre>$log</pre>"; 


            if (!$output) {
                return view('tanaman/hasil', [
                    'gambar' => '/uploads/' . $newName,
                    'label' => 'Tidak ada hasil',
                    'deskripsi' => 'Script Python tidak memberikan output atau error terjadi.'
                ]);
            }

            $result = json_decode($output, true);

            if (!$result) {
                $errorLogPath = ROOTPATH . 'writable/logs/error.log';
                $errorLogContent = file_exists($errorLogPath) ? file_get_contents($errorLogPath) : 'File error.log tidak ditemukan.';

                return view('tanaman/hasil', [
                    'gambar' => '/uploads/' . $newName,
                    'label' => 'Error',
                    'deskripsi' => 'Output script Python bukan JSON. Output mentah:<br>' . nl2br(htmlspecialchars($output)) .
                                '<br><br>Isi error.log:<br>' . nl2br(htmlspecialchars($errorLogContent))
                ]);
            }

            // Jika deskripsi berupa array, ubah menjadi string
            $deskripsi = $result['deskripsi'] ?? 'Tidak ada deskripsi';
            if (is_array($deskripsi)) {
                // Contoh format string: gabungkan semua key-value
                $temp = '';
                foreach ($deskripsi as $key => $val) {
                    $temp .= ucfirst(str_replace('_', ' ', $key)) . ": " . $val . "\n\n";
                }
                $deskripsi = trim($temp);
            }

            return view('tanaman/hasil', [
                'gambar' => '/uploads/' . $newName,
                'label' => $result['label'] ?? 'Tidak ada label',
                'deskripsi' => $deskripsi
            ]);
        } else {
            return view('tanaman/hasil', [
                'gambar' => null,
                'label' => 'Error',
                'deskripsi' => 'File gambar tidak valid atau gagal di-upload.'
            ]);
        }
    }
}