<?php

namespace App\Controllers;
use CodeIgniter\API\ResponseTrait;
use App\Models\PelangganModel;
use App\Controllers\BaseController;

class Pelanggan extends BaseController
{
    use ResponseTrait;
    public function index()
    {
        $model = new PelangganModel();
        $session = session();
        $username = [
            'userdata' => [
                'username' => $session->get('username')
            ]
        ];        
        $data['pelanggan'] = $model->where('username', $username['userdata'])->findAll();
        if(!empty($data['pelanggan'])) {
            return $this->respond($data);
        }
        else {
            return $this->failNotFound('Data kosong');
        }
    }
    public function show($id = null) {
        $model = new PelangganModel();
        $data['pelanggan'] = $model->where('username', $id)->first();
        if(!empty($data['pelanggan'])) {
            return $this->respond($data);
        }
        else {
            return $this->failNotFound('Data tidak ditemukan');
        }
    }
    public function create() {
        $model = new PelangganModel();
        $validation = \Config\Services::Validation();

        $data = [
            'username'      => $this->request->getVar('username'),
            'nm_pelanggan'  => $this->request->getVar('nm_pelanggan'),
            'alamat'        => $this->request->getVar('alamat'),
            'no_hp'         => $this->request->getVar('no_hp'),
            'password'      => md5($this->request->getVar('password')),
        ];
        $validate = $validation->run($data, 'pelanggan_validationRules');
        $errors   = $validation->getErrors();
        if($errors) {
            return $this->fail($errors);
        }
        else {
            $checkUsername = $model->where('username', $data['username'])->first();
            if($checkUsername) {
                $response = [
                    'status'    => 400,
                    'error'     => null,
                    'messages'  => [
                        'failed' => 'Username telah terdaftar, silahkan gunakan username lain!'
                    ]
                ];
                return $this->respond($response); 
            }
            else {
                $model->insert($data);
                $response = [
                    'status'    => 200,
                    'error'     => null,
                    'messages'  => [
                        'success' => 'Registrasi berhasil, silahkan login!'
                    ]
                ];
                return $this->respond($response); 
            }
        }
    }
    public function update($id = null) {
        $model = new PelangganModel();
        $validation = \Config\Services::Validation();

        $finddata = $model->where('username', $id)->first();
        if($finddata) {
            $data = [
                'username'      => $this->request->getVar('username'),
                'nm_pelanggan'  => $this->request->getVar('nm_pelanggan'),
                'alamat'        => $this->request->getVar('alamat'),
                'no_hp'         => $this->request->getVar('no_hp'),
            ];
            
                $model->update($id, $data);
                $response = [
                    'status'    => 200,
                    'error'     => null,
                    'messages'  => [
                        'success' => 'Profile berhasil diperbarui'
                    ]
                ];
                return $this->respond($response);
            
        }
        else {
            return $this->fail('Data tidak ditemukan');
        }
       
    }
    public function delete($id = null) {
        $model = new PelangganModel();
        $finddata = $model->where('username', $id)->first();
        if($finddata) {
            $model->delete($id);
            $response = [
                'status'    => 200,
                'error'     => null,
                'messages'  => [
                    'success' => 'Hapus data berhasil'
                ]
            ];
            return $this->respond($response);
        }
        else {
            return $this->failNotFound('Data tidak ditemukan');
        }
    }
    public function ubahpassword($id = null) {
        $model = new PelangganModel();
        $data = [
            'password'      => md5($this->request->getVar('password')),
        ];
        
        $update = $model->update($id, $data);
        if($update) {
            $response = [
                'status'    => 200,
                'error'     => null,
                'messages'  => [
                    'success' => 'Password diperbarui, silahkan login kembali!'
                ]
            ];
            return $this->respond($response);
        }
        else {
            return $this->fail('Tidak dapat memperbarui password');
        }
    }
}
