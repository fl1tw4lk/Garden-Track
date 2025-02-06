<?php

namespace App\Models;

use CodeIgniter\Model;

class KebunModel extends Model
{
    protected $table = 'kebun';
    protected $primaryKey = 'id_kebun';
    protected $allowedFields = [
        'id_user',
        'id_tanaman',
        'nama_kebun', 
        'poto_kebun', 
        'status'
    ];

    public function getKebunData()
    {
        return $this->select('kebun.id_kebun, kebun.nama_kebun, kebun.poto_kebun, pengguna.id_user, pengguna.nama_users, pengguna.email, pengguna.profile, tanaman.common_name, tanaman_kebun.tanggal_mulai, tanaman_kebun.tanggal_selesai')
                    ->join('pengguna', 'pengguna.id_user = kebun.id_user', 'left')
                    ->join('tanaman_kebun', 'tanaman_kebun.id_kebun = kebun.id_kebun', 'left')
                    ->join('tanaman', 'tanaman.id_tanaman = tanaman_kebun.id_tanaman', 'left')
                    ->where('kebun.status', 'selesai')
                    ->orderBy('kebun.id_kebun', 'ASC')
                    ->findAll();
    }

    public function getKebunByUser($id_user)
    {

        $result = $this->select('pengguna.id_user, pengguna.nama_users, kebun.id_kebun, kebun.nama_kebun, kebun.poto_kebun, kebun.status')
                    ->join('pengguna', 'kebun.id_user = pengguna.id_user', 'left')
                    ->where([
                            'kebun.id_user' => $id_user,
                            'kebun.status' => 'selesai',
                        ])
                    ->findAll();
        return $result;
    }
}