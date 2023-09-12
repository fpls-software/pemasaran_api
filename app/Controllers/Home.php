<?php

namespace App\Controllers;
use CodeIgniter\API\ResponseTrait;
use App\Models\TransaksiViewModel;
use App\Models\ProdukTerlaris;
use App\Models\PelangganModel;

class Home extends BaseController
{
    use ResponseTrait;
    public function harian()
    {
        $date = DATE("Y-m-d");
        $model = new TransaksiViewModel();

        $data['penjualan'] = $model->select("SUM(ttl_harga) as total")->where('tgl_transaksi', $date)->where('status', 'diterima')->first();
        if($data['penjualan']) {
            return $this->respond($data);
        }
        else {
          
           return $this->fail('Data Kosong');
        }

       
    }
    public function bulanan() {
        $bulan = DATE('m');
        $tahun = DATE('Y');
        $model = new TransaksiViewModel();

        $data['penjualan'] = $model->select("SUM(ttl_harga) as total")->where('bulan', $bulan)->where('tahun', $tahun)->where('status', 'diterima')->first();
        if($data['penjualan']) {
            return $this->respond($data);
        }
        else {
          
           return $this->fail('Data Kosong');
        }
    }
    public function tahunan() {
        $tahun = DATE('Y');
        $model = new TransaksiViewModel();

        $data['penjualan'] = $model->select("SUM(ttl_harga) as total")->where('tahun', $tahun)->where('status', 'diterima')->first();
        if($data['penjualan']) {
            return $this->respond($data);
        }
        else {
           return $this->fail('Data Kosong');
        }

    }
    public function terlaris() {
        $model = new ProdukTerlaris();
        $data['terlaris'] = $model->orderBy('total_penjualan', 'DESC')->findAll(3); 
        $session = session();
        $username = [
            'userdata' => [
                'username' => $session->get('username')
            ]
        ];

        if($data['terlaris']){
            return $this->respond($data);
        }
        else {
            return $this->fail('Data Kosong');
         }
    }
    public function getuser() {
        $model = new PelangganModel();
        $session = session();
        $username = [
            'userdata' => [
                'username' => $session->get('username')
            ]
        ];
        $data['user'] = $model->select('username')->where('username', $username['userdata'])->first();

        return $this->respond($data);
    }
    
}
