<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemPajakModel extends Model
{
    use HasFactory;
    protected $table='item_pajak';
    protected $fillable = ['id_item','id_pajak'];
    public $timestamps = false;


    public function item()
     {
     	return $this->belongsTo(ItemModel::class);
     }

    public function pajak()
     {
     	return $this->belongsTo(PajakModel::class);
     }
}
