<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Producto;

class ProductoSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    Producto::create(['nombre' => 'Producto 1', 'precio' => 10.50]);
    Producto::create(['nombre' => 'Producto 2', 'precio' => 20.75]);
  }
}
