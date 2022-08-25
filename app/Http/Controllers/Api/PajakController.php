<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\Pajak;
use App\Models\ItemPajak;

class PajakController extends Controller
{

  public function getdata(){

      $data = Pajak::all();

      return $this->sendResponse($data, "Pajak Berhasil di ambil");

  }

  public function create(Request $request){

      $validator = Validator::make($request->all(), [
        'nama'     => 'required',
        'rate'     => 'required|numeric',
      ]);

      //check if validation fails
      if ($validator->fails()) {
        return $this->sendError("Validation Error", $validator->errors());
      }

      $query = Pajak::create([
    		'nama' => $request->nama,
    		'rate' => $request->rate
    	]);

      return $this->sendResponse($query, "Pajak Berhasil di Tambah");
  }

  public function update(Request $request,$id){

      $this->validate($request,[
        'nama'=>'required',
        'rate'=>'required|numeric'
      ]);

      $data =  Pajak::find($id);

      if(!$data){
        return $this->sendError("Unknown Data", "Data tidak ditemukan");
      }else{
        $data->nama = $request->nama;
        $data->rate = $request->rate;
        $data->save();
      }

     return $this->sendResponse($data, "Pajak Berhasil di Update");

  }

  public function delete($id){

      $data =  Pajak::find($id);

      if(!$data){
        return $this->sendError("Unknown Data", "Data tidak ditemukan");
      }else{
        $data->delete();
        $data2=ItemPajak::where('id_pajak',$id)->delete();
      }

      return $this->sendResponse($data, "Pajak Berhasil di Delete");

  }
}
