<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HistoricoInventario extends Model
{
  use HasFactory;
  use SoftDeletes;
  protected $dates = ['deleted_at'];
  protected $fillable = [
    'cantidad',
    'tipo_movimiento_id',
    'inventario_id',
  ];


  public function tipoMovimiento()
  {
    return $this->belongsTo(TipoMovimiento::class);
  }

  public function inventario()
  {
    return $this->belongsTo(Inventario::class);
  }
}
