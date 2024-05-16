<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Producto;
use App\Models\Venta;

class VentaSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $producto = Producto::first(); // Obtener el primer producto como ejemplo
    Venta::create(['producto_id' => $producto->id, 'cantidad' => 5]);
  }
}
