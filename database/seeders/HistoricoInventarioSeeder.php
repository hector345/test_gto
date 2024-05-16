<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\HistoricoInventario;
use App\Models\TipoMovimiento;

class HistoricoInventarioSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $tipoMovimiento = TipoMovimiento::first(); // Obtener el primer tipo de movimiento como ejemplo
    HistoricoInventario::create(['tipo_movimiento_id' => $tipoMovimiento->id, 'user_id' => 1, 'cantidad' => 50]);
  }
}
