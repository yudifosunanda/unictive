<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\MemberModel;
use App\Models\HobbyModel;
use App\Http\Resources\MemberResource;
use Tymon\JWTAuth\Facades\JWTFactory;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use JWTAuth;


class MemberController extends Controller
{
  public function create(Request $request){

      $validator = Validator::make($request->all(), [
        "nama"     => "required",
        "email"    => "required|email|unique:App\Models\MemberModel,email",
        "phone"    => "digits_between:11,13|required|numeric|regex:/(08)/",
        "hobby"    => "required|array",
        "hobby.*"  => "required|distinct",
      ]);

      //check if validation fails
      if ($validator->fails()) {
        return $this->sendError("Validation Error", $validator->errors());
      }

        //input member
        $member = new MemberModel;
        $member->nama =  $request->nama;
        $member->email =  $request->email;
        $member->phone =  $request->phone;
        $member->save();
        $id = $member->id;

        $hobbies=$request->hobby;

        foreach ($hobbies as $value) {
          $hobby = new HobbyModel;
          $hobby->member_id =  $id;
          $hobby->hobbies   =  $value;
          $hobby->save();
        }

        $data = MemberModel::with(['hobbies' => function ($query) {
         $query->select('member_id','hobbies');
         }])->where('id',$id)
         ->first();

        $token = JWTAuth::fromUser($member);

        $member = [
          "success"=>true,
          "message"=>"Data Berhasil di Buat.",
          "data"=>$data
        ];

        return response()->json(compact('member','token'),200);
  }

  public function get(Request $request){
       $token = $request->header('Authorization');
       $remove_bearer = substr($token, strpos($token, " ") + 1);

       //check token
       try{
         $work = JWTAuth::setToken($remove_bearer)->getPayload();
         $id = $work->get('sub');
       }catch (TokenExpiredException $e){
          return $this->sendError("Token Expired.", $token);
       }

       //if no data
       if(db::table('member')->where('id',$id)->doesntExist()){
            return $this->sendError("Data tidak Ditemukan.", $token);
       }

       // getdata
       $data = MemberModel::with(['hobbies' => function ($query) {
        $query->select('member_id','hobbies');
        }])->where('id',$id)
        ->get();


        $dataresource = MemberResource::collection($data);


       return $this->sendResponse($dataresource, "Data Berhasil di Ambil.");
  }

  public function update(Request $request){
       $token = $request->header('Authorization');
       $remove_bearer = substr($token, strpos($token, " ") + 1);

       //check token
       try{
         $work = JWTAuth::setToken($remove_bearer)->getPayload();
         $id = $work->get('sub');
       }catch (TokenExpiredException $e){
          return $this->sendError("Token Expired.", $token);
       }

       $validator = Validator::make($request->all(), [
         "nama"     => "required",
         "email"    => "required|email|unique:App\Models\MemberModel,email",
         "phone"    => "digits_between:11,13|required|numeric|regex:/(08)/",
         "hobby"    => "required|array",
         "hobby.*"  => "required|distinct",
       ]);

       //check if validation fails
       if ($validator->fails()) {
         return $this->sendError("Validation Error", $validator->errors());
       }

       //if no data
       if(db::table('member')->where('id',$id)->doesntExist()){
            return $this->sendError("Data tidak Ditemukan.", $token);
       }

       // update data
       $update = MemberModel::find($id);
       $update->nama = $request->nama;
       $update->email = $request->email;
       $update->phone = $request->phone;
       $update->save();

       $hobbies = $request->hobby;

       $deletehobbies = HobbyModel::where('member_id',$id)->delete();

       foreach ($hobbies as $value) {
         $hobby = new HobbyModel;
         $hobby->member_id =  $id;
         $hobby->hobbies   =  $value;
         $hobby->save();
       }

       // getdata
       $data = MemberModel::with(['hobbies' => function ($query) {
        $query->select('member_id','hobbies');
        }])->where('id',$id)
        ->get();


       $dataresource = MemberResource::collection($data);

       return $this->sendResponse($dataresource, "Data Berhasil di Update.");
  }

}
