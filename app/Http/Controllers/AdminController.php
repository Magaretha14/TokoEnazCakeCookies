<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Models\Kategori;
use App\Models\Produk;
use App\Models\SubKategori;
use App\Models\User;

class AdminController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data = [
            'title' => 'Admin Toko'
        ];
        return view('contents.admin.home', $data);
    }

    // produk
    public function produk(Request $request)
    {
        $reqsearch = $request->get('search');
        $produkdb = Produk::leftJoin('kategori', 'produk.id_kategori', '=', 'kategori.id')
            ->leftJoin('sub_kategori', 'produk.id_sub_kategori', '=', 'sub_kategori.id')
            ->select('sub_kategori.nama_subkategori', 'kategori.nama_kategori as nama_kategori', 'produk.*')
            ->when($reqsearch, function ($query, $reqsearch) {
                $search = '%' . $reqsearch . '%';
                return $query->whereRaw('nama_subkategori like ? or nama_produk like ?', [
                    $search, $search
                ]);
            });

        $data = [
            'title'     => 'Data Produk',
            'kategori'  => Kategori::all(),
            'subkategori' => SubKategori::all(), // Menambahkan data subkategori
            'produk'    => $produkdb->latest()->paginate(5),
            'request'   => $request
        ];

        return view('contents.admin.produk', $data);
    }


    public function edit_produk(Request $request)
    {
        $produk = Produk::findOrFail($request->get('id'));

        $kategori = Kategori::all();
        $subkategori = SubKategori::all();
        // if ($produk->id_kategori) {
        //     $subkategori = SubKategori::where('id_kategori', $produk->id_kategori)->get();
        // } else {
        //     $subkategori = collect(); // Jika kategori baru, subkategori dikosongkan
        // }

        $data = [
            'edit' => $produk,
            'subkategori' => $subkategori,
            'kategori' => $kategori,
        ];
        // dd($produk);
        return view('components.admin.produk.edit', $data);
    }

    // data proses produk
    public function create_produk(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            "id_kategori"   => "required",
            "gambar"        => "required|image|max:1024",
            "nama_produk"   => "required",
            "deskripsi"     => "required",
            "harga_jual"    => "required",
            "is_best_seller" => "boolean",
            "id_sub_kategori" => "required",
        ]);

        if ($validator->passes()) {

            $image = $request->file('gambar');
            $input['imagename'] = 'produk_' . time() . '.' . $image->getClientOriginalExtension();

            $destinationPath = storage_path('app/public/gambar');
            $image->move($destinationPath, $input['imagename']);

            Produk::insert([
                'id_kategori'   => $request->get("id_kategori"),
                'gambar'        => $input['imagename'],
                'nama_produk'   => $request->get("nama_produk"),
                'deskripsi'     => $request->get("deskripsi"),
                'harga_jual'    => $request->get("harga_jual"),
                'created_at'    => date('Y-m-d H:i:s'),
                'is_best_seller' => false,
                'id_sub_kategori' => $request->get('id_sub_kategori'),
                //'is_best_seller' => $request->get("is_best_seller"),
            ]);
            return redirect()->back()->with("success", " Berhasil Insert Data ! ");
        } else {
            return redirect()->back()->withErrors($validator)->with("failed", " Gagal Insert Data ! ");
        }
    }

    public function update_produk(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            "id"            => "required",
            "id_kategori"   => "required",
            "nama_produk"   => "required",
            "deskripsi"     => "required",
            "harga_jual"    => "required",
            "id_sub_kategori" => "required",
            //"is_best_seller" => "required",
        ]);

        if ($validator->passes()) {
            $produkdb = Produk::findorFail($request->get('id'));
            if ($request->file('gambar')) {
                $validator = \Validator::make($request->all(), [
                    "gambar" => "required|image|max:1024",
                ]);
                if ($validator->passes()) {
                    $image = $request->file('gambar');
                    $input['imagename'] = 'produk_' . time() . '.' . $image->getClientOriginalExtension();

                    $destinationPath = storage_path('app/public/gambar');
                    $image->move($destinationPath, $input['imagename']);
                    $gambar = $input['imagename'];
                } else {
                    return redirect()->back()->withErrors($validator)->with("failed", " Gagal Update Data ! ");
                }
            } else {
                $gambar = $produkdb->gambar;
            }

            $produkdb->update([
                'id_kategori'   => $request->get("id_kategori"),
                "id_sub_kategori" => $request->get("id_sub_kategori"),
                'gambar'        => $gambar,
                'nama_produk'   => $request->get("nama_produk"),
                'deskripsi'     => $request->get("deskripsi"),
                'harga_jual'    => $request->get("harga_jual"),
                'updated_at'    => date('Y-m-d H:i:s'),

                //'is_best_seller' => $request->get("is_best_seller"),
            ]);

            // Produk::where('id', $request->get('id'))->update($produkdb);

            return redirect('admin/produk')->with("success", " Berhasil Update Data Produk " . $request->get("nama_produk") . ' !');
        } else {
            return redirect()->back()->withErrors($validator)->with("failed", " Gagal Update Data ! ");
        }
    }

    public function delete_produk(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);
        $produk->delete();
        return redirect()->back()->with("success", " Berhasil Delete Data Produk ! ");
    }

    public function make_bestseller(Produk $produk, Request $request, $id)
    {
        // $produk->id_kategori = $request->get('id_kategori');
        $produk = Produk::findOrFail($id);
        $produk->timestamps = false;
        $produk->is_best_seller = true;
        $produk->save();
        return back()->with('success', 'Ini adalah produk Best Seller!');
    }

    public function remove_bestseller(Produk $produk, $id)
    {
        $produk = Produk::findOrFail($id);
        if ($produk->id != 1) {
            $produk->timestamps = false;
            $produk->is_best_seller = false;
            $produk->save();
            return back()->with('success', 'Produk ini bukan Best Seller!');
        } else {
            return redirect()->route('contents.admin.produk');
        }
    }

    // kategori
    public function kategori(Request $request)
    {
        if (!empty($request->get('id'))) {
            $edit = Kategori::findOrFail($request->get('id'));
        } else {
            $edit = '';
        }

        $data = [
            'title'     => 'Data Kategori',
            'kategori'  => Kategori::paginate(5),
            'edit'      => $edit,
            'request'   => $request
        ];
        return view('contents.admin.kategori', $data);
    }

    // data proses kategori
    public function create_kategori(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            "nama_kategori" => "required",
        ]);
        if ($validator->passes()) {
            Kategori::insert([
                'nama_kategori' => $request->get("nama_kategori"),
                'created_at'    => date('Y-m-d H:i:s'),
            ]);
            return redirect()->back()->with("success", " Berhasil Insert Data ! ");
        } else {
            return redirect()->back()->withErrors($validator)->with("failed", " Gagal Insert Data ! ");
        }
    }

    public function update_kategori(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            "id"            => "required",
            "nama_kategori" => "required",
        ]);
        if ($validator->passes()) {
            Kategori::findOrFail($request->get('id'))->update([
                'nama_kategori' => $request->get("nama_kategori"),
                'update_at' => date('Y-m-d H:i:s'),
            ]);
            return redirect()->back()->with("success", " Berhasil Update Data ! ");
        } else {
            return redirect()->back()->withErrors($validator)->with("failed", " Gagal Update Data ! ");
        }
    }


    public function delete_kategori(Request $request, $id)
    {
        $kategori = Kategori::findOrFail($id);
        $kategori->delete();
        return redirect()->back()->with("success", " Berhasil Delete Data ! ");
    }

    public function subkategori(Request $request)
    {
        $subkategoriQuery = SubKategori::leftJoin('kategori', 'sub_kategori.id_kategori', '=', 'kategori.id')
            ->select('kategori.nama_kategori', 'sub_kategori.*');

        $edit = '';

        if (!empty($request->get('id'))) {
            $edit = SubKategori::findOrFail($request->get('id'));
        }

        $subkategori = $subkategoriQuery->paginate(5);

        $data = [
            'title'       => 'Data Sub Kategori',
            'subkategori' => $subkategori,
            'kategori'    => Kategori::all(),
            'edit'        => $edit,
            'request'     => $request
        ];

        return view('contents.admin.subkategori', $data);
    }


    // data proses kategori
    public function create_subkategori(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            "id_kategori" => "required",
            "nama_subkategori" => "required",
        ]);
        if ($validator->passes()) {
            SubKategori::insert([
                'id_kategori' => $request->get('id_kategori'),
                'nama_subkategori' => $request->get("nama_subkategori"),
                'created_at'    => date('Y-m-d H:i:s'),
            ]);
            return redirect()->back()->with("success", " Berhasil Insert Data ! ");
        } else {
            return redirect()->back()->withErrors($validator)->with("failed", " Gagal Insert Data ! ");
        }
    }

    public function update_subkategori(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            // "id"               => "required", // Ensure the id exists in the subkategori table
            "id_kategori"      => "required",
            "nama_subkategori" => "required",
        ]);

        if ($validator->passes()) {
            try {
                SubKategori::findOrFail($request->get('id'))->update([
                    'nama_subkategori' => $request->get("nama_subkategori"),
                    'id_kategori'      => $request->get("id_kategori"),
                    'updated_at'       => now(),
                ]);

                return redirect()->back()->with("success", "Berhasil Update Data!");
            } catch (\Exception $e) {
                return redirect()->back()->with("failed", "Gagal Update Data! " . $e->getMessage());
            }
        } else {
            return redirect()->back()->withErrors($validator)->with("failed", "Gagal Update Data!");
        }
    }



    public function delete_subkategori(Request $request, $id)
    {
        $subkategori = SubKategori::findOrFail($id);
        $subkategori->delete();
        return redirect()->back()->with("success", " Berhasil Delete Data ! ");
    }


    // profil
    public function profil(Request $request)
    {
        $data = [
            'title' => 'Data Produk',
            'edit' => User::findOrFail(auth()->user()->id),
            'request' => $request
        ];
        return view('contents.admin.profil', $data);
    }

    // data proses profil
    public function update_profil(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            "name"                  => "required",
            "email"                 => "required",
            "password"              => "required|min:6",
            "password_confirmation" => "required|min:6",
        ]);

        if ($validator->passes()) {
            if ($request->get("password") == $request->get("password_confirmation")) {
                User::findOrFail(auth()->user()->id)->update([
                    'name'          => $request->get("name"),
                    'email'         => $request->get("email"),
                    'phone'         => $request->get("phone"),
                    'address'       => $request->get("address"),
                    'password'      => Hash::make($request->get("password")),
                    'updated_at'    => date('Y-m-d H:i:s'),
                ]);
                return redirect()->back()->with("success", " Berhasil Update Data ! ");
            } else {
                return redirect()->back()->with("failed", "Confirm Password Tidak Sama !");
            }
        } else {
            return redirect()->back()->withErrors($validator)->with("failed", " Gagal Update Data ! ");
        }
    }
}
