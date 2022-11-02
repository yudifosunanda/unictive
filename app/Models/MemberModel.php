<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Tymon\JWTAuth\Contracts\JWTSubject;

class MemberModel extends Model implements JWTSubject
{
  use HasFactory;
  protected $table='member';
  protected $fillable = ['nama','email','phone'];
  public $timestamps = false;

  public function hobbies()
   {
    return $this->hasMany(HobbyModel::class,'member_id');
   }

   public function getJWTIdentifier()
 {
     return $this->getKey();
 }

 /**
  * Return a key value array, containing any custom claims to be added to the JWT.
  *
  * @return array
  */
 public function getJWTCustomClaims()
 {
     return [];
 }
}
