<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;
use App\Helpers\Helpers;

class UniqueInTableAndSynonyms implements ValidationRule
{
  private $table;
  // id del registro que se esta editando
  private $id;


  public function __construct($table, $id = null)
  {
    $this->table = $table;
    $this->id = $id;
  }
  /**
   * Run the validation rule.
   *
   * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
   */
  public function validate(string $attribute, mixed $value, Closure $fail): void

  {
    $nombre = $this->table;
    // quitar guiones bajos y medios y poner espacios
    $nombre = str_replace('_', ' ', $nombre);
    $nombre = str_replace('-', ' ', $nombre);
    $nombre = trim($nombre);
    // si value es un array
    if (is_array($value)) {
      // se recorre el array
      $mensaje = [];
      foreach ($value as $key => $value) {
        // si el id es null, entonces se esta creando un registro
        if ($this->id === null) {
          $existsInName = DB::table($this->table)->where('nombre', $value)->whereNull('deleted_at')->exists();
        } elseif ($this->id !== null) {
          // si el id no es null, entonces se esta editando un registro
          $existsInName = DB::table($this->table)->where('nombre', $value)->where('id', '!=', $this->id)->whereNull('deleted_at')->exists();
        }
        if ($existsInName) {
          $mensaje[] = "$value ya existe en 'nombre' $nombre";
        }
      }
      if (count($mensaje) > 0) {
        $fail(implode(', ', $mensaje));
      }
    } else {
      if ($this->id === null) {
        $existsInName = DB::table($this->table)->where('nombre', $value)->whereNull('deleted_at')->exists();
      } elseif ($this->id !== null) {
        // si el id no es null, entonces se esta editando un registro
        $existsInName = DB::table($this->table)->where('nombre', $value)->where('id', '!=', $this->id)->whereNull('deleted_at')->exists();
      }
      if ($existsInName) {
        $fail("$value ya existe en 'nombre' $nombre");
      }
    }
  }
}
