<?php

namespace App\Controllers;
use CodeIgniter\API\ResponseTrait;
use App\Models\ProdukModel;
use CodeIgniter\Files\File;

use App\Controllers\BaseController;

class Produk extends BaseController
{
    use ResponseTrait;
    public function index()
    {
        $session = session();
        $model = new ProdukModel();
        $data['produk'] = $model->orderBy('tgl_input', 'DESC')->findAll();
        $username = [
            'userdata' => [
                'username' => $session->get('username')
            ]
        ];
        if(!empty($data['produk'])) {
            return $this->respond($data);
        }
        else {
            return $this->failNotFound('Data kosong');
        }
    }
    public function show($id = null) {
        $model = new ProdukModel();
        $data['produk'] = $model->where('kd_produk', $id)->first();
        if(!empty($data['produk'])) {
            return $this->respond($data);
        }
        else {
            return $this->failNotFound('Data tidak ditemukan');
        }

    }
    public function cariproduk($id = null) {
        $model = new ProdukModel();
        $data['produk'] = $model->like('nm_produk', $id)->findAll();
        if(!empty($data['produk'])) {
            return $this->respond($data);
        }
        else {
            return $this->failNotFound('Data tidak ditemukan');
        }
    }
    public function create() {
        $model = new ProdukModel();
        $img = $this->request->getFile('photo_produk');
        $img->move(WRITEPATH . '../assets/productimg/');

        $imgdata = [
            'img_name' => $img->getClientName(),
            'file'  => $img->getClientMimeType()
        ];
        $dataimg = $imgdata['img_name'];
        
        $data = [
            'kd_produk'     => $this->request->getVar('kd_produk'),
            'nm_produk'     => $this->request->getVar('nm_produk'),
            'harga'         => $this->request->getVar('harga'),
            'satuan'        => $this->request->getVar('satuan'),
            'deskripsi'     => $this->request->getVar('deskripsi'),
            //'photo_produk'  => $dataimg
        ];
       
        $verifi = $model->where('kd_produk', $data['kd_produk'])->first();
        if(!$verifi) {
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
        else 
        {
             
            return $this->fail("Kode produk telah digunakan");
        }
        

    }
    public function update($id = null) {
        $model = new ProdukModel();
        $validation = \Config\Services::Validation();

        $finddata['produk'] = $model->where('kd_produk', $id)->first();
        if($finddata['produk']) {
            $data = [
                'kd_produk'     => $this->request->getVar('kd_produk'),
                'nm_produk'     => $this->request->getVar('nm_produk'),
                'harga'         => $this->request->getVar('harga'),
                'satuan'        => $this->request->getVar('satuan'),
                'deskripsi'     => $this->request->getVar('deskripsi'),
                'photo_produk'  => $this->request->getVar('photo_produk')
            ];
            $validate = $validation->run($data, 'produk_validationRules');
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
        $model = new ProdukModel();
        $finddata = $model->where('kd_produk', $id)->first();
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
    public function terbaru()
    {
        $session = session();
        $model = new ProdukModel();
        $data['produk'] = $model->orderBy('tgl_input', 'DESC')->findAll(3);
        $username = [
            'userdata' => [
                'username' => $session->get('username')
            ]
        ];
        if(!empty($data['produk'])) {
            return $this->respond($data);
        }
        else {
            return $this->failNotFound('Data kosong');
        }
    }
}
