<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TipoMovimiento;

class TipoMovimientoSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    TipoMovimiento::create(['nombre' => 'Entrada']);
    TipoMovimiento::create(['nombre' => 'Salida']);
  }
}
