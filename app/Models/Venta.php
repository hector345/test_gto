<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Venta extends Model
{
  use HasFactory;
  use SoftDeletes;
  protected $dates = ['deleted_at'];
  protected $fillable = [
    'cantidad',
    'producto_id',
  ];

  public function producto()
  {
    return $this->belongsTo(Producto::class);
  }
}
