<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Crear roles
    $adminRole = Role::create(['name' => 'Administrador']);
    $almacenistaRole = Role::create(['name' => 'Almacenista']);

    // Crear permisos
    $permissions = [
      "Ver módulo inventario",
      "Agregar nuevos productos",
      "Aumentar inventario",
      "Dar de baja/reactivar un producto",
      "Ver módulo del histórico",
      "Ver módulo para Salida de productos",
      "Sacar inventario del almacén"
    ];

    foreach ($permissions as $permission) {
      Permission::create(['name' => $permission]);
    }

    // Asignar permisos a roles
    $adminRole->givePermissionTo([
      "Ver módulo inventario",
      "Agregar nuevos productos",
      "Aumentar inventario",
      "Dar de baja/reactivar un producto",
      "Ver módulo del histórico"
    ]);

    $almacenistaRole->givePermissionTo([
      "Ver módulo inventario",
      "Ver módulo para Salida de productos",
      "Sacar inventario del almacén"
    ]);
    // aisgnar roles
    //crear usuario Administrador 'juanp@' . env('APP_DOMAIN'),
    User::factory()->create([
      'name' => 'Juan Pérez',
      'email' => 'juanp@' . env('APP_DOMAIN'),
    ])->assignRole($adminRole);

    User::factory()->create([
      'name' => 'Mario Martínez',
      'email' => 'mariam@' . env('APP_DOMAIN'),
    ])->assignRole($almacenistaRole);
  }
}
