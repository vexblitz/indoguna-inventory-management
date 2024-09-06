<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hapus Kategori</title>
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h4>Konfirmasi Penghapusan</h4>
            </div>
            <div class="card-body">
                <p>Apakah Anda yakin ingin menghapus kategori "<strong><?= esc($barang['nama_kategori']) ?></strong>"?</p>
                <form action="<?= base_url('kategori-barang/delete/' . $barang['id']) ?>" method="post">
                    <?= csrf_field() ?>
                    <a href="<?= base_url('kategori-barang') ?>" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
