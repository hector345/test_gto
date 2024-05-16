<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Producto extends Model
{
  use HasFactory;
  use SoftDeletes;
  protected $fillable = [
    'nombre',
    'precio',
  ];
  protected $dates = ['deleted_at'];

  public function ventas()
  {
    return $this->hasMany(Venta::class);
  }

  public function inventario()
  {
    return $this->hasOne(Inventario::class);
  }
}
