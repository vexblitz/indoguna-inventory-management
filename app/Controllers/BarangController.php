<?php

namespace App\Controllers;

use App\Models\BarangModel;
use App\Models\SatuanBarangModel;
use App\Models\KategoriBarangModel;
use App\Controllers\BaseController;
use App\Models\UsersModel;
use Dompdf\Dompdf;

class BarangController extends BaseController
{
    protected $BarangModel;
    protected $SatuanBarangModel;
    protected $KategoriBarangModel;
    protected $db;
    protected $session;
    protected $UsersModel;

    public function __construct()
    {
        $this->BarangModel = new BarangModel();
        $this->SatuanBarangModel = new SatuanBarangModel();
        $this->KategoriBarangModel = new KategoriBarangModel();
        $this->db = \Config\Database::connect(); // Load the database instance
        $this->session = \Config\Services::session(); // Load the session instance
        $this->UsersModel = new UsersModel();
    }

    // Menampilkan daftar laporan barang
    public function index()
    {
        $data['satuan_barang'] = $this->SatuanBarangModel->findAll();
        $data['kategori_barang'] = $this->KategoriBarangModel->findAll();

        $data['barang_control'] = array_map(function ($barang) {
            $barang['harga_jual'] = number_format($barang['harga_jual'], 0, ',', '.');
            $barang['harga_beli'] = number_format($barang['harga_beli'], 0, ',', '.');
            return $barang;
        }, $this->BarangModel->findAll());

        // Mengambil semua laporan barang
        $barang_control = $this->BarangModel->findAll();

        // Mengambil semua satuan barang
        $satuan_barang = $this->SatuanBarangModel->findAll();

        // Membuat array untuk lookup nama satuan berdasarkan ID
        $satuan_lookup = [];
        foreach ($satuan_barang as $satuan) {
            $satuan_lookup[$satuan['id']] = $satuan['nama_satuan'];
        }

        // Mengambil semua kategori barang
        $kategori_barang = $this->KategoriBarangModel->findAll();

        // Membuat array untuk lookup nama satuan berdasarkan ID
        $kategori_lookup = [];
        foreach ($kategori_barang as $kategori) {
            $kategori_lookup[$kategori['id']] = $kategori['nama_kategori'];
        }

        // Menggabungkan data laporan barang dengan nama satuan
        foreach ($barang_control as &$barang) {
            $barang['nama_satuan'] = isset($satuan_lookup[$barang['jenis_satuan']]) ? $satuan_lookup[$barang['jenis_satuan']] : 'Tidak Ditemukan';
            $barang['nama_kategori'] = isset($kategori_lookup[$barang['kategori_barang_id']]) ? $kategori_lookup[$barang['kategori_barang_id']] : 'Tidak Ditemukan';
        }
        $data['barang_control'] = $barang_control;


        // Ambil data pengguna dari session
        $userId = $this->session->get('user_id');
        $user = $this->UsersModel->find($userId);

        $data = [
            'barang_control' =>  $barang_control,
            'session' => $this->session,
            'username' => $user['username'], // Mengirimkan username ke view
            'total_Barang' => count($barang_control),
        ];
        return view('barang/layouts/main', $data);
    }

    // Menampilkan form untuk membuat laporan barang baru
    public function create()
    {
        $BarangModel = new BarangModel();
        $jenisBarang = $BarangModel->getJenisBarang();
        $kategoriBarang = $BarangModel->getJenisKategori();

        $lastBarang = $this->BarangModel->orderBy('kode_barang', 'DESC')->first();
        $lastKode = $lastBarang ? $lastBarang['kode_barang'] : null;
        $nextKode = $this->generateKodeBarang($lastKode);

        return view('barang/components/action/create', [
            'nextKode' => $nextKode,
            'barang_control' => $this->BarangModel->findAll(),
            'jenisBarang' => $jenisBarang,
            'kategoriBarang' => $kategoriBarang,
        ]);

        // Setelah menambah data baru, reset kembali nomor urut untuk memastikan keteraturan
        $this->resetAutoIncrement();
    }

    private function generateKodeBarang($lastKode)
    {
        if ($lastKode) {
            $number = (int) substr($lastKode, 3) + 1;
            return 'BRG' . str_pad($number, 4, '0', STR_PAD_LEFT);
        } else {
            return 'BRG0001';
        }
    }

    public function store()
    {

        // Validasi input dari form
        $validation = $this->validate([
            'nama_barang' => 'required|min_length[3]',
            'harga_beli' => 'required',
            'harga_jual' => 'required',
            'jenis_satuan' => 'required',
            'kategori_barang_id' => 'required',
            'stok_awal' => 'required',
            'stok_akhir' => 'required'
        ]);

        if (!$validation) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $lastBarang = $this->BarangModel->orderBy('kode_barang', 'DESC')->first();
        $lastKode = $lastBarang ? $lastBarang['kode_barang'] : null;
        $kodeBarang = $this->generateKodeBarang($lastKode);

        $data = [
            'kode_barang' => $kodeBarang,
            'nama_barang' => $this->request->getPost('nama_barang'),
            'harga_beli' => $this->request->getPost('harga_beli'),
            'harga_jual' => $this->request->getPost('harga_jual'),
            'kategori_barang_id' => $this->request->getPost('kategori_barang_id'),
            'jenis_satuan' => $this->request->getPost('jenis_satuan'),
            'stok_awal' => $this->request->getPost('stok_awal'),
            'stok_akhir' => $this->request->getPost('stok_akhir'),
        ];

        // Menyimpan data ke database
        $this->BarangModel->save($data);

        return redirect()->to(base_url('data-barang'))->with('success', 'Data successfully created.');
    }

