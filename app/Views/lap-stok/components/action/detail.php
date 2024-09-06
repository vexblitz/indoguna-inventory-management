<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Data</title>

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
                                        <h2 class="h3">Detail Data</h2>
                                        <h3 class="fs-6 fw-normal text-secondary m-0">Menampilkan Data Dengan Detail</h3>
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
                            <form action="<?= base_url('laporan-stok/detail/' . $barang['id']) ?>" method="post">
                                <?= csrf_field() ?>

                                <div class="row gy-3 gy-md-4 overflow-hidden">

                                    <div class="col-12">
                                        <label for="kode_barang" class="form-label">Kode Barang <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="kode_barang" id="kode_barang" value="<?= esc($barang['kode_barang']) ?>" disabled>
                                    </div>
                                    <div class="col-12">
                                        <label for="nama_barang" class="form-label">Nama Barang <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="nama_barang" id="nama_barang" value="<?= esc($barang['nama_barang']) ?>" placeholder="Isikan Nama Barang Dengan Benar" disabled>
                                    </div>
                                    <div class="col-12">
                                        <label for="harga_beli" class="form-label">Harga Beli <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="harga_beli" id="harga_beli" value="Rp. <?= number_format($barang['harga_beli'], 0, ',', '.') ?>" disabled>
                                    </div>
                                    <div class="col-12">
                                        <label for="harga_jual" class="form-label">Harga Jual <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="harga_jual" id="harga_jual" value="Rp. <?= number_format($barang['harga_jual'], 0, ',', '.') ?>" disabled>
                                    </div>
                                    <div class="col-12">
                                        <label for="kategori_barang_id">Kategori Barang <span class="text-danger">*</span></label>
                                        <select name="kategori_barang_id" id="kategori_barang_id" class="form-control" disabled>
                                            <option value="">Pilih Jenis Barang</option>
                                            <?php if (isset($kategoriBarang) && is_array($kategoriBarang)) : ?>
                                                <?php foreach ($kategoriBarang as $kb) : ?>
                                                    <option value="<?= $kb['id'] ?>" <?= $barang['kategori_barang_id'] == $kb['id'] ? 'selected' : '' ?>><?= esc($kb['nama_kategori']) ?></option>
                                                <?php endforeach ?>
                                            <?php endif ?>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label for="jenis_satuan">Jenis Satuan <span class="text-danger">*</span></label>
                                        <select name="jenis_satuan" id="jenis_satuan" class="form-control" disabled>
                                            <option value="">Pilih Jenis Barang</option>
                                            <?php if (isset($jenisBarang) && is_array($jenisBarang)) : ?>
                                                <?php foreach ($jenisBarang as $jenis) : ?>
                                                    <option value="<?= $jenis['id'] ?>" <?= $barang['jenis_satuan'] == $jenis['id'] ? 'selected' : '' ?>><?= esc($jenis['nama_satuan']) ?></option>
                                                <?php endforeach ?>
                                            <?php endif ?>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label for="stok_awal" class="form-label">Stok Awal <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="stok_awal" id="stok_awal" placeholder="Isikan Nama Kategori Dengan Benar" value="<?= esc($barang['stok_awal']) ?>" disabled>
                                    </div>
                                    <div class="col-12">
                                        <label for="stok_akhir" class="form-label">Stok Akhir<span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" name="stok_akhir" id="stok_akhir" placeholder="Isikan Nama Kategori Dengan Benar" value="<?= esc($barang['stok_akhir']) ?>" disabled>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-grid">
                                            <button class="btn bsb-btn-xl btn-primary" type="button" onclick="window.history.back();">Back</button>
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
        /* Format Rupiah */
        function formatRupiah(angka, prefix) {
            var number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix === undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
        }

        function unformatRupiah(value) {
            return value.replace(/[Rp.\s]/g, '').replace(/,/g, '.');
        }

        document.querySelectorAll('input').forEach(input => {
            if (input.id === 'harga_jual' || input.id === 'harga_beli') {
                input.addEventListener('keyup', function() {
                    this.value = formatRupiah(this.value, 'Rp. ');
                });
            }
        });

        document.querySelector('form').addEventListener('submit', function(e) {
            var harga_beli = document.getElementById('harga_beli');
            var harga_jual = document.getElementById('harga_jual');

            harga_beli.value = unformatRupiah(harga_beli.value);
            harga_jual.value = unformatRupiah(harga_jual.value);
        });
    </script>
</body>

</html>