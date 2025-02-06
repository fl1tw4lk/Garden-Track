<?php

namespace App\Models;

use CodeIgniter\Model;

class PlantModel extends Model
{
    protected $table = 'tanaman';
    protected $primaryKey = 'id_tanaman';
    protected $allowedFields = ['trefle_id', 'common_name', 'scientific_name','image_url', 'family', 'genus'];

    
}