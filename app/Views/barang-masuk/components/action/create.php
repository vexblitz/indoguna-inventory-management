<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Data</title>


    <!-- CSS -->
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://unpkg.com/bs-brain@2.0.4/components/registrations/registration-4/assets/css/registration-4.css">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="img/favicon/warehouse.png">

</head>

<body>
    <!-- Registration 4 - Bootstrap Brain Component -->
    <section class="p-3 p-md-4 p-xl-5">
        <div class="container">
            <div class="card border-light-subtle shadow-sm">
                <div class="row g-0">
                    <div class="col-12 col-md-6">
                        <img class="img-fluid rounded-start w-100 h-100 object-fit-cover" loading="lazy" src="<?= base_url('img/login-img.jpg') ?>" alt="BootstrapBrain Logo">
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="card-body p-3 p-md-4 p-xl-5">
                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-5">
                                        <h2 class="h3">Transaksi Barang Masuk</h2>
                                        <h3 class="fs-6 fw-normal text-secondary m-0">Isikan Dengan Jelas</h3>
                                    </div>
                                </div>

                                <?php if (session()->getFlashdata('message')) : ?>
                                    <div style="color: green;">
                                        <?= session()->getFlashdata('message') ?>
                                    </div>
                                <?php endif; ?>

                                <?php if (isset($validation)) : ?>
                                    <div style="color: red;">
                                        <?= $validation->listErrors() ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <form action="<?= base_url('transaksi-barang-masuk/store'); ?>" method="post">

                                <?= csrf_field() ?>
                                <div class="row gy-3 gy-md-4 overflow-hidden">
                                    <div class="col-12">
                                        <label for="kode_transaksi" class="form-label">Kode Transaksi <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="kode_transaksi" id="kode_transaksi" value="<?= $kode_transaksi; ?>" readonly>
                                    </div>
                                    <div class="col-12">
                                        <label for="kode_barang" class="form-label">Pilih Barang <span class="text-danger">*</span></label>
                                        <select name="kode_barang" id="kode_barang" class="form-control" required>
                                            <option value="">Pilih Barang</option>
                                            <?php foreach ($barang as $b) : ?>
                                                <option value="<?= $b['kode_barang']; ?>"><?= $b['nama_barang']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="jumlah_masuk">Jumlah Masuk</label>
                                        <input type="number" name="jumlah_masuk" id="jumlah_masuk" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="tanggal_masuk">Tanggal Masuk</label>
                                        <input type="date" name="tanggal_masuk" id="tanggal_masuk" class="form-control" required>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-grid">
                                            <button class="btn bsb-btn-xl btn-primary" type="submit">Create Data</button>
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
</body>

</html>