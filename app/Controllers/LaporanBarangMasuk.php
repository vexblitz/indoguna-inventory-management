<?php

namespace App\Controllers;

use App\Models\BarangModel;
use App\Models\SatuanBarangModel;
use App\Models\BarangMasukModel;
use App\Controllers\BaseController;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\UsersModel;

class LaporanBarangMasuk extends BaseController
{
    protected $BarangModel;
    protected $SatuanBarangModel;
    protected $barangMasukModel;
    protected $session;
    protected $UsersModel;
    protected $db;


    public function __construct()
    {
        $this->BarangModel = new BarangModel();
        $this->SatuanBarangModel = new SatuanBarangModel();
        $this->barangMasukModel = new BarangMasukModel();
        $this->session = \Config\Services::session(); // Load the session instance
        $this->UsersModel = new UsersModel();
    }

    // Menampilkan daftar laporan barang
    public function index()
    {
        $barangMasukData = $this->barangMasukModel->findAll();
        $barangMasuk = [];

        foreach ($barangMasukData as $data) {
            $barang = $this->BarangModel->where('kode_barang', $data['kode_barang'])->first();
            $data['nama_barang'] = $barang ? $barang['nama_barang'] : 'Tidak Ditemukan';
            $barangMasuk[] = $data;
        }

        $data['satuan_barang'] = $this->SatuanBarangModel->findAll();

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
        $data['barang_control'] = $barang_control;

        // Menghitung total barang masuk
        $totalBarangMasuk = count($barangMasuk);

        // Ambil data pengguna dari session
        $userId = $this->session->get('user_id');
        $user = $this->UsersModel->find($userId);

        $data = [
            'title' => 'Transaksi Barang Masuk',
            'barangMasuk' => $barangMasuk,
            'total_bm' => $totalBarangMasuk, // Kirimkan total barang masuk ke view
            'session' => $this->session,
            'username' => $user['username'], // Mengirimkan username ke view
        ];


        return view('lap-barang-masuk/layouts/main', $data);
    }

    public function detail($id)
    {
        // Ambil data transaksi barang masuk berdasarkan ID
        $barangMasuk = $this->barangMasukModel->find($id);
        // Ambil data jenis barang
        $jenisBarang = $this->BarangModel->getJenisBarang();

        // Jika data transaksi tidak ditemukan, lempar pengecualian
        if (!$barangMasuk) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Transaksi tidak ditemukan dengan ID: ' . $id);
        }

        // Ambil detail barang terkait
        $barang = $this->BarangModel->where('kode_barang', $barangMasuk['kode_barang'])->first();
        $barangMasuk['nama_barang'] = $barang ? $barang['nama_barang'] : 'Tidak Ditemukan';

        // Siapkan data untuk view
        $data = [
            'title' => 'Detail Transaksi Barang Masuk',
            'barangMasuk' => $barangMasuk,
            'jenisBarang' => $jenisBarang,
        ];

