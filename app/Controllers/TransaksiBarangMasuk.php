<?php

namespace App\Controllers;

use App\Models\BarangMasukModel;
use App\Models\BarangModel;
use App\Models\SatuanBarangModel;
use App\Models\UsersModel;

class TransaksiBarangMasuk extends BaseController
{
    protected $barangMasukModel;
    protected $barangModel;
    protected $session;
    protected $UsersModel;
    protected $db;

    public function __construct()
    {
        $this->barangMasukModel = new BarangMasukModel();
        $this->barangModel = new BarangModel();
        $this->session = \Config\Services::session(); // Load the session instance
        $this->UsersModel = new UsersModel();
    }
    public function index()
    {
        $data['total_bm'] = $this->barangMasukModel->getTotal();

        $barangMasukData = $this->barangMasukModel->findAll();
        $barangMasuk = [];
        foreach ($barangMasukData as $data) {
            $barang = $this->barangModel->where('kode_barang', $data['kode_barang'])->first();
            $data['nama_barang'] = $barang ? $barang['nama_barang'] : 'Tidak Ditemukan';
            $barangMasuk[] = $data;
        }

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

        return view('barang-masuk/layouts/main', $data);
    }

    public function create()
    {
        // Generate kode transaksi otomatis
        $kodeTransaksi = $this->generateKodeTransaksi(date('Y-m-d'));
        $barang = $this->barangModel->findAll();
        $data = [
            'title' => 'Tambah Barang Masuk',
            'barang' => $barang,
            'kode_transaksi' => $kodeTransaksi, // Pass kode transaksi ke view
        ];

        return view('barang-masuk/components/action/create', $data);

        // Setelah menambah data baru, reset kembali nomor urut untuk memastikan keteraturan
        $this->resetAutoIncrement();
    }

    public function store()
    {
        if (!$this->validate([
            'kode_barang' => 'required',
            'jumlah_masuk' => 'required|integer',
            'tanggal_masuk' => 'required|valid_date[Y-m-d]',
        ])) {
            return redirect()->back()->withInput();
        }

        // Generate kode transaksi
        $tanggalMasuk = $this->request->getVar('tanggal_masuk');
        $kodeTransaksi = $this->generateKodeTransaksi($tanggalMasuk);

        // Mengambil stok saat ini dari database
        $kodeBarang = $this->request->getVar('kode_barang');
        $barang = $this->barangModel->where('kode_barang', $kodeBarang)->first();

        if (!$barang) {
            // Handle case where barang is not found
            return redirect()->back()->with('error', 'Barang tidak ditemukan');
        }

        // Menyimpan data transaksi barang masuk
        $this->barangMasukModel->save([
            'kode_transaksi' => $kodeTransaksi,
            'kode_barang' => $kodeBarang,
            'jumlah_masuk' => $this->request->getVar('jumlah_masuk'),
            'stok_saat_ini' => $barang['stok_akhir'] + $this->request->getVar('jumlah_masuk'),
            'tanggal_masuk' => $tanggalMasuk,
        ]);

        // Mengupdate stok barang di tabel barang
        $this->barangModel->update($barang['id'], [
            'stok_akhir' => $barang['stok_akhir'] + $this->request->getVar('jumlah_masuk'),
        ]);

        return redirect()->to('transaksi-barang-masuk');
    }


    private function generateKodeTransaksi($tanggal)
    {
        // Format kode transaksi: TRX-IN-XXX-YYYY
        $tahun = date('Y', strtotime($tanggal)); // Ambil tahun dari tanggal transaksi
        $prefix = 'TRX-IN';

        // Cari transaksi terakhir pada tahun yang sama
        $lastTransaction = $this->barangMasukModel
            ->where('kode_transaksi LIKE', "$prefix-%-$tahun")
            ->orderBy('id', 'DESC')
            ->first();

        // Jika ada transaksi sebelumnya, ambil nomor urut terakhir, jika tidak mulai dari 1
        $nextId = $lastTransaction ? ((int)substr($lastTransaction['kode_transaksi'], 7, 3)) + 1 : 1;

        // Format kode transaksi menjadi TRX-IN-XXX-YYYY
        $kodeTransaksi = sprintf("%s-%03d-%s", $prefix, $nextId, $tahun);

        return $kodeTransaksi;
    }

    public function detail($id)
    {
        // Ambil data transaksi barang masuk berdasarkan ID
        $barangMasuk = $this->barangMasukModel->find($id);
        // Ambil data jenis barang
        $jenisBarang = $this->barangModel->getJenisBarang();

        // Jika data transaksi tidak ditemukan, lempar pengecualian
        if (!$barangMasuk) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Transaksi tidak ditemukan dengan ID: ' . $id);
        }

        // Ambil detail barang terkait
        $barang = $this->barangModel->where('kode_barang', $barangMasuk['kode_barang'])->first();
        $barangMasuk['nama_barang'] = $barang ? $barang['nama_barang'] : 'Tidak Ditemukan';

        // Siapkan data untuk view
        $data = [
            'title' => 'Detail Transaksi Barang Masuk',
            'barangMasuk' => $barangMasuk,
            'jenisBarang' => $jenisBarang,
        ];

        // Kirim data ke view
        return view('barang-masuk/components/action/detail', $data);
    }

    public function edit($id)
    {
        $barangMasuk = $this->barangMasukModel->find($id);

        if (!$barangMasuk) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Transaksi tidak ditemukan dengan ID: ' . $id);
        }

        // Ambil data barang terkait
        $barang = $this->barangModel->where('kode_barang', $barangMasuk['kode_barang'])->first();
        $dataBarang = $this->barangModel->findAll(); // Ambil semua barang untuk dropdown

        $data = [
            'title' => 'Edit Transaksi Barang Masuk',
            'barangMasuk' => $barangMasuk,
            'barang' => $barang,
            'dataBarang' => $dataBarang, // Kirimkan semua barang ke view
        ];

        return view('barang-masuk/components/action/edit', $data);
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
        $barang = $this->barangModel->where('kode_barang', $kodeBarang)->first();

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
        $this->barangModel->update($barang['id'], [
            'stok_akhir' => $barang['stok_akhir'] +  $selisihJumlahMasuk,
        ]);

        return redirect()->to('transaksi-barang-masuk');
    }

    public function delete($id)
    {
        // Ambil data transaksi barang masuk berdasarkan ID
        $barangMasuk = $this->barangMasukModel->find($id);

        if (!$barangMasuk) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Transaksi tidak ditemukan dengan ID: ' . $id);
        }

        // Ambil data barang terkait
        $barang = $this->barangModel->where('kode_barang', $barangMasuk['kode_barang'])->first();

        if (!$barang) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Barang tidak ditemukan dengan kode: ' . $barangMasuk['kode_barang']);
        }

        // Update stok barang
        $stokAkhirLama = $barang['stok_akhir'];
        $jumlahMasuk = $barangMasuk['jumlah_masuk'];

        $this->barangModel->update($barang['id'], [
            'stok_akhir' => $stokAkhirLama - $jumlahMasuk,
        ]);

        // Hapus data barang masuk
        $this->barangMasukModel->delete($id);

        // Reset auto-increment setelah menghapus data
        $this->resetAutoIncrement();

        return redirect()->to('transaksi-barang-masuk')->with('success', 'Data transaksi berhasil dihapus.');
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
}
