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
    Schema::create('ventas', function (Blueprint $table) {
      $table->id();
      // producto_id
      $table->unsignedBigInteger('producto_id');
      // cantidad default 0
      $table->integer('cantidad')->default(0);
      $table->timestamps();
      $table->softDeletes(); // Añadir borrado lógico

      $table->foreign('producto_id')->references('id')->on('productos');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('ventas');
  }
};
