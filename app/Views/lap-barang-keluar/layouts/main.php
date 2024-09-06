<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Indoguna Gudang</title>

    <!-- Custom fonts for this template-->
    <link href="<?= base_url('vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?= base_url('css/sb-admin-2.min.css') ?>" rel="stylesheet">

    <!-- Data Tables -->
    <link href="<?= base_url('vendor/datatables/dataTables.bootstrap4.min.css') ?>" rel="stylesheet">

    <link rel="icon" type="image/x-icon" href="<?= base_url('img/favicon/warehouse.png') ?>">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">


</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Components Sidebar -->
        <?= $this->include('template/sidebar') ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Components Navbar -->
                <?= $this->include('template/navbar') ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-flex align-items-center justify-content-end">
                        <a href="<?= base_url('laporan-barang-keluar/generatePDF'); ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                            <i class="fas fa-solid fa-download fa-sm text-white-50"></i> Generate Report
                        </a>
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Laporan Barang Keluar -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Laporan Barang Keluar </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_bk; ?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-solid fa-warehouse fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Jenis Satuan -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Laporan Barang keluar</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Kode Transaksi</th>
                                            <th>Nama Barang</th>
                                            <th>Jumlah keluar</th>
                                            <th>Stok Saat Ini</th>
                                            <th>Tanggal keluar</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($barangKeluar)) : ?>
                                            <?php foreach ($barangKeluar as $index => $bk) : ?>
                                                <tr>
                                                    <td><?= $index + 1; ?></td>
                                                    <td><?= $bk['kode_transaksi']; ?></td>
                                                    <td><?= $bk['nama_barang']; ?></td>
                                                    <td><?= $bk['jumlah_keluar']; ?></td>
                                                    <td><?= $bk['stok_saat_ini']; ?></td>
                                                    <td><?= $bk['tanggal_keluar']; ?></td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <!-- Button for viewing details -->
                                                            <a href="<?= base_url('laporan-barang-keluar/detail/' . $bk['id']) ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-3">
                                                                <i class="fas fa-solid fa-eye fa-sm text-white-50"></i>
                                                            </a>

                                                            <!-- Button for editing -->
                                                            <a href="<?= base_url('laporan-barang-keluar/edit/' . $bk['id']) ?>" class="btn btn-sm btn-warning shadow-sm mr-2">
                                                                <i class="fas fa-edit fa-sm text-white-50"></i>
                                                            </a>

                                                            <!-- Button for deleting with confirmation prompt -->
                                                            <a href="<?= base_url('laporan-barang-keluar/delete/' . $bk['id']) ?>" class="btn btn-sm btn-danger shadow-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?');">
                                                                <i class="fas fa-trash fa-sm text-white-50"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            <tr>
                                                <td colspan="7" class="text-center">Data tidak ditemukan</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Components Footer -->
            <?= $this->include('template/footer') ?>

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Components Modal -->
    <?= $this->include('template/modal') ?>

    <!-- Bootstrap core JavaScript-->
    <script src="<?= base_url('vendor/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= base_url('vendor/jquery-easing/jquery.easing.min.js') ?>"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= base_url('js/sb-admin-2.min.js') ?>"></script>

    <!-- Page level plugins -->
    <script src="<?= base_url('vendor/datatables/jquery.dataTables.min.js') ?>"></script>
    <script src="<?= base_url('vendor/datatables/dataTables.bootstrap4.min.js') ?>"></script>

    <!-- Page level custom scripts -->
    <script src="<?= base_url('js/demo/datatables-demo.js') ?>"></script>

</body>

</html>