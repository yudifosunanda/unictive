<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\ItemModel;
use App\Models\ItemPajakModel;

class ItemController extends Controller
{
  public function getdata(){

      $data = db::table('item_pajak')
      ->join('item','item.id','item_pajak.id_item')
      ->join('pajak','pajak.id','item_pajak.id_pajak')
      ->select('item.id as id','item.nama as nama',DB::raw("concat('[',group_concat(json_object('id',pajak.id,'nama',pajak.nama,'rate',pajak.rate)),']')AS pajak"))
      ->groupBy('item.id')
      ->groupBy('item.nama')
      ->get();

      $dataquery =[];
      foreach($data as $d){
      $dataquery = [
        'id'=>$d->id,
        'nama'=>$d->nama,
        'pajak'=>json_decode($d->pajak)
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
      $query1 = ItemModel::create([
        'nama' => $request->nama,
      ]);

      //input ke itempajak table
      $data=[];
      foreach($request->pajak as $key => $value ){
        $data[$key] = array(
          'id_item'=>$query1->id,
          'id_pajak'=>$value
        );

        $query2 = ItemPajakModel::create($data[$key]);
      }

      return $this->sendResponse($query1, "Item Berhasil di Tambah");
  }

  public function update(Request $request,$id){

      $validator = Validator::make($request->all(), [
        'nama'     => 'required',
        "pajak"    => "required|array|min:2",
        "pajak.*"  => "required|distinct",
      ]);

      $data  =  ItemModel::find($id);

      //check if data exist
      if(!$data){
        return $this->sendError("Unknown Data", "Data tidak ditemukan");
      }else{
      //update table item
        $data->nama = $request->nama;
        $data->save();

      //update table itempajak item_pajak
        $findData=ItemPajakModel::where('id_item',$id)->get();

        $count1 = count($findData);
        $count2 = count($request->pajak);

        $data=[];

        if($count1==$count2){
          foreach($findData as $key=> $value ){
            //updating itempajak table
            $data = ItemPajakModel::where(['id_item'=>$id,'id_pajak'=>$value['id_pajak']])->update([
              'id_pajak'=>$request->pajak[$key],
            ]);
          }
        }else{
          //deleting itempajak table
          ItemPajakModel::where('id_item',$id)->delete();

          //input new value itempajak table
          $data=[];
          foreach($request->pajak as $key => $value ){
            $data[$key] = array(
              'id_item'=>$id,
              'id_pajak'=>$value
            );

            $query2 = ItemPajakModel::create($data[$key]);
          }
        }

      }

     return $this->sendResponse($data, "Item Berhasil di Update");

  }

  public function delete($id){

      $data =  ItemModel::find($id);

      //check if data exist
      if(!$data){
        return $this->sendError("Unknown Data", "Data tidak ditemukan");
      }else{
        $data->delete();
        $data2=ItemPajakModel::where('id_item',$id)->delete();
      }

      return $this->sendResponse($data, "Item Berhasil di Delete");

  }
}
