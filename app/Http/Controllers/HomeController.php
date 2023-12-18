<?php

/**
 * Codekop Toko Online
 *
 * @link       https://www.codekop.com/
 * @version    1.0.1
 * @copyright  (c) 2021
 *
 * File      : HomeController.php
 * Web Name  : Toko Online
 * Developer : Fauzan Falah
 * E-mail    : fauzancodekop@gmail.com / fauzan1892@codekop.com
 *
 *
 **/

namespace App\Http\Controllers;

use App\Models\SubKategori;
use Illuminate\Http\Request;
use App\Models\Kategori;
use App\Models\Produk;
use App\Models\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function index(Request $request)
    {

        $reqsearch = $request->get('search');
        $produkdb = Produk::leftJoin('kategori', 'produk.id_kategori', '=', 'kategori.id')
            ->leftJoin('sub_kategori', 'produk.id_sub_kategori', '=', 'sub_kategori.id')
            ->select('sub_kategori.nama_subkategori', 'kategori.nama_kategori as nama_kategori', 'produk.*');
        $data = [
            'title'     => 'Enaz Cake&Cookies',
            'kategori'  => Kategori::All(),
            'subkategori' => SubKategori::All(),
            'produk'    => $produkdb->latest()->paginate(8),
        ];
        return view('contents.frontend.home', $data);
    }

    public function kategori(Request $request, $id)
    {
        $edit = Kategori::findOrFail($id);
        $produkdb = Produk::leftJoin('kategori', 'produk.id_kategori', '=', 'kategori.id')
            ->select('kategori.nama_kategori', 'produk.*')->where('produk.id_kategori', $id);
        $data = [
            'title'     => $edit->nama_kategori,
            'kategori'  => Kategori::All(),
            'subkategori' => SubKategori::All(),
            'produk'    => $produkdb->latest()->paginate(8),
        ];
        return view('contents.frontend.kategori', $data);
    }

    public function subkategori(Request $request, $id)
    {
        $edit = SubKategori::findOrFail($id);
        $produkdb = Produk::leftJoin('kategori', 'produk.id_kategori', '=', 'kategori.id')
            ->leftJoin('sub_kategori', 'produk.id_sub_kategori', '=', 'sub_kategori.id') // Tambahkan join ke subkategori
            ->select('kategori.nama_kategori', 'sub_kategori.nama_subkategori', 'produk.*') // Pilih kolom yang ingin ditampilkan
            ->where('produk.id_sub_kategori', $id);

        $data = [
            'title'     => $edit->nama_subkategori,
            'kategori'  => Kategori::All(),
            'subkategori' => SubKategori::All(),
            'produk'    => $produkdb->latest()->paginate(8),
        ];
        return view('contents.frontend.subkategori', $data);
    }

    public function search(Request $request)
    {
        $reqsearch = $request->get('keyword');
        $produkdb = Produk::leftJoin('kategori', 'produk.id_kategori', '=', 'kategori.id')
            ->select('kategori.nama_kategori', 'produk.*')
            ->when($reqsearch, function ($query, $reqsearch) {
                $search = '%' . $reqsearch . '%';
                return $query->whereRaw('nama_kategori like ? or nama_produk like ?', [
                    $search, $search
                ]);
            });
        $data = [
            'title'     => 'Cari : ' . $reqsearch,
            'kategori'  => Kategori::All(),
            'produk'    => $produkdb->latest()->paginate(8),
        ];
        return view('contents.frontend.kategori', $data);
    }

    public function produk(Request $request, $id)
    {
        $reqsearch = $request->get('keyword');
        $produkdb = Produk::leftJoin('kategori', 'produk.id_kategori', '=', 'kategori.id')
            ->select('kategori.nama_kategori', 'produk.*')->where('produk.id', $id)->first();

        if (!$produkdb) {
            abort('404');
        }

        $data = [
            'title'     => $produkdb->nama_produk,
            'kategori'  => Kategori::All(),
            'subkategori' => SubKategori::All(),
            'profil_toko' => User::find(1),
            'edit'      => $produkdb,
        ];
        return view('contents.frontend.produk', $data);
    }

    public function redir_admin()
    {
        return redirect('admin');
    }
}
