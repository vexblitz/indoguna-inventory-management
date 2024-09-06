<?php

namespace App\Controllers;

use App\Models\KategoriBarangModel;
use CodeIgniter\Controller;
use App\Models\UsersModel;
use Dompdf\Dompdf;

class KategoriBarang extends Controller
{
    protected $KategoriBarangModel;
    protected $db;
    protected $session;
    protected $UsersModel;

    public function __construct()
    {
        $this->KategoriBarangModel = new KategoriBarangModel();
        $this->UsersModel = new UsersModel();
        $this->db = \Config\Database::connect(); // Load the database instance
        $this->session = \Config\Services::session(); // Load the session instance
    }

    public function index()
    {

        // Ambil data pengguna dari session
        $userId = $this->session->get('user_id');
        $user = $this->UsersModel->find($userId);

        // Kategori
        $total_kategori = $this->KategoriBarangModel->getTotalKategori();


        $KategoriBarangModel = $this->KategoriBarangModel->findAll();
        $data['kategori_barang'] = $this->KategoriBarangModel->findAll();
        $data = [
            'KategoriBarangModel' => $KategoriBarangModel,
            'total_roles' => count($KategoriBarangModel),
            'session' => $this->session,
            'username' => $user['username'], // Mengirimkan username ke view
            'total_kategori' => $total_kategori,
        ];

        return view('kategori-barang/layouts/main', $data);
    }

    public function create()
    {
        return view('kategori-barang/components/action/create');
    }

    public function store()
    {
        $validation = \Config\Services::validation();

        $rules = [
            'nama_kategori' => 'required|string'
        ];

        if (!$this->validate($rules)) {
            return view('kategori-barang/components/action/create', [
                'validation' => $this->validator
            ]);
        }

        $data = [
            'nama_kategori' => $this->request->getPost('nama_kategori'),
        ];

        $this->KategoriBarangModel->save($data);

        // Setelah menambah data baru, reset kembali nomor urut untuk memastikan keteraturan
        $this->resetAutoIncrement();

        return redirect()->to('kategori-barang')->with('message', 'Data berhasil ditambahkan.');
    }

    public function detail($id = null)
    {
        $barang = $this->KategoriBarangModel->find($id);
        if (!$barang) {
            return redirect()->to('kategori-barang')->with('error', 'Data tidak ditemukan.');
        }

        return view('kategori-barang/components/action/detail', [
            'barang' => $barang
        ]);
    }

    public function edit($id)
    {
        $barang = $this->KategoriBarangModel->find($id);
        if (!$barang) {
            return redirect()->to('/kategori-barang')->with('error', 'Data tidak ditemukan.');
        }

        return view('kategori-barang/components/action/edit', [
            'barang' => $barang,
        ]);
    }

    public function update()
    {
        $id = $this->request->getPost('id');
        $data = [
            'nama_kategori' => $this->request->getPost('nama_kategori'),
        ];

        $validation = \Config\Services::validation();
        $rules = [
            'nama_kategori' => 'required|string'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $validation->getErrors());
        }

        if ($this->KategoriBarangModel->update($id, $data)) {
            return redirect()->to('/kategori-barang')->with('message', 'Data berhasil diperbarui.');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->KategoriBarangModel->errors());
        }
    }


    public function delete($id)
    {
        if ($id === null) {
            return redirect()->to(base_url('kategori-barang'))->with('error', 'ID tidak ditemukan.');
        }

        if ($this->KategoriBarangModel->delete($id)) {
            // Reset nomor urut setelah penghapusan
            $this->resetAutoIncrement();
            return redirect()->to(base_url('kategori-barang'))->with('success', 'Data berhasil dihapus.');
        } else {
            return redirect()->to(base_url('kategori-barang'))->with('error', 'Data gagal dihapus.');
        }
    }

    private function resetAutoIncrement()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('kategori_barang');
        $records = $builder->orderBy('id', 'ASC')->get()->getResultArray();

        $number = 1;
        foreach ($records as $record) {
            $builder->where('id', $record['id'])
                ->update(['id' => $number]);
            $number++;
        }

        // Optionally reset the auto-increment counter
        $db->query("ALTER TABLE kategori_barang AUTO_INCREMENT = " . ($number));
    }

    public function generatePDF()
    {
        $data['kategori_barang'] = $this->KategoriBarangModel->findAll();

        $dompdf = new Dompdf();
        $dompdf->loadHtml(view('kategori-barang/report/pdf', $data));
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream("report.pdf", array("Attachment" => 0));
    }
}
