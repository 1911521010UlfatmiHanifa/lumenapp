<?php
use Illuminate\Support\Facades\DB;

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
    $router->post('api/ubahSandi/{id}', 'UserController@ubahSandi');
    $router->post('api/hapusBarangKeranjang/{id_user}/{id_barang}' , function ($id_user, $id_barang) use ($router){
        $results = DB::table('keranjangs')->where('id_user', $id_user)->where('id_barang', $id_barang)->delete();
        return response()->json(['message' => 'Barang di Keranjang Berhasil Dihapus']);
    });

    $router->get('api/barang/{id_kategori}', function ($id_kategori) use ($router) {
        $listBarang = new stdClass();
        $results = app('db')->select("SELECT * FROM barangs where id_kategori=$id_kategori");
        $listBarang->barang = $results;
        return response()->json($listBarang);
    });

    $router->get('api/keranjang/{id_user}', function ($id_user) use ($router) {
        $listKeranjang = new stdClass();
        $results = app('db')->select("SELECT id_barang, nama_barang, jumlah, gambar, harga_barang FROM keranjangs join barangs on keranjangs.id_barang=barangs.id where id_user=$id_user");
        $listKeranjang->keranjang = $results;
        return response()->json($listKeranjang);
    });

    $router->get('api/detail_barang/{id_barang}', function ($id_barang) use ($router) {
        $listBarang = new stdClass();
        $results = app('db')->select("SELECT * FROM barangs where id=$id_barang");
        $listBarang->barang = $results;
        return response()->json($listBarang);
    });

    $router->get('api/pesananSelesai/{id_user}', function ($id_user) use ($router) {
        $listPesanan = new stdClass();
        $results = app('db')->select("SELECT transaksis.id, status_transaksi, waktu, 
                                    (SUM(jumlah*harga_barang))+biaya_kirim AS SUM 
                                    from transaksis 
                                    JOIN detail_transaksis ON transaksis.id=detail_transaksis.id_transaksi 
                                    JOIN barangs ON barangs.id=detail_transaksis.id_barang 
                                    where (status_transaksi='Diterima' or status_transaksi='Dibatalkan') and id_user=$id_user
                                    GROUP BY transaksis.id");
        $listPesanan->pesanan = $results;
        return response()->json($listPesanan);
    });

    $router->get('api/pesananProses/{id_user}', function ($id_user) use ($router) {
        $listPesanan = new stdClass();
        $results = app('db')->select("SELECT transaksis.id, status_transaksi, waktu, 
                                    (SUM(jumlah*harga_barang))+biaya_kirim AS SUM 
                                    from transaksis 
                                    JOIN detail_transaksis ON transaksis.id=detail_transaksis.id_transaksi 
                                    JOIN barangs ON barangs.id=detail_transaksis.id_barang where status_transaksi='Diproses' and id_user=$id_user
                                    GROUP BY transaksis.id");
        $listPesanan->pesanan = $results;
        return response()->json($listPesanan);
    });

    $router->get('api/detail_pesanan/{id_transaksi}', function ($id_transaksi) use ($router) {
        $listDPesanan = new stdClass();
        $results = app('db')->select("SELECT to_char(waktu, 'DD-Month-YYYY') AS tanggal, TO_CHAR(waktu, 'HH:mm:ss') AS waktu, alamat,
                                    biaya_kirim, SUM(jumlah*barangs.harga_barang) AS subtotal,
                                    (SUM(jumlah*harga_barang))+biaya_kirim AS total, status_transaksi
                                    FROM transaksis JOIN detail_transaksis ON transaksis.id=detail_transaksis.id_transaksi 
                                    JOIN barangs ON barangs.id=detail_transaksis.id_barang
                                    WHERE transaksis.id=$id_transaksi GROUP BY transaksis.id");
        $listDPesanan->dpesanan = $results;
        return response()->json($listDPesanan);
    });

    $router->get('api/produkPesanan/{id_transaksi}', function ($id_transaksi) use ($router) {
        $listProdukPsn = new stdClass();
        $results = app('db')->select("SELECT id_barang, nama_barang, harga_barang, jumlah, gambar FROM detail_transaksis 
                                        JOIN barangs ON barangs.id=detail_transaksis.id_barang WHERE id_transaksi=$id_transaksi");
        $listProdukPsn->prodpesan = $results;
        return response()->json($listProdukPsn);
    });

    $router->post('api/batalkanPesanan/{id_transaksi}', 'TransaksiController@batalkanPesan');

    $router->post('api/logout', 'AuthController@logout');
});
