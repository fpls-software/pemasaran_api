<?php

namespace App\Controllers;
use CodeIgniter\API\ResponseTrait;
use App\Controllers\BaseController;
use App\Models\KeranjangModel;
use App\Models\KeranjangViewModel;

class Keranjang extends BaseController
{
    use ResponseTrait;

    public function index()
    {
        //
        
        $model = new KeranjangModel();
        $data['keranjang'] = $model->findAll();

        if($data['keranjang']) {
            return $this->respond($data);
        }
        else {
            return $this->failNotFound('Data Kosong');
        }

    }
    public function create()
    {
        $model = new KeranjangModel();
        $validation = \Config\Services::Validation();

        $data = [
            'kd_produk' => $this->request->getVar('kd_produk'),
            'username'  => $this->request->getVar('username'),
            'jml_beli'  => $this->request->getVar('jml_beli'),
            'harga'     => $this->request->getVar('harga')
        ];
        $validate = $validation->run($data, 'keranjang_validationRules');
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
                    'success' => 'Item telah ditambahkan ke keranjang'
                ]
            ];
            return $this->respond($response);
        }
    }
    public function show($id = null)
    {
        $model = new KeranjangViewModel();
        $data['keranjang']= $model->where('username', $id)->findAll();
        if($data['keranjang']) {
            return $this->respond($data);
        }
        else {
            return $this->failNotFound('Keranjang kosong');
        }
    }

    public function delete($id = null)
    {
        $model = new KeranjangModel();
        $find = $model->where('kd_produk', $id)->first();
        if($find) {
            $model->where('kd_produk', $id)->delete();
            $response = [
                'status'    => 200,
                'error'     => null,
                'messages'  => [
                    'success' => 'Produk dihapus dari keranjang'
                ]
            ];
            return $this->respond($response);
        }
        else {
            return $this->failNotFound('Tidak dapat menghapus produk');
        }
    }
    public function jmlitem() {
        $modal = new KeranjangModel();
        $session = session();
        $username = [
            'userdata' => [
                'username' => $session->get('username')
            ]
        ];
        $data['jml'] = $modal->select("COUNT(kd_produk) as jml_item")->where('username', $username['userdata'])->first();

        return $this->respond($data);
    }

    public function nilaikeranjang() {
        $model = new KeranjangViewModel();
        $session = session();
        $username = [
            'userdata' => [
                'username' => $session->get('username')
            ]
        ];
        $data['jml'] = $model->selectSum('total_harga')->where('username', $username['userdata'])->first();
        if($data['jml'] == null) {
            return $this->fail('Tidak ada data');
        }
        else {
            return $this->respond($data);
           
        }
        
    }
}
