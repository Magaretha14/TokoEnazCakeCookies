<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\SubKategori;
use Illuminate\Http\Request;

class SubKategoriController extends Controller
{

    public function subkategorinew(Request $request)
    {
        if(!empty($request->get('id'))){
            $edit = SubKategori::findOrFail($request->get('id'));
        }
        else{
            $edit = '';
        }

        $data = [
            'title'     => 'Data Sub Kategori',
            'subkategori'  => SubKategori::paginate(5),
            'kategori'  => Kategori::all(),
            'edit'      => $edit,
            'request'   => $request
        ];
        return view('contents.admin.subkategori', $data);
    }

    // data proses kategori
    public function create_subkategori(Request $request)
    {
        $validator = \Validator::make($request->all(),[
            "id_kategori" => "required",
            "nama_subkategori" => "required",
        ]);
        if($validator->passes()) {
            SubKategori::insert([
                'id_kategori' => $request->get('id_kategori'),
                'nama_subkategori' => $request->get("nama_subkategori"),
                'created_at'    => date('Y-m-d H:i:s'),
            ]);
            return redirect()->back()->with("success"," Berhasil Insert Data ! ");
        }
        else{
            return redirect()->back()->withErrors($validator)->with("failed"," Gagal Insert Data ! ");
        }
    }

    public function update_subkategori(Request $request)
    {
        $validator = \Validator::make($request->all(),[
            "id"            => "required",
            "nama_subkategori" => "required",
        ]);
        if($validator->passes()) {
            SubKategori::findOrFail($request->get('id'))->update([
                'nama_subkategori' => $request->get("nama_subkategori"),
                'update_at' => date('Y-m-d H:i:s'),
            ]);
            return redirect()->back()->with("success"," Berhasil Update Data ! ");
        }
        else{
            return redirect()->back()->withErrors($validator)->with("failed"," Gagal Update Data ! ");
        }
    }


    public function delete_subkategori(Request $request, $id)
    {
        $subkategori = SubKategori::findOrFail($id);
        $subkategori->delete();
        return redirect()->back()->with("success"," Berhasil Delete Data ! ");
    }

}