    public function detail($id = null)
    {
        $barangModel = new BarangModel();
        $barang = $barangModel->find($id); // Mengambil data barang berdasarkan ID

        $BarangModel = new BarangModel();
        $jenisBarang = $BarangModel->getJenisBarang();

        $kategoriBarang = $BarangModel->getJenisKategori();
        $satuanBarangModel = new SatuanBarangModel();
        $kategoriBarangModel = new KategoriBarangModel();
        // Periksa apakah ID diberikan
        if ($id === null) {
            return redirect()->back()->with('error', 'ID tidak ditemukan.');
        }

        // Ambil data satuan berdasarkan ID
        $satuan = $satuanBarangModel->find($id);
        $kategori = $kategoriBarangModel->find($id);

        // Jika data tidak ditemukan, berikan pesan kesalahan
        if (!$satuan) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        // Kirim data ke view
        $data = [
            'satuan' => $satuan,
            'jenisBarang' => $jenisBarang,
            'barang' => $barang,
            'kategori' => $kategori,
            'kategoriBarang' => $kategoriBarang,
        ];

        return view('barang/components/action/detail', $data);
    }

    // Menampilkan form untuk mengedit barang
    public function edit($id)
    {
        $barang = $this->BarangModel->find($id);

        // Jika barang tidak ditemukan, redirect dengan pesan error
        if (!$barang) {
            session()->setFlashdata('error', 'Data barang tidak ditemukan.');
            return redirect()->to(base_url('data-barang'));
        }

        $jenisBarang = $this->SatuanBarangModel->findAll();
        $kategoriBarang = $this->KategoriBarangModel->findAll();

        return view('barang/components/action/edit', [
            'barang' => $barang,
            'jenisBarang' => $jenisBarang,
            'kategoriBarang' => $kategoriBarang,
        ]);
    }


    // Memproses pembaruan data barang
    public function update($id)
    {
        // Validasi input dari form
        if (!$this->validate([
            'nama_barang' => 'required|min_length[3]',
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'jenis_satuan' => 'required',
            'kategori_barang_id' => 'required',
            'stok_awal' => 'required|numeric',
            'stok_akhir' => 'required|numeric',
        ])) {
            // Mengambil objek validasi
            $validation = \Config\Services::validation();
            return redirect()->back()->withInput()->with('validation', $validation);
        }

        $data = [
            'nama_barang' => $this->request->getPost('nama_barang'),
            'harga_beli' => $this->request->getPost('harga_beli'),
            'harga_jual' => $this->request->getPost('harga_jual'),
            'jenis_satuan' => $this->request->getPost('jenis_satuan'),
            'kategori_barang_id' => $this->request->getPost('kategori_barang_id'),
            'stok_awal' => $this->request->getPost('stok_awal'),
            'stok_akhir' => $this->request->getPost('stok_akhir'),
        ];

        // Debug statement (optional, untuk memeriksa data)
        log_message('debug', 'Data to be updated: ' . print_r($data, true));

        // Melakukan pembaruan data
        $this->BarangModel->update($id, $data);

        // Mengatur pesan flash untuk notifikasi
        session()->setFlashdata('message', 'Data berhasil diupdate.');
        return redirect()->to(base_url('data-barang'));
    }

    private function resetAutoIncrement()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('barang');
        $records = $builder->orderBy('id', 'ASC')->get()->getResultArray();

        $number = 1;
        foreach ($records as $record) {
            $builder->where('id', $record['id'])
                ->update(['id' => $number]);
            $number++;
        }

        // Optionally reset the auto-increment counter
        $db->query("ALTER TABLE barang AUTO_INCREMENT = " . ($number));
    }

    public function delete($id)
    {
        if (!$this->BarangModel->find($id)) {
            session()->setFlashdata('error', 'Data tidak ditemukan.');
            return redirect()->to(base_url('data-barang')); // Ganti dengan URL yang sesuai
        }

        $this->BarangModel->delete($id);

        // Reset auto-increment setelah menghapus data
        $this->resetAutoIncrement();

        session()->setFlashdata('message', 'Data berhasil dihapus.');
        return redirect()->to(base_url('data-barang')); // Ganti dengan URL yang sesuai
    }

    public function generatePDF()
    {
        $barang_control = $this->BarangModel->findAll();
        $satuan_barang = $this->SatuanBarangModel->findAll();
        $kategori_barang = $this->KategoriBarangModel->findAll();

        // Membuat array untuk lookup nama satuan berdasarkan ID
        $satuan_lookup = [];
        foreach ($satuan_barang as $satuan) {
            $satuan_lookup[$satuan['id']] = $satuan['nama_satuan'];
        }

        // Membuat array untuk lookup nama kategori berdasarkan ID
        $kategori_lookup = [];
        foreach ($kategori_barang as $kategori) {
            $kategori_lookup[$kategori['id']] = $kategori['nama_kategori'];
        }

        // Menggabungkan data laporan barang dengan nama satuan dan kategori
        foreach ($barang_control as &$barang) {
            $barang['nama_satuan'] = isset($satuan_lookup[$barang['jenis_satuan']]) ? $satuan_lookup[$barang['jenis_satuan']] : 'Tidak Ditemukan';
            $barang['nama_kategori'] = isset($kategori_lookup[$barang['kategori_barang_id']]) ? $kategori_lookup[$barang['kategori_barang_id']] : 'Tidak Ditemukan';
        }

        $data['barang'] = $barang_control;

        // Inisialisasi Dompdf
        $dompdf = new Dompdf();
        $html = view('barang/report/pdf', $data);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream("data-barang-report.pdf", array("Attachment" => 0));
    }
}
