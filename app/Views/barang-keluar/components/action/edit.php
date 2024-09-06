<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title); ?></title>
    <!-- CSS -->
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>

<body>
    <section class="p-3 p-md-4 p-xl-5">
        <div class="container">
            <div class="card border-light-subtle shadow-sm">
                <div class="row g-0">
                    <div class="col-12 col-md-6">
                        <img class="img-fluid rounded-start w-100 h-100 object-fit-cover" loading="lazy" src="<?= base_url('img/login-img.jpg') ?>" alt="Warehouse Image">
                    </div>
                    <div class="col-12 col-md-6">

                        <div class="card-body p-3 p-md-4 p-xl-5">
                            <h2 class="h3">Edit Transaksi Barang Keluar</h2>

                            <form action="<?= base_url('transaksi-barang-keluar/update/' . $barangKeluar['id']) ?>" method="post">


                                <?= csrf_field() ?>

                                <div class="row gy-3 gy-md-4 overflow-hidden">
                                    <div class="col-12">
                                        <label for="kode_transaksi" class="form-label">Kode Transaksi <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="kode_transaksi" id="kode_transaksi" value="<?= esc($barangKeluar['kode_transaksi']) ?>" disabled>
                                    </div>
                                    <div class="col-12">
                                        <label for="nama_barang" class="form-label">Nama Barang <span class="text-danger">*</span></label>
                                        <select class="form-select" name="kode_barang" id="kode_barang" required>
                                            <option value="">Pilih Barang</option>
                                            <?php foreach ($dataBarang as $barang): ?>
                                                <option value="<?= esc($barang['kode_barang']) ?>" <?= $barang['kode_barang'] == $barangKeluar['kode_barang'] ? 'selected' : '' ?>>
                                                    <?= esc($barang['nama_barang']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label for="jumlah_keluar" class="form-label">Jumlah Keluar <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="jumlah_keluar" id="jumlah_keluar" value="<?= esc($barangKeluar['jumlah_keluar']) ?>" required>
                                    </div>
                                    <div class="col-12">
                                        <label for="stok_saat_ini" class="form-label">Stok Saat Ini <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="stok_saat_ini" id="stok_saat_ini" value="<?= esc($barangKeluar['stok_saat_ini']) ?>" disabled>
                                    </div>
                                    <div class="col-12">
                                        <label for="tanggal_keluar" class="form-label">Tanggal Keluar <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="tanggal_keluar" id="tanggal_keluar" value="<?= esc($barangKeluar['tanggal_keluar']) ?>" required>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-grid">
                                            <button class="btn btn-primary" type="submit">Update Data</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://unpkg.com/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>