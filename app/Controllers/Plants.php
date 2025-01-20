<?php
namespace App\Controllers;

class Plants extends BaseController
{
    

    // api jek
    private $apiToken;
    private $baseUrl = 'https://trefle.io/api/v1';

    public function __construct(){
        $this->apiToken = env('TOKEN');
    }

    public function index()
    {
        $client = \Config\Services::curlrequest();

        // Ambil halaman saat ini dari parameter URL, default 1
        $currentPage = (int) $this->request->getGet('page') ?? 1;
        if ($currentPage < 1) {
            $currentPage = 1;
        }

        // API Endpoint untuk mengambil data tanaman sayuran
        $response = $client->get("{$this->baseUrl}/plants", [
            'query' => [
                'filter[vegetable]' => 'true',
                'token' => $this->apiToken,
                'page' => $currentPage
            ]
        ]);

        if ($response->getStatusCode() === 200) {
            $data = json_decode($response->getBody(), true);
            return view('plants', [
                'plants' => $data['data'],
                'currentPage' => $currentPage,
                'totalPages' => $data['meta']['total_pages'] ?? 1
            ]);
        } else {
            // Log error atau tampilkan pesan error
            log_message('error', 'Gagal mengambil data dari Trefle API: ' . $response->getBody());
            return view('plants', [
                'plants' => [],
                'currentPage' => $currentPage,
                'totalPages' => 1,
                'error' => 'Gagal mengambil data dari Trefle API. Silakan coba lagi nanti.'
            ]);
        }
    }
}
