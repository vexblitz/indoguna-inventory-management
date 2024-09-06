<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class SetUsername
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = \Config\Services::session();
        $request->username = $session->get('username');
        return $request;
    }
}
