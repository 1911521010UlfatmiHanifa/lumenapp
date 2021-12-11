<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return ["Hello Hai..!!!"];
});

$router->post('/register', 'UserController@register');
$router->post('/login','AuthController@login');

$router->group(['middleware' => 'auth'], function() use ($router){
    $router->get('api/kategori', function () use ($router) {
        $listKategori = new stdClass();
        $results = app('db')->select("SELECT * FROM kategoris");
        $listKategori->kategori = $results;
        return response()->json($listKategori);
    });

    $router->get('api/user/{id}', 'UserController@show');
    $router->post('api/editDataUser/{id}', 'UserController@editDataDiri');

    $router->get('api/barang/{id_kategori}', function ($id_kategori) use ($router) {
        $listBarang = new stdClass();
        $results = app('db')->select("SELECT * FROM barangs where id_kategori=$id_kategori");
        $listBarang->barang = $results;
        return response()->json($listBarang);
    });

    $router->get('api/detail_barang/{id_barang}', function ($id_barang) use ($router) {
        $listBarang = new stdClass();
        $results = app('db')->select("SELECT * FROM barangs where id=$id_barang");
        $listBarang->barang = $results;
        return response()->json($listBarang);
    });

    $router->get('api/pesananSelesai/{id_user}', function ($id_user) use ($router) {
        $listPesanan = new stdClass();
        $results = app('db')->select("SELECT transaksis.id_transaksi, status_transaksi, waktu, 
                                    (SUM(jumlah*harga_barang))+biaya_kirim AS SUM 
                                    from transaksis 
                                    JOIN detail_transaksis ON transaksis.id_transaksi=detail_transaksis.id_transaksi 
                                    JOIN barangs ON barangs.id=detail_transaksis.id_barang 
                                    where (status_transaksi='Diterima' and id_user=$id_user) or (status_transaksi='Dibatalkan' and id_user=$id_user)
                                    GROUP BY transaksis.id_transaksi");
        $listPesanan->pesanan = $results;
        return response()->json($listPesanan);
    });

    $router->get('api/pesananProses/{id_user}', function ($id_user) use ($router) {
        $listPesanan = new stdClass();
        $results = app('db')->select("SELECT transaksis.id_transaksi, status_transaksi, waktu, 
                                    (SUM(jumlah*harga_barang))+biaya_kirim AS SUM 
                                    from transaksis 
                                    JOIN detail_transaksis ON transaksis.id_transaksi=detail_transaksis.id_transaksi 
                                    JOIN barangs ON barangs.id=detail_transaksis.id_barang where status_transaksi='Diproses' and id_user=$id_user
                                    GROUP BY transaksis.id_transaksi");
        $listPesanan->pesanan = $results;
        return response()->json($listPesanan);
    });

    $router->get('api/detail_pesanan/{id_transaksi}', function ($id_transaksi) use ($router) {
        $listDPesanan = new stdClass();
        $results = app('db')->select("SELECT to_char(waktu, 'DD-Month-YYYY') AS tanggal, TO_CHAR(waktu, 'HH:mm:ss') AS waktu, alamat,
                                    biaya_kirim, SUM(jumlah*barangs.harga_barang) AS subtotal,
                                    (SUM(jumlah*harga_barang))+biaya_kirim AS total, status_transaksi
                                    FROM transaksis JOIN detail_transaksis ON transaksis.id_transaksi=detail_transaksis.id_transaksi 
                                    JOIN barangs ON barangs.id=detail_transaksis.id_barang
                                    WHERE transaksis.id_transaksi=$id_transaksi GROUP BY transaksis.id_transaksi");
        $listDPesanan->dpesanan = $results;
        return response()->json($listDPesanan);
    });

    $router->post('api/batalkanPesanan/{id_transaksi}', 'TransaksiController@batalkanPesanan');

    $router->post('api/logout', 'AuthController@logout');
});
