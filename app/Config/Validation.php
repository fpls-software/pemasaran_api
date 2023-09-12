<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Validation\StrictRules\CreditCardRules;
use CodeIgniter\Validation\StrictRules\FileRules;
use CodeIgniter\Validation\StrictRules\FormatRules;
use CodeIgniter\Validation\StrictRules\Rules;

class Validation extends BaseConfig
{
    // --------------------------------------------------------------------
    // Setup
    // --------------------------------------------------------------------

    /**
     * Stores the classes that contain the
     * rules that are available.
     *
     * @var string[]
     */
    public array $ruleSets = [
        Rules::class,
        FormatRules::class,
        FileRules::class,
        CreditCardRules::class,
    ];

    /**
     * Specifies the views that are used to display the
     * errors.
     *
     * @var array<string, string>
     */
    public array $templates = [
        'list'   => 'CodeIgniter\Validation\Views\list',
        'single' => 'CodeIgniter\Validation\Views\single',
    ];

    // --------------------------------------------------------------------
    // Rules
    // --------------------------------------------------------------------

    public $toko_validationRules = [
        'id_toko' => [
            'label' => 'ID Toko',
            'rules' => 'required'
        ],
        'nm_toko' => [
            'label' => 'Nama Toko',
            'rules' => 'required'
        ],
        'alamat_toko' => [
            'label' => 'Alamat Toko',
            'rules' => 'required'
        ],
        'no_hp' => [
            'label' => 'No Kontak',
            'rules' => 'required'
        ],
        'username' => [
            'label' => 'Username',
            'rules' => 'required'
        ],
        'password' => [
            'label' => 'Kata Sandi',
            'rules' => 'required'
        ]
    ];
    public $rekening_validationRules = [
        'no_rekening' => [
            'label' => 'No Rekening',
            'rules' => 'required'
        ],
        'atas_nama' => [
            'label' => 'Atas Nama',
            'rules' => 'required'
        ],
        'bank' => [
            'label' => 'Bank',
            'rules' => 'required'
        ]
    ];
    public $produk_validationRules = [
        'kd_produk' => [
            'label' => 'Kode Produk',
            'rules' => 'required'
        ],
        'nm_produk' => [
            'label' => 'Nama Produk',
            'rules' => 'required'
        ],
        'harga' => [
            'label' => 'Harga',
            'rules' => 'required'
        ],
        'satuan' => [
            'label' => 'Satuan',
            'rules' => 'required'
        ],

        'photo_produk' => [
            'label' => 'Photo Produk',
            'rules' => 'required'
        ]
    ];
    public $pelanggan_validationRules = [
        'username' => [
            'label' => 'Username',
            'rules' => 'required'
        ],
        'nm_pelanggan' => [
            'label' => 'Nama Pelanggan',
            'rules' => 'required'
        ],
        'alamat' => [
            'label' => 'Alamat',
            'rules' => 'required'
        ],
        'no_hp' => [
            'label' => 'No. HP',
            'rules' => 'required'
        ],
        'password' => [
            'label' => 'Password',
            'rules' => 'required'
        ],
    ];
    public $keranjang_validationRules = [
        'kd_produk' => [
            'label' => 'Kode Produk',
            'rules' => 'required'
        ],
        'username' => [
            'label' => 'Username',
            'rules' => 'required'
        ],
        'jml_beli' => [
            'label' => 'Jumlah Beli',
            'rules' => 'required'
        ],
        'harga' => [
            'label' => 'Harga',
            'rules' => 'required'
        ]
    ];

    
}
