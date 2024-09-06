<?php

namespace App\Controllers;

use App\Models\PermissionsModel;
use App\Models\UsersModel;
use CodeIgniter\HTTP\Response;

class Permissions extends BaseController
{
    protected $permissions;
    protected $UsersModel;
    protected $db;
    protected $session;

    public function __construct()
    {
        $this->permissions = new PermissionsModel();
        $this->UsersModel = new UsersModel();
        $this->db = \Config\Database::connect(); // Load the database instance
        $this->session = \Config\Services::session(); // Load the session instance
    }

    public function index()
    {
        // Ambil data pengguna dari session
        $userId = $this->session->get('user_id');
        $user = $this->UsersModel->find($userId);
        $permissions = $this->permissions->findAll();
        $data = [
            'permissions' => $permissions,
            'total_permissions' => count($permissions),
            'session' => $this->session,
            'username' => $user['username'], // Mengirimkan username ke view
        ];

        return view('permissions/main', $data);
    }

    public function create()
    {
        return view('permissions/action/create');
    }

    public function store()
    {
        // Validasi input
        $validation = \Config\Services::validation();

        $rules = [
            'permission_name' => 'required|min_length[5]|max_length[100]',
            'description' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Data valid, simpan ke database
        $data = [
            'permission_name' => $this->request->getPost('permission_name'),
            'description' => $this->request->getPost('description'),
        ];

        $this->permissions->insert($data);

        return redirect()->to('/permissions')->with('message', 'Permission created successfully.');
    }

    public function edit($id)
    {
        $data['permission'] = $this->permissions->find($id);
        if (!$data['permission']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('permissions/edit', $data);
    }

    public function update($id)
    {
        // Validasi input
        $validation = \Config\Services::validation();

        $rules = [
            'permission_name' => 'required|min_length[5]|max_length[100]',
            'description' => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Data valid, perbarui data di database
        $data = [
            'permission_name' => $this->request->getPost('permission_name'),
            'description' => $this->request->getPost('description'),
        ];

        $this->permissions->update($id, $data);

        return redirect()->to('/permissions')->with('message', 'Permission updated successfully.');
    }

    public function delete($id)
    {
        $this->permissions->delete($id);
        return redirect()->to('/permissions')->with('message', 'Permission deleted successfully.');
    }
}
