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
    'tipo_movimiento_id',
    'user_id',
    'cantidad',
    'producto_id',
  ];
  // created_at updated_at




  public function tipoMovimiento()
  {
    return $this->belongsTo(TipoMovimiento::class);
  }

  public function inventario()
  {
    return $this->belongsTo(Inventario::class);
  }
  // user_id
  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
