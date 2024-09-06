<?php

namespace App\Controllers;

use App\Models\BarangModel;
use App\Models\BarangKeluarModel;
use App\Models\SatuanBarangModel;
use App\Controllers\BaseController;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\UsersModel;

class LaporanBarangKeluar extends BaseController
{
    protected $BarangModel;
    protected $SatuanBarangModel;
    protected $BarangKeluarModel;
    protected $session;
    protected $UsersModel;
    protected $db;

    public function __construct()
    {
        $this->BarangModel = new BarangModel();
        $this->SatuanBarangModel = new SatuanBarangModel();
        $this->BarangKeluarModel = new BarangKeluarModel();
        $this->session = \Config\Services::session(); // Load the session instance
        $this->UsersModel = new UsersModel();
    }

    // Menampilkan daftar laporan barang
    public function index()
    {
        $data['total_bk'] = $this->BarangKeluarModel->getTotal();
        $kodeTransaksi = $this->generateKodeTransaksi(date('Y-m-d'));

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
        $barangKeluarData = $this->BarangKeluarModel->findAll();
        $barangKeluar = [];

        foreach ($barangKeluarData as $data) {
            $barang = $this->BarangModel->where('kode_barang', $data['kode_barang'])->first();
            $data['nama_barang'] = $barang ? $barang['nama_barang'] : 'Tidak Ditemukan';
            $barangKeluar[] = $data;
        }

        // Menghitung total barang keluar
        $totalbarangKeluar = count($barangKeluar);

        // Ambil data pengguna dari session
        $userId = $this->session->get('user_id');
        $user = $this->UsersModel->find($userId);

        $data['barang_control'] = $barang_control;
        return view('lap-barang-keluar/layouts/main', $data = [
            'kode_transaksi' => $kodeTransaksi, // Pass kode transaksi ke view
            'barangKeluar' => $barangKeluar,
            'total_bk' => $totalbarangKeluar, // Kirimkan total barang keluar ke view
            'session' => $this->session,
            'username' => $user['username'], // Mengirimkan username ke view
        ]);
    }

    private function generateKodeTransaksi($tanggal)
    {
        // Ambil tahun dari tanggal
        $tahun = date('Y', strtotime($tanggal));

        // Cari transaksi terakhir dengan format yang sama di tahun yang sama
        $lastTransaction = $this->BarangKeluarModel
            ->where('kode_transaksi LIKE', 'TRX-OUT-%-' . $tahun)
            ->orderBy('id', 'DESC')
            ->first();

        // Tentukan nomor urut transaksi berikutnya
        $nextId = $lastTransaction ? ((int)substr($lastTransaction['kode_transaksi'], 8, 4)) + 1 : 1;

        // Buat kode transaksi dengan format TRX-OUT-XXXX-YYYY
        $kodeTransaksi = sprintf("TRX-OUT-%04d-%s", $nextId, $tahun);

        return $kodeTransaksi;
    }

    public function detail($id)
    {
        // Ambil data transaksi barang Keluar berdasarkan ID
        $barangKeluar = $this->BarangKeluarModel->find($id);
        // Ambil data jenis barang
        $jenisBarang = $this->BarangModel->getJenisBarang();

        // Jika data transaksi tidak ditemukan, lempar pengecualian
        if (!$barangKeluar) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Transaksi tidak ditemukan dengan ID: ' . $id);
        }

        // Ambil detail barang terkait
        $barang = $this->BarangModel->where('kode_barang', $barangKeluar['kode_barang'])->first();
        $barangKeluar['nama_barang'] = $barang ? $barang['nama_barang'] : 'Tidak Ditemukan';

        // Siapkan data untuk view
        $data = [
            'title' => 'Detail Transaksi Barang Keluar',
            'barangKeluar' => $barangKeluar,
            'jenisBarang' => $jenisBarang,
        ];

