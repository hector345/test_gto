<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventario extends Model
{
  use HasFactory;
  use SoftDeletes;
  protected $dates = ['deleted_at'];

  public function producto()
  {
    return $this->belongsTo(Producto::class);
  }

  public function historicoInventarios()
  {
    return $this->hasMany(HistoricoInventario::class);
  }
}
