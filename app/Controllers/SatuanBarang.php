<?php

namespace App\Controllers;

use App\Models\SatuanBarangModel;
use App\Models\UsersModel;
use CodeIgniter\Controller;
use Dompdf\Dompdf;

class SatuanBarang extends Controller
{
    protected $SatuanBarangModel;
    protected $db;
    protected $session;
    protected $UsersModel;
    protected $satuanbarang;

    public function __construct()
    {
        $this->SatuanBarangModel = new SatuanBarangModel();
        $this->db = \Config\Database::connect(); // Load the database instance
        $this->session = \Config\Services::session(); // Load the session instance
        $this->UsersModel = new UsersModel();
    }

    public function index()
    {
        // Ambil data pengguna dari session
        $userId = $this->session->get('user_id');
        $user = $this->UsersModel->find($userId);

        $satuanbarang = $this->SatuanBarangModel->findAll();
        $data = [
            'satuanbarang' => $satuanbarang,
            'total_satuan' => count($satuanbarang),
            'session' => $this->session,
            'username' => $user['username'], // Mengirimkan username ke view
        ];

        return view('satuan-barang/layouts/main', $data);
    }

    public function create()
    {
        return view('satuan-barang/components/action/create');
    }

    public function store()
    {
        $validation = \Config\Services::validation();

        $rules = [
            'nama_satuan' => 'required|string'
        ];

        if (!$this->validate($rules)) {
            return view('satuan-barang/components/action/create', [
                'validation' => $this->validator
            ]);
        }

        $data = [
            'nama_satuan' => $this->request->getPost('nama_satuan'),
        ];

        $this->SatuanBarangModel->save($data);

        // Setelah menambah data baru, reset kembali nomor urut untuk memastikan keteraturan
        $this->resetAutoIncrement();

        return redirect()->to('satuan-barang')->with('message', 'Data berhasil ditambahkan.');
    }

    public function detail($id = null)
    {
        $barang = $this->SatuanBarangModel->find($id);
        if (!$barang) {
            return redirect()->to('satuan-barang')->with('error', 'Data tidak ditemukan.');
        }

        return view('satuan-barang/components/action/detail', [
            'barang' => $barang
        ]);
    }

    public function edit($id)
    {
        $barang = $this->SatuanBarangModel->find($id);
        if (!$barang) {
            return redirect()->to('/satuan-barang')->with('error', 'Data tidak ditemukan.');
        }

        return view('satuan-barang/components/action/edit', [
            'barang' => $barang,
        ]);
    }

    public function update()
    {
        $id = $this->request->getPost('id');
        $data = [
            'nama_satuan' => $this->request->getPost('nama_satuan'),
        ];

        $validation = \Config\Services::validation();
        $rules = [
            'nama_satuan' => 'required|string'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $validation->getErrors());
        }

        if ($this->SatuanBarangModel->update($id, $data)) {
            return redirect()->to('satuan-barang')->with('message', 'Data berhasil diperbarui.');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->SatuanBarangModel->errors());
        }
    }


    public function delete($id)
    {
        if ($id === null) {
            return redirect()->to(base_url('satuan-barang'))->with('error', 'ID tidak ditemukan.');
        }

        if ($this->SatuanBarangModel->delete($id)) {
            // Reset nomor urut setelah penghapusan
            $this->resetAutoIncrement();
            return redirect()->to(base_url('satuan-barang'))->with('success', 'Data berhasil dihapus.');
        } else {
            return redirect()->to(base_url('satuan-barang'))->with('error', 'Data gagal dihapus.');
        }
    }

    private function resetAutoIncrement()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('satuan_barang');
        $records = $builder->orderBy('id', 'ASC')->get()->getResultArray();

        $number = 1;
        foreach ($records as $record) {
            $builder->where('id', $record['id'])
                ->update(['id' => $number]);
            $number++;
        }

        // Optionally reset the auto-increment counter
        $db->query("ALTER TABLE satuan_barang AUTO_INCREMENT = " . ($number));
    }

    // Generate PDF

    public function generatePDF()
    {
        $data['satuan_barang'] = $this->SatuanBarangModel->findAll();

        $dompdf = new Dompdf();
        $dompdf->loadHtml(view('satuan-barang/report/pdf', $data));
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream("report.pdf", array("Attachment" => 0));
    }
}
