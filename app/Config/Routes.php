<?php

namespace Config;

use CodeIgniter\Commands\Utilities\Routes;
use CodeIgniter\Config\BaseConfig;


$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */



// Route to the login page
$routes->get('/', 'AuthController::index'); // Halaman login
$routes->get('/login', 'AuthController::index'); // Halaman login
$routes->post('/auth/login', 'AuthController::login'); // Proses login
$routes->get('/auth/logout', 'AuthController::logout'); // Proses logout

$routes->get('/', 'DashboardController::index', ['filter' => 'setUsername']);

$routes->group('', ['filter' => 'auth'], function ($routes) {
    $routes->get('dashboard', 'DashboardController::index');

    // Routes untuk Administrator
    $routes->group('admin', function ($routes) {
        $routes->get('users', 'AdminController::manageUsers');
        // Tambahkan routes lainnya untuk Administrator
    });

    // Routes untuk Manager
    $routes->group('manager', function ($routes) {
        $routes->get('reports', 'ManagerController::reports');
        // Tambahkan routes lainnya untuk Manager
    });

    // Routes untuk Staff Gudang
    $routes->group('staff', function ($routes) {
        $routes->get('inventory', 'StaffController::inventory');
        // Tambahkan routes lainnya untuk Staff Gudang
    });

    // Routes untuk Kepala Gudang
    $routes->group('kepala', function ($routes) {
        $routes->get('overview', 'KepalaController::overview');
        // Tambahkan routes lainnya untuk Kepala Gudang
    });
});



// Routes for Register
$routes->group('register', function ($routes) {
    $routes->get('/', 'RegisterController::index'); // Display all categories
    $routes->get('create', 'RegisterController::create'); // Show form to create new category
    $routes->post('store', 'RegisterController::store'); // Handle form submission to create a new category
    $routes->get('edit/(:num)', 'RegisterController::edit/$1'); // Show form to edit an existing category
    $routes->post('update', 'RegisterController::update'); // Handle form submission to update an existing category
    $routes->get('detail/(:num)', 'RegisterController::detail/$1'); // Show details of a category
    $routes->get('delete/(:num)', 'RegisterController::delete/$1'); // Delete a category
});

// Routes for Satuan Barang
$routes->group('satuan-barang', function ($routes) {
    $routes->get('/', 'SatuanBarang::index'); // Display all categories
    $routes->get('create', 'SatuanBarang::create'); // Show form to create new category
    $routes->post('store', 'SatuanBarang::store'); // Handle form submission to create a new category
    $routes->get('edit/(:num)', 'SatuanBarang::edit/$1'); // Show form to edit an existing category
    $routes->post('update', 'SatuanBarang::update'); // Handle form submission to update an existing category
    $routes->get('detail/(:num)', 'SatuanBarang::detail/$1'); // Show details of a category
    $routes->get('delete/(:num)', 'SatuanBarang::delete/$1'); // Delete a category
    $routes->get('generatePDF', 'SatuanBarang::generatePDF'); // Generate report
});

// Routes for KategoriBarang
$routes->group('kategori-barang', function ($routes) {
    $routes->get('/', 'KategoriBarang::index'); // Display all categories
    $routes->get('create', 'KategoriBarang::create'); // Show form to create new category
    $routes->post('store', 'KategoriBarang::store'); // Handle form submission to create a new category
    $routes->get('edit/(:num)', 'KategoriBarang::edit/$1'); // Show form to edit an existing category
    $routes->post('update', 'KategoriBarang::update'); // Handle form submission to update an existing category
    $routes->get('detail/(:num)', 'KategoriBarang::detail/$1'); // Show details of a category
    $routes->get('delete/(:num)', 'KategoriBarang::delete/$1'); // Delete a category
    $routes->get('generatePDF', 'KategoriBarang::generatePDF'); // Generate report
});

// Routes for Hak Akses
$routes->group('hak-akses', function ($routes) {
    $routes->get('/', 'Permissions::index'); // Display all categories
    $routes->get('create', 'Permissions::create'); // Show form to create new category
    $routes->post('store', 'Permissions::store'); // Handle form submission to create a new category
    $routes->get('edit/(:num)', 'Permissions::edit/$1'); // Show form to edit an existing category
    $routes->post('update', 'Permissions::update'); // Handle form submission to update an existing category
    $routes->get('detail/(:num)', 'Permissions::detail/$1'); // Show details of a category
    $routes->get('delete/(:num)', 'Permissions::delete/$1'); // Delete a category
});

// Routes for Roles
$routes->group('roles', function ($routes) {
    $routes->get('/', 'Roles::index'); // Display all categories
    $routes->get('create', 'Roles::create'); // Show form to create new category
    $routes->post('store', 'Roles::store'); // Handle form submission to create a new category
    $routes->get('edit/(:num)', 'Roles::edit/$1'); // Show form to edit an existing category
    $routes->post('update', 'Roles::update'); // Handle form submission to update an existing category
    $routes->get('detail/(:num)', 'Roles::detail/$1'); // Show details of a category
    $routes->get('delete/(:num)', 'Roles::delete/$1'); // Delete a category
});

