<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    protected $table='item';
    protected $fillable = ['nama'];
    public $timestamps = false;

    public function pajaks()
     {
     	return $this->belongsToMany(Pajak::class);
     }

}
