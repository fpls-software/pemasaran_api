<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\RekeningModel;

class Rekening extends BaseController
{
    use ResponseTrait;

    public function index()
    {
        //
        $model = new RekeningModel();
        //$validation = \Config\Services::Validation();
        $data['rekening'] = $model->findAll();
        if($data['rekening']){
            return $this->respond($data);
        }
        else {
            return $this->failNotFound('Data kosong');
        }
    }
    public function show($id = null) {
        $model = new RekeningModel();
        $data['rekening'] = $model->where('id', $id)->first();
        if($data['rekening']){
            return $this->respond($data);
        }
        else {
            return $this->failNotFound('Data tidak ditemukan');
        }
        
    }
    public function create() {
        $model = new RekeningModel();
        $validation = \Config\Services::Validation();

        $data = [
            'no_rekening'   => $this->request->getVar('no_rekening'),
            'atas_nama'     => $this->request->getVar('atas_nama'),
            'bank'          => $this->request->getVar('bank')
        ];
        $validate = $validation->run($data, 'rekening_validationRules');
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
        $model = new RekeningModel();
        $validation = \Config\Services::Validation();

        $data = [
            'no_rekening'   => $this->request->getVar('no_rekening'),
            'atas_nama'     => $this->request->getVar('atas_nama'),
            'bank'          => $this->request->getVar('bank')
        ];

        $find = $model->where('id', $id)->first();
        if($find) {
            $validate = $validation->run($data, 'rekening_validationRules');
            $errors   = $validation->getErrors();
            if($errors) {
                return $this->fail($errors);
            }
            else {
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
        }
        else {
            return $this->failNotFound('Data tidak ditemukan');
        }
    }
    public function delete($id = null) {
        $model = new RekeningModel();
        $delete = $model->where('id', $id)->first();
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
