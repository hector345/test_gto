<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('inventario', function (Blueprint $table) {
      $table->id();
      // producto_id a la tabla producto
      $table->foreignId('producto_id')->constraint();
      // cantidad default 0
      $table->integer('cantidad')->default(0);
      $table->timestamps();
      $table->softDeletes(); // Añadir borrado lógico
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('inventario');
  }
};
