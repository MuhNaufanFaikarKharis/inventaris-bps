<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Halaman utama
$routes->get('/', 'Inventory::index');

// --- AUTHENTICATION ---
$routes->get('/login', 'LoginController::index');
$routes->post('/login-process', 'LoginController::process');
$routes->get('/forgot-password', 'LoginController::forgot');
$routes->post('/forgot-process', 'LoginController::forgotProcess');
$routes->get('/logout', 'LoginController::logout');

// Rute Registrasi
$routes->get('/register', 'RegisterController::index');
$routes->post('/register-process', 'RegisterController::process');
// [TAMBAHAN BARU]: Rute untuk halaman menunggu konfirmasi dari Superadmin
$routes->get('/menunggu-konfirmasi', 'RegisterController::waitingConfirmation');

// --- GLOBAL PROFIL ROUTE (Taruh di luar grup agar bisa diakses semua role) ---
$routes->get('profil', 'Inventory::profil_user');
$routes->post('update-profil', 'Inventory::update_profil');

// --- (GLOBAL NOTIFICATION) ---
$routes->get('notification', 'Notification::index');
$routes->get('notification/markRead/(:num)', 'Notification::markAsRead/$1');
// Tambahkan ini agar URL notification/readAll bisa dikenali
$routes->get('notification/readAll', 'Inventory::markAllAsRead');


// --- 1. GROUP SUPERADMIN ---
$routes->group('superadmin', function ($routes) {
    $routes->get('dashboard', 'Inventory::index');
    $routes->get('stok', 'Inventory::stok_management');
    $routes->get('validasi', 'Inventory::validasi_request');
    $routes->get('laporan', 'Inventory::laporan_view');
    $routes->post('save_tambah', 'Inventory::save_tambah');
    $routes->get('update_status/(:num)/(:any)', 'Inventory::update_status/$1/$2');
    $routes->get('carousel', 'Inventory::carousel_view');
    $routes->post('save_carousel', 'Inventory::save_carousel');
    $routes->get('delete_carousel/(:num)', 'Inventory::delete_carousel/$1');
    $routes->get('categories', 'CategoryController::index');
    $routes->post('categories/save', 'CategoryController::save');
    $routes->get('categories/delete/(:num)', 'CategoryController::delete/$1');
    
    // Manajemen User (Di sinilah Superadmin memvalidasi akun pending & memilih role)
    $routes->get('users', 'Inventory::user_management');
    $routes->post('users/save', 'Inventory::user_save'); 
    $routes->get('users/delete/(:num)', 'Inventory::user_delete/$1');
    
    $routes->get('riwayat_opname', 'Inventory::riwayat_opname');
    $routes->post('save_opname', 'Inventory::save_opname');
    $routes->get('stok/delete/(:num)', 'Inventory::delete_stok/$1');
});

// --- 2. GROUP SUPERVISOR ---
$routes->group('supervisor', function ($routes) {
    $routes->get('dashboard', 'Inventory::index');
    $routes->get('delete/(:num)', 'Inventory::delete/$1');
});

// --- 3. GROUP STAFF ---
$routes->group('staff', function ($routes) {
    $routes->get('dashboard', 'Inventory::index');
    $routes->get('request', 'Inventory::request_view');
    $routes->post('save_request', 'Inventory::save_request');
    $routes->get('stok', 'Inventory::stok_management');
    $routes->get('laporan', 'Inventory::laporan_staff');
    $routes->post('konfirmasi_terima/(:num)', 'Inventory::konfirmasi_terima/$1');
});

// --- GLOBAL ROUTES ---
$routes->get('stok', 'Inventory::stok_management');
// Rute laporan global dihapus agar tidak bentrok dengan superadmin/laporan