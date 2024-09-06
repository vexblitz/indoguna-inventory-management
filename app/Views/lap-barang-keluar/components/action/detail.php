<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSS -->
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://unpkg.com/bs-brain@2.0.4/components/registrations/registration-4/assets/css/registration-4.css">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= base_url('img/favicon/warehouse.png') ?>">

    <title> Laporan Barang Keluar </title>

</head>

<body>
    <!-- Detail Transaksi Barang Keluar - Bootstrap Brain Component -->
    <section class="p-3 p-md-4 p-xl-5">
        <div class="container">
            <div class="card border-light-subtle shadow-sm">
                <div class="row g-0">
                    <div class="col-12 col-md-6">
                        <img class="img-fluid rounded-start w-100 h-100 object-fit-cover" loading="lazy" src="<?= base_url('img/login-img.jpg') ?>" alt="Warehouse Image">
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="card-body p-3 p-md-4 p-xl-5">
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-5">
                                        <h2 class="h3">Detail Transaksi Barang Keluar</h2>
                                        <h3 class="fs-6 fw-normal text-secondary m-0">Menampilkan Data Transaksi Secara Detail</h3>
                                    </div>
                                </div>

                                <?php if (isset($validation)) : ?>
                                    <div style="color: red;">
                                        <?= $validation->listErrors() ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <form action="<?= base_url('laporan-barang-keluar') ?>">
                                <?= csrf_field() ?>

                                <div class="row gy-3 gy-md-4 overflow-hidden">
                                    <div class="col-12">
                                        <label for="kode_transaksi" class="form-label">Kode Transaksi <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="kode_transaksi" id="kode_transaksi" value="<?= esc($barangKeluar['kode_transaksi']) ?>" disabled>
                                    </div>
                                    <div class="col-12">
                                        <label for="kode_barang" class="form-label">Kode Barang <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="kode_barang" id="kode_barang" value="<?= esc($barangKeluar['kode_barang']) ?>" disabled>
                                    </div>
                                    <div class="col-12">
                                        <label for="nama_barang" class="form-label">Nama Barang <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="nama_barang" id="nama_barang" value="<?= esc($barangKeluar['nama_barang']) ?>" placeholder="Isikan Nama Barang Dengan Benar" disabled>
                                    </div>
                                    <div class="col-12">
                                        <label for="jumlah_keluar" class="form-label">Jumlah Keluar <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="jumlah_keluar" id="jumlah_keluar" value="<?= esc($barangKeluar['jumlah_keluar']) ?>" disabled>
                                    </div>
                                    <div class="col-12">
                                        <label for="stok_saat_ini" class="form-label">Stok Saat Ini <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="stok_saat_ini" id="stok_saat_ini" value="<?= esc($barangKeluar['stok_saat_ini']) ?>" disabled>
                                    </div>
                                    <div class="col-12">
                                        <label for="tanggal_keluar" class="form-label">Tanggal Keluar <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="tanggal_keluar" id="tanggal_keluar" value="<?= esc($barangKeluar['tanggal_keluar']) ?>" disabled>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-grid">
                                            <button class="btn bsb-btn-xl btn-primary" type="submit">Back</button>
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

    <script>
        $(document).ready(function() {
            // Get all satuan barang
            $.ajax({
                type: 'GET',
                url: '<?= base_url('transaksi-barang-keluar/create') ?>',
                dataType: 'json',
                success: function(data) {
                    var jenisBarang = <?= json_encode($jenisBarang) ?>;

                    $.each(jenisBarang, function(index, jenis) {
                        $('#jenis_satuan').append('<option value="' + jenis.id + '">' + jenis.nama_satuan + '</option>');
                    });
                },
            });
        });
    </script>
</body>

</html>