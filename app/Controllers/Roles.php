<?php

namespace App\Controllers;

use App\Models\RolesModel;
use App\Models\UsersModel;

class Roles extends BaseController
{
    protected $roles;
    protected $db;
    protected $session;
    protected $UsersModel;

    public function __construct()
    {
        $this->roles = new RolesModel();
        $this->db = \Config\Database::connect(); // Load the database instance
        $this->session = \Config\Services::session(); // Load the session instance
        $this->UsersModel = new UsersModel();
    }

    public function index()
    {
        // Ambil data pengguna dari session
        $userId = $this->session->get('user_id');
        $user = $this->UsersModel->find($userId);

        $roles = $this->roles->findAll();
        $data = [
            'roles' => $roles,
            'total_roles' => count($roles),
            'session' => $this->session,
            'username' => $user['username'], // Mengirimkan username ke view
        ];
        return view('roles/main', $data);
    }

    public function create()
    {
        return view('roles/action/create');
    }

    public function store()
    {
        $validation = \Config\Services::validation();

        $rules = [
            'role_name' => 'required|min_length[3]|max_length[100]|is_unique[roles.role_name]',
            'description' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $data = [
            'role_name' => $this->request->getPost('role_name'),
            'description' => $this->request->getPost('description'),
        ];

        if ($this->roles->insert($data) === false) {
            return redirect()->back()->withInput()->with('errors', $this->roles->errors());
        }

        return redirect()->to('/roles')->with('message', 'Role created successfully.');
    }

    public function edit($id)
    {
        $role = $this->roles->find($id);
        if (!$role) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('roles/edit', ['role' => $role]);
    }

    public function update($id)
    {
        $validation = \Config\Services::validation();

        $rules = [
            'role_name' => 'required|min_length[3]|max_length[100]',
            'description' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $data = [
            'role_name' => $this->request->getPost('role_name'),
            'description' => $this->request->getPost('description'),
        ];

        if ($this->roles->update($id, $data) === false) {
            return redirect()->back()->withInput()->with('errors', $this->roles->errors());
        }

        return redirect()->to('/roles')->with('message', 'Role updated successfully.');
    }

    public function delete($id)
    {
        if ($this->roles->delete($id) === false) {
            return redirect()->to('/roles')->with('errors', $this->roles->errors());
        }

        return redirect()->to('/roles')->with('message', 'Role deleted successfully.');
    }
}
