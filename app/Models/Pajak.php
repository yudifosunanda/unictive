<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pajak extends Model
{
    use HasFactory;
    protected $table='pajak';
    protected $fillable = ['nama','rate'];
    public $timestamps = false;

    public function items()
     {
      return $this->belongsToMany(Item::class);
     }
}
