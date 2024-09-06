<?php

namespace App\Controllers;

use App\Models\UsersModel;
use App\Models\RolesModel;

class UserController extends BaseController
{
    protected $usersModel;
    protected $rolesModel;
    protected $session;
    protected $UsersModel;
    protected $db;

    public function __construct()
    {
        $this->usersModel = new UsersModel();
        $this->rolesModel = new RolesModel();
        $this->session = \Config\Services::session(); // Load the session instance
        $this->UsersModel = new UsersModel();
    }

    public function index()
    {
        $users = $this->usersModel->findAll();
        $roles = [];

        foreach ($users as $user) {
            $role = $this->rolesModel->find($user['role_id']);
            $roles[$user['role_id']] = $role;
        }
        // Ambil data pengguna dari session
        $userId = $this->session->get('user_id');
        $user = $this->UsersModel->find($userId);
        $data = [
            'users' => $users,
            'roles' => $roles,
            'total_users' => count($users),
            'session' => $this->session,
            'username' => $user['username'], // Mengirimkan username ke view
        ];
        return view('users/index', $data);
    }

    public function create()
    {
        return view('users/create');
    }

    public function store()
    {
        $validation = \Config\Services::validation();

        // Aturan validasi
        $rules = [
            'username' => 'required|min_length[3]|max_length[100]|is_unique[users.username]',
            'email' => 'required|valid_email|max_length[255]|is_unique[users.email]',
            'password' => 'required|min_length[8]',
            'first_name' => 'required|max_length[100]',
            'last_name' => 'required|max_length[100]',
            'role_id' => 'required|integer',
            'status' => 'required|in_list[active,inactive,banned]'
        ];

        // Menjalankan validasi
        if ($validation->withRequest($this->request)->setRules($rules)->run() === FALSE) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Data yang akan disimpan
        $data = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password_hash' => password_hash((string) $this->request->getPost('password'), PASSWORD_DEFAULT),
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'role_id' => $this->request->getPost('role_id'),
            'status' => $this->request->getPost('status'),
        ];

        // Periksa apakah data yang diambil adalah string, jika tidak, berikan penanganan yang sesuai
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = implode(', ', $value); // Contoh penanganan untuk array, bisa disesuaikan
            } elseif (is_null($value)) {
                $data[$key] = ''; // Atau nilai default lainnya
            }
        }

        // Menyimpan data ke database
        $this->usersModel->insert($data);

        return redirect()->to('/users')->with('message', 'User created successfully.');
    }

    public function edit($id)
    {
        $user = $this->usersModel->find($id);
        if (!$user) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'user' => $user,
        ];

        return view('users/edit', $data);
    }

    public function update($id)
    {
        $validation = \Config\Services::validation();

        // Aturan validasi
        $rules = [
            'username' => 'required|min_length[3]|max_length[100]',
            'email' => 'required|valid_email|max_length[255]',
            'first_name' => 'required|max_length[100]',
            'last_name' => 'required|max_length[100]',
            'role_id' => 'required|integer',
            'status' => 'required|in_list[active,inactive,banned]'
        ];

        // Jika password juga diubah, tambahkan aturan validasi untuk password
        if ($this->request->getPost('password')) {
            $rules['password'] = 'required|min_length[8]';
        }

        // Menjalankan validasi
        if ($validation->withRequest($this->request)->setRules($rules)->run() === FALSE) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Data yang akan diperbarui
        $data = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'role_id' => $this->request->getPost('role_id'),
            'status' => $this->request->getPost('status'),
        ];

        // Periksa apakah data yang diambil adalah string, jika tidak, berikan penanganan yang sesuai
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = implode(', ', $value); // Contoh penanganan untuk array, bisa disesuaikan
            } elseif (is_null($value)) {
                $data[$key] = ''; // Atau nilai default lainnya
            }
        }

        // Jika password juga diubah, lakukan hashing pada password
        if ($this->request->getPost('password')) {
            $data['password_hash'] = password_hash((string) $this->request->getPost('password'), PASSWORD_DEFAULT);
        }

        // Memperbarui data di database
        $this->usersModel->update($id, $data);

        return redirect()->to('/users')->with('message', 'User updated successfully.');
    }

    public function delete($id)
    {
        $this->usersModel->delete($id);
        return redirect()->to('/users')->with('message', 'User deleted successfully.');
    }
}
