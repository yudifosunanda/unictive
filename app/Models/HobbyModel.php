<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HobbyModel extends Model
{
  use HasFactory;
  protected $table='hobbies';
  protected $fillable = ['member_id','hobbies'];
  public $timestamps = false;

  public function member()
   {
    return $this->belongsTo(MemberModel::class,'member_id','id');
   }
}
