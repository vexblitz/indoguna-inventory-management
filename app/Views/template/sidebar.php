<?php
$session = session();
$role_id = $session->get('role_id'); // Ambil role_id dari session
?>

<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url('/') ?>">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fas-solid fa-industry"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Indoguna</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="<?= base_url('/dashboard') ?>">
            <i class="fas fa-fw fa-home"></i>
            <span>Dashboard</span></a>
    </li>

    <?php if ($role_id == 1 || $role_id == 3): ?>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
            Informasi
        </div>

        <!-- Nav Item - Pages Collapse Menu -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                <i class="fas fa-fw fa-cog"></i>
                <span>Barang</span>
            </a>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="kategori-barang">Kategori Barang</a>
                    <a href="satuan-barang" class="collapse-item">Satuan Barang</a>
                    <a href="data-barang" class="collapse-item">Data Barang</a>
                </div>
            </div>
        </li>
    <?php endif; ?>

    <?php if ($role_id == 1 || $role_id == 3): ?>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
            TRANSAKSI
        </div>
        <!-- Barang Masuk -->
        <li class="nav-item">
            <a class="nav-link" href="transaksi-barang-masuk">
                <i class="fas fa-solid fa-box-open"></i>
                <span>Barang Masuk</span></a>
        </li>

        <!-- Barang Keluar -->
        <li class="nav-item">
            <a class="nav-link" href="transaksi-barang-keluar">
                <i class="fas fa-solid fa-dolly"></i>
                <span>Barang Keluar</span></a>
        </li>
    <?php endif; ?>

    <?php if ($role_id == 1 || $role_id == 2 || $role_id == 4): ?>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
            Laporan
        </div>

        <!-- Laporan Stok -->
        <li class="nav-item">
            <a class="nav-link" href="laporan-stok">
                <i class="fas fa-solid fa-clipboard-list"></i>
                <span>Laporan Stok</span></a>
        </li>

        <!-- Laporan Barang Masuk -->
        <li class="nav-item">
            <a class="nav-link" href="laporan-barang-masuk">
                <i class="fas fa-solid fa-file-import"></i>
                <span>Laporan Barang Masuk</span></a>
        </li>
        <!-- Laporan Barang Keluar -->
        <li class="nav-item">
            <a class="nav-link" href="laporan-barang-keluar">
                <i class="fas fa-solid fa-file-export"></i>
                <span>Laporan Barang Keluar</span></a>
        </li>
    <?php endif; ?>

    <?php if ($role_id == 1 || $role_id == 2): ?>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
            MANAJEMEN USER
        </div>

        <!-- Users -->
        <li class="nav-item">
            <a class="nav-link" href="users">
                <i class="fas fa-solid fa-users"></i>
                <span>Users</span></a>
        </li>

        <!-- Roles -->
        <li class="nav-item">
            <a class="nav-link" href="roles">
                <i class="fas fa-solid fa-users"></i>
                <span>Roles</span></a>
        </li>
    <?php endif; ?>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->