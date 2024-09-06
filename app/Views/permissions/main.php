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
                        <nav aria-label="breadcrumb">
                        </nav>
                        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-3">
                            <i class="fas fa-download fa-sm text-white-50"></i> Generate Report
                        </a>
                        <a href="<?= base_url('hak-akses-create'); ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                            <i class="fas fa-download fa-sm text-white-50"></i> Create Data
                        </a>
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Data Barang Masuk -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Hak Akses</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= esc($total_permissions) ?></div>
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
                            <h6 class="m-0 font-weight-bold text-primary">Hak Akses</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Hak Akses</th>
                                            <th>Deskripsi</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($permissions) && is_array($permissions)) : ?>
                                            <?php foreach ($permissions as $permission) : ?>
                                                <tr>
                                                    <td><?= esc($permission['permission_id']) ?></td>
                                                    <td><?= esc($permission['permission_name']) ?></td>
                                                    <td><?= esc($permission['description']) ?></td>
                                                    <td>
                                                        <div class="d-flex align-items-center justify-content">
                                                            <a href=" <?= base_url('permissions/detail/' . $permission['permission_id']) ?>" class=" d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-3">
                                                                <i class="fas fa-solid fa-eye fa-sm text-white-50"></i>
                                                            </a>
                                                            <a href="<?= base_url('permissions/edit/' . $permission['permission_id']) ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-3">
                                                                <i class="fas fa-solid fa-edit fa-sm text-white-50"></i>
                                                            </a>
                                                            <a href=" <?= base_url('permissions/delete/' . $permission['permission_id']) ?>" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                                                                <i class="fas fa-solid fa-trash fa-sm text-white-50"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            <tr>
                                                <td colspan="4">No permissions found.</td>
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