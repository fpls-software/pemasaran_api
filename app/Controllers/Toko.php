<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use App\Controllers\BaseController;
use App\Models\TokoModel;

class Toko extends BaseController
{
    use ResponseTrait;
    public function index()
    {
        //
        $model = new TokoModel();
        $data['toko'] = $model->findAll();

        if($data['toko']) {
            return $this->respond($data);
        }
        else {
            return $this->failNotFound("Data kosong");
        }
    }
    public function show($id = null)
    {
        $model = new TokoModel();
        $data['toko'] = $model->where('id_toko', $id)->first();
        if($data['toko']) {
            return $this->respond($data);
        }
        else {
            return $this->failNotFound("Data tidak ditemukan");
        }
    }
    public function create()
    {
        $model  = new TokoModel();
        $validation = \Config\Services::Validation();
        $data   = [
            'id_toko'       => $this->request->getVar('id_toko'),
            'nm_toko'       => $this->request->getVar('nm_toko'),
            'alamat_toko'   => $this->request->getVar('alamat_toko'),  
            'no_hp'         => $this->request->getVar('no_hp'),
            'username'      => $this->request->getVar('username'),
            'password'      => md5($this->request->getVar('password')),
        ];
        $validate = $validation->run($data, 'toko_validationRules');
        $errors   = $validation->getErrors();
        if($errors) {
            return $this->fail($errors);
        }
        else {
            $model->insert($data);
            $response = [
                'status'    => 200,
                'error'     => null,
                'messages'  => [
                    'success' => 'Insert data berhasil'
                ]
            ];
            return $this->respond($response);
        }
    }
    public function update($id = null) {
        $model = new TokoModel();
       
        $data   = [
            'id_toko'       => $this->request->getVar('id_toko'),
            'nm_toko'       => $this->request->getVar('nm_toko'),
            'alamat_toko'   => $this->request->getVar('alamat_toko'),  
            'no_hp'         => $this->request->getVar('no_hp'),
            'username'      => $this->request->getVar('username')
        ];
        $find = $model->where('id_toko', $id)->first();
        if($find) {
            
                $model->update($id, $data);
                $response = [
                    'status'    => 200,
                    'error'     => null,
                    'messages'  => [
                        'success' => 'Update data berhasil'
                    ]
                ];
                return $this->respond($response);
            
        }
        else {
            return $this->failNotFound('Data tidak ditemukan');
        }
    }
    public function delete($id = null) {
        $model = new TokoModel();
        $delete = $model->where('id_toko', $id)->first();
        if($delete) {
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
}
