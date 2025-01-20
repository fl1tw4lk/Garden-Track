<?php

namespace App\Models;

use CodeIgniter\Model;

class register_model extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'fullname',
        'email',
        'username', 
        'password',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation Rules
    protected $validationRules = [
        'fullname' => 'required|min_length[3]|max_length[100]',
        'email' => 'required|valid_email|is_unique[users.email]',
        'username' => 'required|min_length[3]|max_length[20]|is_unique[users.username]',
        'password' => 'required|min_length[6]'
    ];

    protected $validationMessages = [
        'fullname' => [
            'required' => 'Full name is required',
            'min_length' => 'Full name must have at least 3 characters',
            'max_length' => 'Full name cannot exceed 100 characters'
        ],
        'email' => [
            'required' => 'Email is required',
            'valid_email' => 'Please provide a valid email address',
            'is_unique' => 'This email is already registered'
        ],
        'username' => [
            'required' => 'Username is required',
            'min_length' => 'Username must have at least 3 characters',
            'max_length' => 'Username cannot exceed 20 characters',
            'is_unique' => 'This username is already taken'
        ],
        'password' => [
            'required' => 'Password is required',
            'min_length' => 'Password must have at least 6 characters'
        ]
    ];

    protected $skipValidation = false;
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    // Hash password before storing
    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }
        return $data;
    }
}
