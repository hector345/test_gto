<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Inventario;
use App\Models\Producto;

class InventarioSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $producto = Producto::first(); // Obtener el primer producto como ejemplo
    Inventario::create(['producto_id' => $producto->id, 'cantidad' => 100]);
  }
}
