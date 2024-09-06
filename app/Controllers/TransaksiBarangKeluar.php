<?php

namespace App\Controllers;

use App\Models\BarangKeluarModel;
use App\Models\BarangModel;
use App\Models\UsersModel;

class TransaksiBarangKeluar extends BaseController
{
    protected $BarangKeluarModel;
    protected $barangModel;
    protected $session;
    protected $UsersModel;
    protected $db;

    public function __construct()
    {
        $this->BarangKeluarModel = new BarangKeluarModel();
        $this->barangModel = new BarangModel();
        $this->session = \Config\Services::session(); // Load the session instance
        $this->UsersModel = new UsersModel();
    }
    public function index()
    {
        $data['total_bk'] = $this->BarangKeluarModel->getTotal();

        $barangKeluarData = $this->BarangKeluarModel->findAll();
        $barangKeluar = [];

        foreach ($barangKeluarData as $data) {
            $barang = $this->barangModel->where('kode_barang', $data['kode_barang'])->first();
            $data['nama_barang'] = $barang ? $barang['nama_barang'] : 'Tidak Ditemukan';
            $barangKeluar[] = $data;
        }

        // Menghitung total barang keluar
        $totalbarangKeluar = count($barangKeluar);

        // Ambil data pengguna dari session
        $userId = $this->session->get('user_id');
        $user = $this->UsersModel->find($userId);

        $data = [
            'title' => 'Transaksi Barang Keluar',
            'barangKeluar' => $barangKeluar,
            'total_bk' => $totalbarangKeluar, // Kirimkan total barang keluar ke view
            'session' => $this->session,
            'username' => $user['username'], // Mengirimkan username ke view
        ];

        return view('barang-keluar/layouts/main', $data);
    }

    public function create()
    {
        // Generate kode transaksi otomatis
        $kodeTransaksi = $this->generateKodeTransaksi(date('Y-m-d'));
        $barang = $this->barangModel->findAll();
        $data = [
            'title' => 'Tambah Barang keluar',
            'barang' => $barang,
            'kode_transaksi' => $kodeTransaksi, // Pass kode transaksi ke view
        ];

        return view('barang-keluar/components/action/create', $data);
    }

    public function store()
    {
        if (!$this->validate([
            'kode_barang' => 'required',
            'jumlah_keluar' => 'required|integer',
            'tanggal_keluar' => 'required|valid_date[Y-m-d]',
        ])) {
            return redirect()->back()->withInput();
        }

        // Generate kode transaksi
        $tanggalkeluar = $this->request->getVar('tanggal_keluar');
        $kodeTransaksi = $this->generateKodeTransaksi($tanggalkeluar);

        // Mengambil stok saat ini dari database
        $kodeBarang = $this->request->getVar('kode_barang');
        $barang = $this->barangModel->where('kode_barang', $kodeBarang)->first();

        if (!$barang) {
            // Handle case where barang is not found
            return redirect()->back()->with('error', 'Barang tidak ditemukan');
        }

        // Pastikan menggunakan kolom yang benar
        $stokAwal = $barang['stok_awal'];
        $stokAkhir = $barang['stok_akhir'];

        // Menyimpan data transaksi barang keluar
        $this->BarangKeluarModel->save([
            'kode_transaksi' => $kodeTransaksi,
            'kode_barang' => $kodeBarang,
            'jumlah_keluar' => $this->request->getVar('jumlah_keluar'),
            'stok_saat_ini' => $stokAkhir - $this->request->getVar('jumlah_keluar'),
            'tanggal_keluar' => $tanggalkeluar,
        ]);

        // Mengupdate stok barang di tabel barang
        $this->barangModel->update($barang['id'], [
            'stok_awal' => $stokAwal,
            'stok_akhir' => $stokAkhir - $this->request->getVar('jumlah_keluar'),
        ]);

        return redirect()->to('transaksi-barang-keluar');
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
        // Ambil data transaksi barang keluar berdasarkan ID
        $barangKeluar = $this->BarangKeluarModel->find($id);
        // Ambil data jenis barang
        $jenisBarang = $this->barangModel->getJenisBarang();

        // Jika data transaksi tidak ditemukan, lempar pengecualian
        if (!$barangKeluar) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Transaksi tidak ditemukan dengan ID: ' . $id);
        }

        // Ambil detail barang terkait
        $barang = $this->barangModel->where('kode_barang', $barangKeluar['kode_barang'])->first();
        $barangKeluar['nama_barang'] = $barang ? $barang['nama_barang'] : 'Tidak Ditemukan';

        // Siapkan data untuk view
        $data = [
            'title' => 'Detail Transaksi Barang Keluar',
            'barangKeluar' => $barangKeluar,
            'jenisBarang' => $jenisBarang,
        ];

        // Kirim data ke view
        return view('barang-keluar/components/action/detail', $data);

        // Setelah menambah data baru, reset kembali nomor urut untuk memastikan keteraturan
        $this->resetAutoIncrement();
    }

    public function edit($id)
    {
        $barangKeluar = $this->BarangKeluarModel->find($id);

        if (!$barangKeluar) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Transaksi tidak ditemukan dengan ID: ' . $id);
        }

        // Ambil data barang terkait
        $barang = $this->barangModel->where('kode_barang', $barangKeluar['kode_barang'])->first();
        $dataBarang = $this->barangModel->findAll(); // Ambil semua barang untuk dropdown

        $data = [
            'title' => 'Edit Transaksi Barang Keluar',
            'barangKeluar' => $barangKeluar,
            'barang' => $barang,
            'dataBarang' => $dataBarang, // Kirimkan semua barang ke view
        ];

        return view('barang-keluar/components/action/edit', $data);
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
        $barang = $this->barangModel->where('kode_barang', $kodeBarang)->first();

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
        $this->barangModel->update($barang['id'], [
            'stok_akhir' => $barang['stok_akhir'] -  $selisihJumlahKeluar,
        ]);

        return redirect()->to('transaksi-barang-keluar');
    }

    public function delete($id)
    {
        // Ambil data transaksi barang masuk berdasarkan ID
        $barangKeluar = $this->BarangKeluarModel->find($id);

        if (!$barangKeluar) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Transaksi tidak ditemukan dengan ID: ' . $id);
        }

        // Ambil data barang terkait
        $barang = $this->barangModel->where('kode_barang', $barangKeluar['kode_barang'])->first();

        if (!$barang) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Barang tidak ditemukan dengan kode: ' . $barangKeluar['kode_barang']);
        }

        // Update stok barang
        $stokAkhirLama = $barang['stok_akhir'];
        $jumlahkeluarBaru = $barangKeluar['jumlah_keluar'];

        $this->barangModel->update($barang['id'], [
            'stok_akhir' => $stokAkhirLama - $jumlahkeluarBaru,
        ]);

        // Hapus data barang keluar
        $this->BarangKeluarModel->delete($id);

        // Reset auto-increment setelah menghapus data
        $this->resetAutoIncrement();

        return redirect()->to('transaksi-barang-keluar')->with('success', 'Data transaksi berhasil dihapus.');
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
}