        // Kirim data ke view
        return view('lap-barang-masuk/components/action/detail', $data);
    }

    public function edit($id)
    {
        $barangMasuk = $this->barangMasukModel->find($id);

        if (!$barangMasuk) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Transaksi tidak ditemukan dengan ID: ' . $id);
        }

        // Ambil data barang terkait
        $barang = $this->BarangModel->where('kode_barang', $barangMasuk['kode_barang'])->first();
        $dataBarang = $this->BarangModel->findAll(); // Ambil semua barang untuk dropdown

        $data = [
            'title' => 'Edit Transaksi Barang Masuk',
            'barangMasuk' => $barangMasuk,
            'barang' => $barang,
            'dataBarang' => $dataBarang, // Kirimkan semua barang ke view
        ];

        return view('lap-barang-masuk/components/action/edit', $data);
    }

    public function update($id)
    {
        if (!$this->validate([
            'kode_barang' => 'required',
            'jumlah_masuk' => 'required|integer',
            'tanggal_masuk' => 'required|valid_date[Y-m-d]',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $barangMasukLama = $this->barangMasukModel->find($id);
        if (!$barangMasukLama) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Transaksi tidak ditemukan dengan ID: ' . $id);
        }

        $kodeBarang = $this->request->getVar('kode_barang');
        $barang = $this->BarangModel->where('kode_barang', $kodeBarang)->first();

        if (!$barang) {
            return redirect()->back()->with('error', 'Barang tidak ditemukan');
        }

        $jumlahMasukBaru = $this->request->getVar('jumlah_masuk');
        $tanggalMasuk = $this->request->getVar('tanggal_masuk');

        // Hitung selisih jumlah barang masuk
        $selisihJumlahMasuk = $jumlahMasukBaru - $barangMasukLama['jumlah_masuk'];

        // Update data barang masuk
        $this->barangMasukModel->update($id, [
            'kode_barang' => $kodeBarang,
            'jumlah_masuk' => $jumlahMasukBaru,
            'stok_saat_ini' => $barang['stok_akhir'] + $selisihJumlahMasuk,
            'tanggal_masuk' => $tanggalMasuk,
        ]);

        // Update stok barang
        $this->BarangModel->update($barang['id'], [
            'stok_akhir' => $barang['stok_akhir'] +  $selisihJumlahMasuk,
        ]);

        return redirect()->to('laporan-barang-masuk');
    }

    public function delete($id)
    {
        // Ambil data transaksi barang masuk berdasarkan ID
        $barangMasuk = $this->barangMasukModel->find($id);

        if (!$barangMasuk) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Transaksi tidak ditemukan dengan ID: ' . $id);
        }

        // Ambil data barang terkait
        $barang = $this->BarangModel->where('kode_barang', $barangMasuk['kode_barang'])->first();

        if (!$barang) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Barang tidak ditemukan dengan kode: ' . $barangMasuk['kode_barang']);
        }

        // Update stok barang
        $stokAkhirLama = $barang['stok_akhir'];
        $jumlahMasuk = $barangMasuk['jumlah_masuk'];

        $this->BarangModel->update($barang['id'], [
            'stok_akhir' => $stokAkhirLama - $jumlahMasuk,
        ]);

        // Hapus data barang masuk
        $this->barangMasukModel->delete($id);

        // Reset auto-increment setelah menghapus data
        $this->resetAutoIncrement();

        return redirect()->to('laporan-barang-masuk')->with('success', 'Data transaksi berhasil dihapus.');
    }

    private function resetAutoIncrement()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('barang_masuk');
        $records = $builder->orderBy('id', 'ASC')->get()->getResultArray();

        $number = 1;
        foreach ($records as $record) {
            $builder->where('id', $record['id'])
                ->update(['id' => $number]);
            $number++;
        }

        // Optionally reset the auto-increment counter
        $db->query("ALTER TABLE barang_masuk AUTO_INCREMENT = " . ($number));
    }

    public function generatePDF()
    {
        $barangMasukData = $this->barangMasukModel->findAll();
        $barangMasuk = [];
        foreach ($barangMasukData as $data) {
            $barang = $this->BarangModel->where('kode_barang', $data['kode_barang'])->first();
            $data['nama_barang'] = $barang ? $barang['nama_barang'] : 'Tidak Ditemukan';
            $barangMasuk[] = $data;
        }

        // Data untuk PDF
        $data = [
            'title' => 'Laporan Barang Masuk',
            'barangMasuk' => $barangMasuk
        ];

        // Load library Dompdf
        $options = new Options();
        $options->set('defaultFont', 'Courier');
        $dompdf = new Dompdf($options);

        // Load HTML ke Dompdf
        $dompdf->loadHtml(view('lap-barang-masuk/report/pdf', $data));

        // Setup ukuran kertas dan orientasi
        $dompdf->setPaper('A4', 'landscape');

        // Render HTML menjadi PDF
        $dompdf->render();

        // Output file PDF ke browser
        $dompdf->stream("laporan-barang-masuk-report.pdf", ["Attachment" => false]);
    }
}
