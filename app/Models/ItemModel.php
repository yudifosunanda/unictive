<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemModel extends Model
{
    use HasFactory;
    protected $table='item';
    protected $fillable = ['nama'];
    public $timestamps = false;

    public function itempajak()
     {
     	return $this->hasMany(ItemPajakModel::class,'id_item');
     }

}
