<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use \Auth, \Redirect, \Validator, \Input, \Session;
use App\Obat, App\Pamakologi, App\Log, App\Kartu_stok;
use Webpatser\Uuid\Uuid;

class MObatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('ckadmin');
    }

    public function index()
    {
        $obatData = Obat::with('pamakologi')->get();
        $total_stok = array();
        for ($i=0; $i <sizeof($obatData) ; $i++) {
          $total_stok[] = Kartu_stok::where('id_obat',$obatData[$i]->id)->sum('jumlah');
        }
        return view('obat.index')->with(['obatData'=>$obatData,'total_stok'=>$total_stok]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pamakologiData = Pamakologi::get();
        return view('obat.create')->with('pamakologiData',$pamakologiData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
              'nama' => 'required',
              'id_pamakologi' => 'required',
              'dosis' => 'required',
              'satuan_dosis' => 'required',
              'bentuk_sediaan' => 'required',
              'harga_jual' => 'required',
        ]);

        $obat = new Obat;
        $obat->id = Uuid::generate()->string;
        $obat->nama = strtoupper($request->nama);
        $obat->id_pamakologi = $request->id_pamakologi;
        $obat->dosis = $request->dosis;
        $obat->satuan_dosis = strtoupper($request->satuan_dosis);
        $obat->bentuk_sediaan = strtoupper($request->bentuk_sediaan);
        $obat->harga_jual = intval(str_replace(['.',','],'',$request->harga_jual));
        $obat->keterangan = $request->keterangan;
        $obat->save();

        Session::flash('message', 'Obat baru telah ditambahkan.');
        return Redirect::to('obat');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pamakologiData = Pamakologi::get();
        $obat = Obat::find($id);
        return view('obat.edit')->with(['pamakologiData'=>$pamakologiData,'obat'=>$obat]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $obat = Obat::find($id);
        $this->validate($request, [
              'nama' => 'required',
              'id_pamakologi' => 'required',
              'dosis' => 'required',
              'bentuk_sediaan' => 'required',
              'harga_jual' => 'required',
        ]);

        $obat->nama = strtoupper($request->nama);
        $obat->id_pamakologi = $request->id_pamakologi;
        $obat->dosis = $request->dosis;
        $obat->satuan_dosis = strtoupper($request->satuan_dosis);
        $obat->bentuk_sediaan = strtoupper($request->bentuk_sediaan);
        $old_harga_jual = $obat->harga_jual;
        $obat->harga_jual = intval(str_replace(['.',','],'',$request->harga_jual));
        $obat->keterangan = $request->keterangan;

        if($old_harga_jual != $obat->harga_jual){
            $log = new Log;
            $log->id_obat = $obat->id;
            $log->jenis = "Harga Jual";
            if($old_harga_jual > $obat->harga_jual)
                $log->keterangan = "harga jual turun dari Rp ".number_format($old_harga_jual,2,",",".")." menjadi Rp".number_format($obat->harga_jual,2,",",".").".";
            else
                $log->keterangan = "harga jual naik dari Rp ".number_format($old_harga_jual,2,",",".")." menjadi Rp".number_format($obat->harga_jual,2,",",".").".";
            $log->save();
        }

        $obat->save();

        Session::flash('message', 'Obat baru telah diupdate.');
        return Redirect::to('obat');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      $obat = Obat::find($id);
      Session::flash('message', 'Obat '.$obat->nama.' telah berhasil dihapus.');
      $obat->delete();
      return Redirect::to('obat');
    }
}
