<?php

namespace App\Controllers;

use App\Models\UsersModel;
use CodeIgniter\Controller;

class AuthController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function login()
    {
        $session = session();
        $model = new UsersModel();

        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password_hash');

        $user = $model->where('email', $email)->first();

        if ($user) {
            $pass = $user['password_hash'];
            $authPassword = password_verify($password, $pass);
            if ($authPassword) {
                $ses_data = [
                    'user_id' => $user['user_id'],
                    'email' => $user['email'],
                    'role_id' => $user['role_id'],
                    'isLoggedIn' => TRUE
                ];
                $session->set($ses_data);

                // Redirect sesuai role_id
                return $this->redirectUserBasedOnRole($user['role_id']);
            } else {
                $session->setFlashdata('msg', 'Wrong Password');
                return redirect()->to('/login');
            }
        } else {
            $session->setFlashdata('msg', 'Username not Found');
            return redirect()->to('/login');
        }
    }

    private function redirectUserBasedOnRole($role)
    {
        switch ($role) {
            case 1: // Administrator
                return redirect()->to('dashboard');
            case 2: // Manager
                return redirect()->to('dashboard');
            case 3: // Staff Gudang
                return redirect()->to('dashboard');
            case 4: // Kepala Gudang
                return redirect()->to('dashboard');
            default:
                return redirect()->to('/login');
        }
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('/login');
    }
}
