<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\Producto;
use Illuminate\Support\Facades\Auth;

class GreaterThanCurrentQuantity implements ValidationRule
{
  protected $producto;
  protected $cantidadAnterior;

  public function __construct(Producto $producto, $cantidadAnterior)
  {
    $this->producto = $producto;
    $this->cantidadAnterior = $cantidadAnterior;
  }

  /**
   * Run the validation rule.
   *
   * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
   */
  public function validate(string $attribute, mixed $value, Closure $fail): void
  {
    // dd(floatval($value), floatval($this->cantidadAnterior));
    //
    // si es Administrador < puede aumentar la cantidad
    // si es Almacenista > disminuir la cantidad
    // if (floatval($value) < floatval($this->cantidadAnterior)) {
    //   $fail('La cantidad no puede ser menor a la actual.');
    // }
    if (Auth::user()->hasRole('Administrador') && floatval($value) < floatval($this->cantidadAnterior)) {
      $fail('La cantidad no puede ser menor a la actual.');
    }
    if (Auth::user()->hasRole('Almacenista') && floatval($value) > floatval($this->cantidadAnterior)) {
      $fail('La cantidad no puede ser mayor a la actual.');
    }
  }
}