        // Kirim data ke view
        return view('lap-barang-keluar/components/action/detail', $data);
    }


    public function edit($id)
    {
        $barangKeluar = $this->BarangKeluarModel->find($id);

        if (!$barangKeluar) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Transaksi tidak ditemukan dengan ID: ' . $id);
        }

        // Ambil data barang terkait
        $barang = $this->BarangModel->where('kode_barang', $barangKeluar['kode_barang'])->first();
        $dataBarang = $this->BarangModel->findAll(); // Ambil semua barang untuk dropdown

        $data = [
            'title' => 'Edit Transaksi Barang Keluar',
            'barangKeluar' => $barangKeluar,
            'barang' => $barang,
            'dataBarang' => $dataBarang, // Kirimkan semua barang ke view
        ];

        return view('lap-barang-keluar/components/action/edit', $data);
    }

    public function update($id)
    {
        if (!$this->validate([
            'kode_barang' => 'required',
            'jumlah_keluar' => 'required|integer',
            'tanggal_keluar' => 'required|valid_date[Y-m-d]',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $barangKeluarLama = $this->BarangKeluarModel->find($id);
        if (!$barangKeluarLama) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Transaksi tidak ditemukan dengan ID: ' . $id);
        }

        $kodeBarang = $this->request->getVar('kode_barang');
        $barang = $this->BarangModel->where('kode_barang', $kodeBarang)->first();

        if (!$barang) {
            return redirect()->back()->with('error', 'Barang tidak ditemukan');
        }

        $jumlahKeluarBaru = $this->request->getVar('jumlah_keluar');
        $tanggalKeluar = $this->request->getVar('tanggal_keluar');

        // Hitung selisih jumlah barang keluar
        $selisihJumlahKeluar = $jumlahKeluarBaru - $barangKeluarLama['jumlah_keluar'];

        // Update data barang keluar
        $this->BarangKeluarModel->update($id, [
            'kode_barang' => $kodeBarang,
            'jumlah_keluar' => $jumlahKeluarBaru,
            'stok_saat_ini' => $barang['stok_akhir'] - $selisihJumlahKeluar,
            'tanggal_keluar' => $tanggalKeluar,
        ]);

        // Update stok barang
        $this->BarangModel->update($barang['id'], [
            'stok_akhir' => $barang['stok_akhir'] -  $selisihJumlahKeluar,
        ]);

        return redirect()->to('laporan-barang-keluar');
    }

    public function delete($id)
    {
        // Ambil data transaksi barang keluar berdasarkan ID
        $barangKeluar = $this->BarangKeluarModel->find($id);

        if (!$barangKeluar) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Transaksi tidak ditemukan dengan ID: ' . $id);
        }

        // Ambil data barang terkait
        $barang = $this->BarangModel->where('kode_barang', $barangKeluar['kode_barang'])->first();

        if (!$barang) {
            return redirect()->to('laporan-barang-keluar')->with('error', 'Barang tidak ditemukan dengan kode: ' . $barangKeluar['kode_barang']);
        }

        // Update stok barang
        $stokAkhirLama = $barang['stok_akhir'];
        $jumlahKeluar = $barangKeluar['jumlah_keluar'];

        // Update stok akhir barang
        $this->BarangModel->update($barang['id'], [
            'stok_akhir' => $stokAkhirLama + $jumlahKeluar,
        ]);

        // Hapus data barang keluar
        $this->BarangKeluarModel->delete($id);

        // Reset auto-increment setelah menghapus data (opsional)
        $this->resetAutoIncrement();

        return redirect()->to('laporan-barang-keluar')->with('success', 'Data transaksi berhasil dihapus.');
    }

    private function resetAutoIncrement()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('barang_keluar');
        $records = $builder->orderBy('id', 'ASC')->get()->getResultArray();

        $number = 1;
        foreach ($records as $record) {
            $builder->where('id', $record['id'])
                ->update(['id' => $number]);
            $number++;
        }

        // Optionally reset the auto-increment counter
        $db->query("ALTER TABLE barang_keluar AUTO_INCREMENT = " . ($number));
    }

    public function generatePDF()
    {
        $barangKeluarData = $this->BarangKeluarModel->findAll();
        $barangKeluar = [];
        foreach ($barangKeluarData as $data) {
            $barang = $this->BarangModel->where('kode_barang', $data['kode_barang'])->first();
            $data['nama_barang'] = $barang ? $barang['nama_barang'] : 'Tidak Ditemukan';
            $barangKeluar[] = $data;
        }

        // Data untuk PDF
        $data = [
            'title' => 'Laporan Barang Keluar',
            'barangKeluar' => $barangKeluar
        ];

        // Load library Dompdf
        $options = new Options();
        $options->set('defaultFont', 'Courier');
        $dompdf = new Dompdf($options);

        // Load HTML ke Dompdf
        $dompdf->loadHtml(view('lap-barang-keluar/report/pdf', $data));

        // Setup ukuran kertas dan orientasi
        $dompdf->setPaper('A4', 'landscape');

        // Render HTML menjadi PDF
        $dompdf->render();

        // Output file PDF ke browser
        $dompdf->stream("laporan-barang-Keluar-report.pdf", ["Attachment" => false]);
    }
}
