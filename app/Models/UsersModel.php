<?php

namespace App\Models;

use CodeIgniter\Model;

class UsersModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'user_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'username',
        'email',
        'password_hash',
        'first_name',
        'last_name',
        'role_id',
        'status_id',
        'last_login'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'username' => 'required|min_length[3]|max_length[100]|is_unique[users.username]',
        'email' => 'required|valid_email|max_length[255]|is_unique[users.email]',
        'password_hash' => 'required|min_length[8]',
        'first_name' => 'required|max_length[100]',
        'last_name' => 'required|max_length[100]',
        'role_id' => 'required|integer',
        'status' => 'required|in_list[active,inactive,banned]'
    ];
    protected $validationMessages   = [
        'username' => [
            'required' => 'Username wajib diisi.',
            'min_length' => 'Username harus terdiri dari setidaknya 3 karakter.',
            'is_unique' => 'Username sudah digunakan, pilih username yang lain.'
        ],
        'email' => [
            'required' => 'Email wajib diisi.',
            'valid_email' => 'Harap masukkan email yang valid.',
            'is_unique' => 'Email sudah digunakan, pilih email yang lain.'
        ],
        'password_hash' => [
            'required' => 'Password wajib diisi.',
            'min_length' => 'Password harus terdiri dari setidaknya 8 karakter.'
        ],
        'first_name' => [
            'required' => 'Nama depan wajib diisi.',
        ],
        'last_name' => [
            'required' => 'Nama belakang wajib diisi.',
        ],
        'role_id' => [
            'required' => 'Role ID wajib diisi.',
            'integer' => 'Role ID harus berupa angka.'
        ],
        'status' => [
            'required' => 'Status wajib diisi.',
            'in_list' => 'Status tidak valid.'
        ],
    ];

    public function saveUser($data)
    {
        // Hash password
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        return $this->save($data);
    }

    public function get_user($id)
    {
        return $this->where('user_id', $id)->first();
    }

    public function getUserData()
    {
        $user = $this->db->table('users')->where('user_id', session()->get('user_id'))->get()->getRowArray();
        extract($user);
        return [
            'username' => $username,
            // other data...
        ];
    }

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}
