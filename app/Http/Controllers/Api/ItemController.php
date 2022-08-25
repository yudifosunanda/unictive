<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\Item;
use App\Models\Pajak;
use App\Models\ItemPajak;

class ItemController extends Controller
{
  public function getdata(){

      $data = db::table('item_pajak')
      ->join('item','item.id','item_pajak.item_id')
      ->join('pajak','pajak.id','item_pajak.pajak_id')
      ->select('item.id as id','item.nama as nama',DB::raw("concat('[',group_concat(json_object('id',pajak.id,'nama',pajak.nama,'rate',concat(pajak.rate,'%'))),']')AS pajak"))
      ->groupBy('item.id')
      ->groupBy('item.nama')
      ->get();

      $dataquery =[];
      foreach($data as $d){
      $dataquery = [
        'id'=>$d->id,
        'nama'=>$d->nama,
        'pajak'=>json_decode($d->pajak),
        ];
      }

      return $this->sendResponse($dataquery, "List Item Berhasil di ambil");

  }

  public function create(Request $request){

      $validator = Validator::make($request->all(), [
        'nama'     => 'required',
        "pajak"    => "required|array|min:2",
        "pajak.*"  => "required|distinct",
      ]);

      //check if validation fails
      if ($validator->fails()) {
        return $this->sendError("Validation Error", $validator->errors());
      }

      //input master Item
      $item = Item::create([
        'nama' => $request->nama,
      ]);

      $pajak = Pajak::find($request->pajak);
      $item->pajaks()->attach($pajak);

      return $this->sendResponse($item, "Item Berhasil di Tambah");
  }

  public function update(Request $request,$id){

      $validator = Validator::make($request->all(), [
        'nama'     => 'required',
        "pajak"    => "required|array|min:2",
        "pajak.*"  => "required|distinct",
      ]);

      $data  =  Item::find($id);

      //check if data exist
      if(!$data){
        return $this->sendError("Unknown Data", "Data tidak ditemukan");
      }else{
      //update table item
        $data->nama = $request->nama;
        $data->save();

      //update table junction
       $pajak = Pajak::find($request->pajak);
       $data->pajaks()->sync($pajak);

      }

     return $this->sendResponse($data, "Item Berhasil di Update");

  }

  public function delete($id){

      $data =  Item::find($id);

      //check if data exist
      if(!$data){
        return $this->sendError("Unknown Data", "Data tidak ditemukan");
      }else{
        $data->delete();
        $data->pajaks()->wherePivot('item_id',$id)->detach();
      }

      return $this->sendResponse($data, "Item Berhasil di Delete");

  }
}
