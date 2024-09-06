<?php

namespace App\Controllers;

use App\Models\UsersModel;
use CodeIgniter\HTTP\Response;

class RegisterController extends BaseController
{
    protected $usersModel;

    public function __construct()
    {
        $this->usersModel = new UsersModel();
    }

    public function index()
    {
        $users = $this->usersModel->findAll();
        $data = [
            'users' => $users,
            'total_users' => count($users),
        ];
        return view('register', $data);
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
            'password_hash' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'role_id' => $this->request->getPost('role_id'),
            'status' => $this->request->getPost('status'),
        ];

        // Menyimpan data ke database
        $this->usersModel->insert($data);

        return redirect()->to('/login')->with('message', 'User created successfully.');
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

        // Jika password juga diubah, lakukan hashing pada password
        if ($this->request->getPost('password')) {
            $data['password_hash'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
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