// Routes for Users
$routes->group('users', function ($routes) {
    $routes->get('/', 'UserController::index'); // Display all categories
    $routes->get('create', 'UserController::create'); // Show form to create new category
    $routes->post('store', 'UserController::store'); // Handle form submission to create a new category
    $routes->get('edit/(:num)', 'UserController::edit/$1'); // Show form to edit an existing category
    $routes->post('update', 'UserController::update'); // Handle form submission to update an existing category
    $routes->get('detail/(:num)', 'UserController::detail/$1'); // Show details of a category
    $routes->get('delete/(:num)', 'UserController::delete/$1'); // Delete a category
});

// Routes for Data Barang
$routes->group('data-barang', function ($routes) {
    $routes->get('/', 'BarangController::index'); // Display all categories
    $routes->get('create', 'BarangController::create'); // Show form to create new category
    $routes->post('store', 'BarangController::store'); // Handle form submission to create a new category
    $routes->get('edit/(:num)', 'BarangController::edit/$1'); // Show form to edit an existing category
    $routes->post('update/(:num)', 'BarangController::update/$1'); // Handle form submission to update an existing category
    $routes->get('detail/(:num)', 'BarangController::detail/$1'); // Show details of a category
    $routes->get('delete/(:num)', 'BarangController::delete/$1'); // Delete a category
    $routes->get('generatePDF', 'BarangController::generatePDF'); // Generate report
});

// Routes for Transaksi Barang Masuk
$routes->group('transaksi-barang-masuk', function ($routes) {
    $routes->get('/', 'TransaksiBarangMasuk::index'); // Display all categories
    $routes->get('create', 'TransaksiBarangMasuk::create'); // Show form to create new category
    $routes->post('store', 'TransaksiBarangMasuk::store'); // Handle form submission to create a new category
    $routes->get('edit/(:num)', 'TransaksiBarangMasuk::edit/$1'); // Show form to edit an existing category
    $routes->post('update/(:num)', 'TransaksiBarangMasuk::update/$1'); // Handle form submission to update an existing category
    $routes->get('detail/(:num)', 'TransaksiBarangMasuk::detail/$1'); // Show details of a category
    $routes->get('delete/(:num)', 'TransaksiBarangMasuk::delete/$1'); // Delete a category
    $routes->get('generatePDF', 'TransaksiBarangMasuk::generatePDF'); // Generate report
});

// Routes for Transaksi Barang Keluar
$routes->group('transaksi-barang-keluar', function ($routes) {
    $routes->get('/', 'TransaksiBarangKeluar::index'); // Display all categories
    $routes->get('create', 'TransaksiBarangKeluar::create'); // Show form to create new category
    $routes->post('store', 'TransaksiBarangKeluar::store'); // Handle form submission to create a new category
    $routes->get('edit/(:num)', 'TransaksiBarangKeluar::edit/$1'); // Show form to edit an existing category
    $routes->post('update/(:num)', 'TransaksiBarangKeluar::update/$1'); // Handle form submission to update an existing category
    $routes->get('detail/(:num)', 'TransaksiBarangKeluar::detail/$1'); // Show details of a category
    $routes->get('delete/(:num)', 'TransaksiBarangKeluar::delete/$1'); // Delete a category
    $routes->get('generatePDF', 'TransaksiBarangKeluar::generatePDF'); // Generate report
});

// Routes for Laporan Stok
$routes->group('laporan-stok', function ($routes) {
    $routes->get('/', 'LaporanStok::index'); // Tampilkan daftar semua barang
    $routes->get('edit/(:num)', 'LaporanStok::edit/$1'); // Tampilkan form untuk mengedit barang
    $routes->post('update/(:num)', 'LaporanStok::update/$1'); // Proses pembaruan data barang
    $routes->get('detail/(:num)', 'LaporanStok::detail/$1'); // Tampilkan detail barang
    $routes->get('delete/(:num)', 'LaporanStok::delete/$1'); // Hapus barang
    $routes->get('generatePDF', 'LaporanStok::generatePDF'); // Generate laporan PDF
});


// Routes for Laporan Barang Masuk
$routes->group('laporan-barang-masuk', function ($routes) {
    $routes->get('/', 'LaporanBarangMasuk::index'); // Display all categories
    $routes->get('edit/(:num)', 'LaporanBarangMasuk::edit/$1'); // Show form to edit an existing category
    $routes->post('update/(:num)', 'LaporanBarangMasuk::update/$1'); // Handle form submission to update an existing category
    $routes->get('detail/(:num)', 'LaporanBarangMasuk::detail/$1'); // Show details of a category
    $routes->get('delete/(:num)', 'LaporanBarangMasuk::delete/$1'); // Delete a category
    $routes->get('generatePDF', 'LaporanBarangMasuk::generatePDF'); // Generate laporan PDF
});

// Routes for Laporan Barang Masuk
$routes->group('laporan-barang-keluar', function ($routes) {
    $routes->get('/', 'LaporanBarangKeluar::index'); // Display all categories
    $routes->get('edit/(:num)', 'LaporanBarangKeluar::edit/$1'); // Show form to edit an existing category
    $routes->post('update/(:num)', 'LaporanBarangKeluar::update/$1'); // Handle form submission to update an existing category
    $routes->get('detail/(:num)', 'LaporanBarangKeluar::detail/$1'); // Show details of a category
    $routes->get('delete/(:num)', 'LaporanBarangKeluar::delete/$1'); // Delete a category
    $routes->get('generatePDF', 'LaporanBarangKeluar::generatePDF'); // Generate laporan PDF
});

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need to be able to override any defaults in this file. Environment
 * based routes are one such time. require() additional route files
 * here to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
