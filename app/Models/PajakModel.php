<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PajakModel extends Model
{
    use HasFactory;
    protected $table='pajak';
    protected $fillable = ['nama','rate'];
    public $timestamps = false;

    public function pajakitem()
     {
      return $this->hasMany(ItemPajakModel::class,'id_pajak');
     }
}
