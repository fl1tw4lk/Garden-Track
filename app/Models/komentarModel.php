<?php
namespace App\Models;
use CodeIgniter\Model;
class komentarModel extends Model
{
    protected $table = 'komentar';
    protected $primaryKey = 'id_komentar';
    protected $allowedFields = [
        'id_kebun',
        'id_user', 
        'induk_komentar_id', 
        'komentar', 
        'created_at'
    ];

    // Ambil semua komentar berdasarkan id_kebun
    // public function getKomentarByKebun($id_kebun)
    // {
    //     return $this->where('id_kebun', $id_kebun)
    //                 ->where('induk_komentar_id', null) // Hanya komentar utama
    //                 ->orderBy('created_at', 'ASC')
    //                 ->findAll();
    // }

    public function getKomentarByKebun($id_kebun)
    {
        return $this->select('komentar.*, pengguna.nama_users as nama_users')
            ->join('pengguna', 'pengguna.id_user = komentar.id_user')
            ->where('komentar.id_kebun', $id_kebun)
            ->orderBy('komentar.created_at', 'DESC')
            ->findAll();
    }

    // Ambil balasan komentar tertentu berdasarkan induk_komentar_id dan id_kebun
    public function getBalasanByKomentar($id_kebun, $induk_komentar_id)
    {
        return $this->where('id_kebun', $id_kebun)
                    ->where('induk_komentar_id', $induk_komentar_id)
                    ->orderBy('created_at', 'ASC')
                    ->findAll();
    }

    // Tambahkan komentar baru
    public function addKomentar($data)
    {
        return $this->insert($data);
    }

    // Hapus komentar utama dan balasannya
    public function deleteKomentar($id_komentar)
    {
        // Hapus semua balasan terkait
        $this->where('induk_komentar_id', $id_komentar)->delete();

        // Hapus komentar utama
        return $this->delete($id_komentar);
    }
}