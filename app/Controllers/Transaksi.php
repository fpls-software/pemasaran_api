<?php
namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use App\Models\TransaksiModel;
use \App\Models\FinalTransaksiView;
use \App\Models\TransaksiViewModel;

use App\Controllers\BaseController;

class Transaksi extends BaseController
{
    use ResponseTrait;
    public function index()
    {
        $model = new TransaksiModel();
        $data['transaksi'] = $model->where('status', 'diterima')->orderBy('tgl_transaksi', 'DESC')->findAll();
        if($data) {
            return $this->respond($data);
        }               
        else {
            return $this->fail('Data Kosong');
        }
    }
    public function show($id = null) {
        $model = new TransaksiModel();
        $data['transaksi'] = $model->where('username', $id)->where('status', 'diterima')->orderBy('tgl_transaksi', 'DESC')->findAll();
        if(!empty($data['transaksi'])) {
            return $this->respond($data);
        }               
        else {
            return $this->fail('Data Kosong');
        }
    }

    public function notifikasi()
    {
        $model = new FinalTransaksiView();
        $data['transaksi'] = $model->where('status', 'pending')->orderBy('kd_transaksi', 'DESC')->findAll();
        if($data) {
            return $this->respond($data);
        }               
        else {
            return $this->fail('Data Kosong');
        }
    }
    public function getCurrentTransaction($kode) {
        $model = new TransaksiModel();
        $kode = $model->select('kd_transaksi')->orderBy('tgl_transaksi', 'DECS')->findAll(1);
        
        return $kode;
    }
    public function jmlnotifikasi() {
        $model = new FinalTransaksiView();
        $data['jml'] = $model->selectcount('kd_transaksi')->where('status', 'pending')->first();
        if($data) {
            return $this->respond($data);
        }               
        else {
            return $this->fail('Data Kosong');
        }
    }

    public function checkout() {
        $model = new TransaksiModel();
        $db = \Config\Database::connect();

        date_default_timezone_set('Asia/Bangkok');
        $tanggal = DATE("Y-m-d"); 
        $kodetanggal = DATE("Ymd");

        //get username
        $session = session();
        $username = [
            'userdata' => [
                'username' => $session->get('username')
            ]
        ];
        $user = $session->get('username');
        //

        //Initiate default kode
   
        //

        //get current code transaction
        $kode = $db->query("SELECT kd_transaksi, tgl_transaksi FROM tb_transaksi ORDER BY id_transaksi DESC LIMIT 1");
        foreach ($kode->getResult('array') as $row) {}
        //

        //Find todays transaction
        
    
        $today = $model->where('tgl_transaksi', $tanggal)->orderBy('id_transaksi', 'DESC')->first();

        
        if(!$today == null) {
            
            
            $index = substr($today['kd_transaksi'], 8);
            $getCode = str_pad(intval($index) + 1, strlen($index), '0', STR_PAD_LEFT);
            
            $data = $kodetanggal.$getCode;

            $transaksi = $db->query("
                INSERT INTO tb_transaksi (kd_transaksi, kd_produk, username, jml_beli, harga)
                SELECT '$data', kd_produk, username, jml_beli, harga FROM tb_keranjang
                WHERE username='$user'
            ");
            if($transaksi) {
                $response = [
                    'status'    => 200,
                    'error'     => null,
                    'messages'  => [
                        'success' => 'Checkout berhasil'
                    ]
                ];
                $db->query("DELETE FROM tb_keranjang WHERE username='$user'");

                return $this->respond($response);
            }
            else {
                return $this->fail('Checkout gagal');
            }
        

          
        }
        else {
            $data = $kodetanggal."0001";


            $transaksi = $db->query("
                INSERT INTO tb_transaksi (kd_transaksi, kd_produk, username, jml_beli, harga)
                SELECT '$data', kd_produk, username, jml_beli, harga FROM tb_keranjang
                WHERE username='$user'
            ");
            if($transaksi) {
                $response = [
                    'status'    => 200,
                    'error'     => null,
                    'messages'  => [
                        'success' => 'Checkout berhasil'
                    ]
                ];
                $db->query("DELETE FROM tb_keranjang WHERE username='$user'");

                return $this->respond($response);
            }
            else {
                return $this->fail('Checkout gagal');
            }
        }
        
        
        
        
    }
    public function invoice() {
        $model = new FinalTransaksiView();
        $session = session();
        $user = $session->get('username');

        $data['invoice'] = $model->where('username', $user)->orderBy('tgl_transaksi', 'DESC')->findAll();
        if(!empty($data['invoice'])) {
            return $this->respond($data);
        }
        else {
            return $this->fail('Anda belum memiliki pesanan');
        }
    }
    public function datatransaksi() {
        $model = new FinalTransaksiView();
        $session = session();
        $user = $session->get('username');

        $data['transaksi'] = $model->where('username', $user)->where('status', 'diterima')->orderBy('kd_transaksi', 'DESC')->findAll();
        if(!empty($data['transaksi'])) {
            return $this->respond($data);
        }
        else {
            return $this->fail('Anda belum memiliki transaksi');
        }
    }
    public function viewtransaksi($id = null) {
        $model = new TransaksiViewModel();
        $data['transaksi'] = $model->where('kd_transaksi', $id)->findAll();
        if($data['transaksi']) {
            return $this->respond($data);
        }
        else {
            return $this->fail('Data Kosong');
        }
    }
    public function terimatransaksi($id = null) {
        $model = new TransaksiModel();

        $data = [
            'status' => $this->request->getVar('status')
        ];
        $db = \Config\Database::connect();
        $update = $db->query("UPDATE tb_transaksi SET status='diterima' WHERE kd_transaksi='$id'");
        if($update) {
            $response = [
                'status' => 200,
                'error' => null,
                'messages' => [
                    'success' => 'Pesanan telah anda terima, silahkan proses pesanan tersebut!'
                ]
            ];
            return $this->respond($response);
        }
        else {
            return $this->fail('Error');
        }
    }
    public function tolaktransaksi($id = null) {
        $model = new TransaksiModel();

        $data = [
            'status' => $this->request->getVar('status')
        ];
        $db = \Config\Database::connect();
        $update = $db->query("UPDATE tb_transaksi SET status='ditolak' WHERE kd_transaksi='$id'");
        if($update) {
            $response = [
                'status' => 200,
                'error' => null,
                'messages' => [
                    'success' => 'Pesanan telah ditolak!'
                ]
            ];
            return $this->respond($response);
        }
        else {
            return $this->fail('Error');
        }
    }
}
