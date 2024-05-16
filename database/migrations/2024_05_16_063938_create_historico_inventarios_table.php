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
    Schema::create('historico_inventarios', function (Blueprint $table) {
      $table->id();
      // tipo_movimiento_id
      $table->foreignId('tipo_movimiento_id')->constrained();
      // user_id
      $table->foreignId('user_id')->constrained();
      // cantidad comentario: cantidad de productos de ese movimiento
      $table->integer('cantidad')->default(0)->comment('cantidad de productos de ese movimiento');
      $table->timestamps();
      $table->softDeletes(); // Añadir borrado lógico
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('historico_inventarios');
  }
};
