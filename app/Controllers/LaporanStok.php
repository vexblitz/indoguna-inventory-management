<?php

namespace App\Controllers;

use App\Models\BarangModel;
use App\Models\SatuanBarangModel;
use App\Models\KategoriBarangModel;
use App\Controllers\BaseController;
use Dompdf\Dompdf;
use App\Models\UsersModel;

class LaporanStok extends BaseController
{
    protected $BarangModel;
    protected $SatuanBarangModel;
    protected $KategoriBarangModel;
    protected $session;
    protected $UsersModel;
    protected $db;

    public function __construct()
    {
        $this->BarangModel = new BarangModel();
        $this->SatuanBarangModel = new SatuanBarangModel();
        $this->KategoriBarangModel = new KategoriBarangModel();
        $this->session = \Config\Services::session(); // Load the session instance
        $this->UsersModel = new UsersModel();
    }

    // Menampilkan daftar laporan barang
    public function index()
    {
        $bulan = $this->request->getGet('bulan');
        $data['satuan_barang'] = $this->SatuanBarangModel->findAll();

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

        // Menggabungkan data laporan barang dengan nama satuan
        foreach ($barang_control as &$barang) {
            $barang['nama_satuan'] = isset($satuan_lookup[$barang['jenis_satuan']]) ? $satuan_lookup[$barang['jenis_satuan']] : 'Tidak Ditemukan';
        }

        // Menghitung total barang masuk
        $totalBarang = count($barang_control);

        // Ambil data pengguna dari session
        $userId = $this->session->get('user_id');
        $user = $this->UsersModel->find($userId);

        $data = [
            'barang_control' => $barang_control,
            'total_Barang' => $totalBarang, // Kirimkan total barang  ke view
            'session' => $this->session,
            'username' => $user['username'], // Mengirimkan username ke view
        ];

        return view('lap-stok/layouts/main', $data);
    }

    public function detail($id)
    {
        $barang = $this->BarangModel->find($id);
        $kategoriBarang = $this->KategoriBarangModel->findAll();

        if (!$barang) {
            session()->setFlashdata('error', 'Data barang tidak ditemukan.');
            return redirect()->to(base_url('laporan-stok'));
        }

        $jenisBarang = $this->SatuanBarangModel->findAll();

        return view('lap-stok/components/action/detail', [
            'barang' => $barang,
            'jenisBarang' => $jenisBarang,
            'kategoriBarang' => $kategoriBarang,
        ]);
    }


    // Menampilkan form untuk mengedit barang
    public function edit($id)
    {
        $barang = $this->BarangModel->find($id);

        // Jika barang tidak ditemukan, redirect dengan pesan error
        if (!$barang) {
            session()->setFlashdata('error', 'Data barang tidak ditemukan.');
            return redirect()->to(base_url('laporan-stok'));
        }

        $jenisBarang = $this->SatuanBarangModel->findAll();
        $kategoriBarang = $this->KategoriBarangModel->findAll();

        return view('lap-stok/components/action/edit', [
            'barang' => $barang,
            'jenisBarang' => $jenisBarang,
            'kategoriBarang' => $kategoriBarang,
        ]);
    }

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

        // Menghapus format mata uang dari harga beli dan harga jual
        $harga_beli = $this->request->getPost('harga_beli');
        $harga_jual = $this->request->getPost('harga_jual');

        $data = [
            'nama_barang' => $this->request->getPost('nama_barang'),
            'harga_beli' => str_replace(['Rp. ', '.', ','], '', $harga_beli),
            'harga_jual' => str_replace(['Rp. ', '.', ','], '', $harga_jual),
            'jenis_satuan' => $this->request->getPost('jenis_satuan'),
            'kategori_barang_id' => $this->request->getPost('kategori_barang_id'),
            'stok_awal' => $this->request->getPost('stok_awal'),
            'stok_akhir' => $this->request->getPost('stok_akhir'),
        ];

        // Melakukan pembaruan data
        $this->BarangModel->update($id, $data);

        // Mengatur pesan flash untuk notifikasi
        session()->setFlashdata('message', 'Data berhasil diupdate.');
        return redirect()->to(base_url('laporan-stok'));
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
            return redirect()->to(base_url('laporan-stok')); // Ganti dengan URL yang sesuai
        }

        $this->BarangModel->delete($id);

        // Reset auto-increment setelah menghapus data
        $this->resetAutoIncrement();

        session()->setFlashdata('message', 'Data berhasil dihapus.');
        return redirect()->to(base_url('laporan-stok')); // Ganti dengan URL yang sesuai
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
        $dompdf->stream("laporan-stok-report.pdf", array("Attachment" => 0));
    }


    public function apiData($bulan)
    {
        $corona = new BarangModel();
        $corona->where('tgl >=', "2020-{$bulan}-01");
        $corona->where('tgl <=', "2020-{$bulan}-31");
        $corona->orderBy('tgl', 'asc');
        echo json_encode($corona->get()->getResult());
    }
}
