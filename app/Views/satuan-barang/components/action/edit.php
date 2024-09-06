<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Satuan Barang</title>


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
                                        <h2 class="h3">Edit Satuan Barang</h2>
                                        <h3 class="fs-6 fw-normal text-secondary m-0">Isikan Dengan Jelas</h3>
                                    </div>
                                </div>
                            </div>
                            <form action="<?= base_url('satuan-barang/update') ?>" method="post">

                                <?= csrf_field() ?>
                                <input type="hidden" name="id" value="<?= $barang['id'] ?>">

                                <div class="row gy-3 gy-md-4 overflow-hidden">
                                    <div class="col-12">
                                        <label for="nama_satuan" class="form-label">Nama satuan <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="nama_satuan" id="nama_satuan" value="<?= $barang['nama_satuan'] ?>" required>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-grid">
                                            <button class="btn bsb-btn-xl btn-primary" type="submit">Update Data</button>
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