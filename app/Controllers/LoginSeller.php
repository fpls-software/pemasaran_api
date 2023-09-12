<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use App\Models\TokoModel;
use App\Controllers\BaseController;


class LoginSeller extends BaseController
{
    use ResponseTrait;
    public function index()
    {
        $session = session();
        $model = new TokoModel();
        $data = [
            'username' => $this->request->getVar('username'),
            'password' => $this->request->getVar('password'),
        ];
        $verifi = $model->where(array('username' => $data['username'], 'password' => md5($data['password'])))->first();
        if($verifi) {
            $session_data = [
                'username_toko' => $data['username'],
                'login'    => true
            ];
            $session->set($session_data);
            $response = [
                'status' => 200,
                'error' => null,
                'message' => [
                    'success' => 'Login Success'
                ]
            ];
            return $this->respond($response);
        }
        else {
            return $this->fail('Username atau Password salah');
        }
        
    }
    public function logout() {
        $session = session();
        $session->destroy();

        $response = [
            'status'    => 200,
            'error'     => null,
            'messages'  => [
                'success' => 'Logout berhasil'
            ]
        ];
        return $this->respond($response);
    }
}
