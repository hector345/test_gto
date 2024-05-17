<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\HistoricoInventario;
use App\Helpers\Helpers;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Rules\UniqueInTableAndSynonyms;

class HistoricoInventarioController extends Controller
{
  public $nombre_crud = " Historico de Inventario";

  // array de nombre de columnas de la tabla del crud que seran visibles en la vista
  // tipo_movimiento_id
  // user_id
  // cantidad
  public $columnas_crud = [
    // tipo_movimiento_id
    'tipoMovimiento',
    'usuario',
    'cantidad',
    'tiempo'
  ];

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request  $request)
  {
    // path request
    $ruta_web = $request->path();
    // si tiene ?page=1 o cualquier numero, entonces se usa el numero de pagina que viene en la url
    if ($request->has('page')) {
      // no traer team
      $registros = HistoricoInventario::latest()->paginate(10)->withQueryString();
    } else {
      // si no tiene ?page=1 o cualquier numero, entonces se usa la pagina 1
      $registros = HistoricoInventario::latest()->paginate(10);
    }
    \Carbon\Carbon::setLocale('es');
    // map para obtener datos de la relacion
    $registros->map(function ($registro) {
      // tipoMovimiento, inventario, user
      $registro->tipoMovimiento = $registro->tipoMovimiento->nombre;
      $registro->usuario = $registro->user->name;
      // getCreatedAtAttribute
      $registro->tiempo = \Carbon\Carbon::parse($registro->created_at)->diffForHumans();
      // getUpdatedAtAttribute
      return $registro;
    });

    $nombre_ruta = Route::currentRouteName();

    return view($nombre_ruta, [
      'listado' => $registros,
      'tipoDato' => Helpers::obtenerTipoDatoCRUD(),
      'encabezados' => $this->columnas_crud,
      'ruta_web' => $ruta_web,
      // nombre_crud
      'nombre_crud' => $this->nombre_crud,
      'error' => false,
    ]);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    // consultar los campos y tipos de datos de la tabla
    $registro = HistoricoInventario::class;
    $tableColumns = \Schema::getColumnListing((new $registro)->getTable());
    $nombre_ruta = Route::currentRouteName();
    $nombre_ruta_sin_punto = explode('.', $nombre_ruta)[0];
    $data = new HistoricoInventario;
    return view($nombre_ruta_sin_punto . '.create', [
      'data' => $data,
      'ruta_para_create' => route($nombre_ruta_sin_punto . '.store'),
      'encabezados' => $this->columnas_crud,
      'tipoDato' => Helpers::obtenerTipoDatoCRUD(),
      'nombre_crud' => $this->nombre_crud,
      'nombre_ruta' => route($nombre_ruta_sin_punto . '.store'),
      'nombre_tabla' => $data->getTable(), // Obtener el nombre de la tabla de la nueva instancia
      'ruta' => $nombre_ruta_sin_punto,
      // ruta store
      'ruta_store' => route($nombre_ruta_sin_punto . '.store'),
    ]);
  }

  /**
   * Almacene un recurso recién creado en el almacenamiento.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $nombre_tabla = (new HistoricoInventario)->getTable();
    $validator = Validator::make($request->all()[$nombre_tabla], [
      'nombre' => [
        'required',
        new UniqueInTableAndSynonyms($nombre_tabla),
      ],
    ], [
      'nombre.unique' => 'El nombre ya existe',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'message' => 'Error al crear ' . $this->nombre_crud,
        'error' => true,
        'errors' => $validator->errors(),
      ]);
    }
    try {
      DB::beginTransaction();
      $datos = $request->all();
      $registro = HistoricoInventario::create($datos[$nombre_tabla]);
      $nombre_ruta = Route::currentRouteName();
      $nombre_ruta_sin_punto = explode('.', $nombre_ruta)[0];
      DB::commit();
      return response()->json([
        'message' => 'Se creó ' . $this->nombre_crud . ' con éxito',
        'data' => $registro,
        'url_redirect' => route($nombre_ruta_sin_punto . '.index'),
        'error' => false,
      ]);
    } catch (\Exception $e) {
      DB::rollback();
      // Aquí puedes manejar el error como prefieras, por ejemplo, devolver una respuesta JSON con un mensaje de error.
      return response()->json(['error' => $e->getMessage()], 500);
    }
  }

  /**
   * Display the specified resource.
   * Mostrar el recurso especificado.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $data = HistoricoInventario::where('id', $id)->firstOrFail();
    $nombre_ruta = Route::currentRouteName();
    // primer elemento
    $nombre_ruta_sin_punto = explode('.', $nombre_ruta)[0];
    return view($nombre_ruta_sin_punto . '.show', [
      'data' => $data
    ]);
  }

  /**
   * Show the form for editing the specified resource.
   * Mostrar el formulario para editar el recurso especificado.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    $data = HistoricoInventario::where('id', $id)->firstOrFail();
    $nombre_ruta = Route::currentRouteName();
    // primer elemento
    $nombre_ruta_sin_punto = explode('.', $nombre_ruta)[0];
    $nombre_tabla = (new HistoricoInventario)->getTable();
    // dd($nombre_tabla);
    // return view('HistoricoInventarios.create', [
    return view($nombre_ruta_sin_punto . '.edit', [
      'data' => $data,
      'encabezados' => $this->columnas_crud,
      'tipoDato' => Helpers::obtenerTipoDatoCRUD(),
      'nombre_crud' => $this->nombre_crud,
      'nombre_tabla' => $data->getTable(),
      'ruta' => $nombre_ruta_sin_punto,
      'id_tabla' => $id,
      'error' => false,
      'ruta_update' => route($nombre_ruta_sin_punto . '.update', $id),
    ]);
  }

  /**
   * Update the specified resource in storage.
   * Actualizar el recurso especificado en el almacenamiento.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id_tabla)
  {
    $nombre_tabla = (new HistoricoInventario)->getTable();
    $validator = Validator::make($request->all()[$nombre_tabla], [
      'nombre' => [
        'required',
        new UniqueInTableAndSynonyms($nombre_tabla, $id_tabla),
      ]
    ], [
      'nombre.required' => 'El nombre es requerido',
    ]);

    if ($validator->fails()) {
      return response()->json([
        'message' => 'Error al editar ' . $this->nombre_crud,
        'error' => true,
        'errors' => $validator->errors(),
      ]);
    }
    try {
      DB::beginTransaction();
      $registro = HistoricoInventario::where('id', $id_tabla)->firstOrFail();
      $nombre_tabla = $registro->getTable();
      $data = $request->all(); // Get all form data
      // dd($data);
      $registro->update($data[$nombre_tabla]);
      $nombre_ruta = Route::currentRouteName();
      $nombre_ruta_sin_punto = explode('.', $nombre_ruta)[0];
      DB::commit();
      return response()->json([
        'message' => 'Se actualizó ' . $this->nombre_crud . ' con éxito',
        'data' => $registro,
        'url_redirect' => route($nombre_ruta_sin_punto . '.index'),
        'error' => false,
      ]);
    } catch (\Exception $e) {
      DB::rollback();
      // Aquí puedes manejar el error como prefieras, por ejemplo, devolver una respuesta JSON con un mensaje de error.
      return response()->json(['error' => $e->getMessage()], 500);
    }
  }

  /**
   * Remove the specified resource from storage.
   * Eliminar el recurso especificado del almacenamiento.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    try {
      DB::beginTransaction();

      // buscar por id
      $data = HistoricoInventario::where('id', $id)->firstOrFail();
      // borrar con soft delete
      $nombre_ruta = Route::currentRouteName();
      $nombre_ruta_sin_punto = explode('.', $nombre_ruta)[0];
      $data->delete();
      DB::commit();

      return response()->json(['error' => false, 'mensaje' => 'Se eliminó ' . $this->nombre_crud . ' con éxito', 'url_redirect' => route($nombre_ruta_sin_punto . '.index')]);
    } catch (\Exception $e) {
      DB::rollback();
      // Aquí puedes manejar el error como prefieras, por ejemplo, devolver una respuesta JSON con un mensaje de error.
      return response()->json(['error' => true, 'mensaje' => $e->getMessage()], 500);
    }
  }
  //  search nombre en caso de existir la columna descripcion tambien
  public function buscar(Request $request)
  {
    try {
      $ruta_web = $request->path();
      $gfdg = explode('/', $ruta_web);
      $elemento_ruta = end($gfdg);
      $nueva_ruta = str_replace('/' . $elemento_ruta, '', $ruta_web);

      if ($request->has('condicion')) {
        $registro = new HistoricoInventario();
        $fillableColumns = $registro->getFillable();

        $registros = HistoricoInventario::latest()
          ->where(function ($query) use ($request, $fillableColumns) {
            foreach ($fillableColumns as $column) {
              $query->orWhere($column, 'like', '%' . $request->condicion . '%');
            }
          })
          ->orWhereHas('tipoMovimiento', function ($query) use ($request) {
            $query->where('nombre', 'like', '%' . $request->condicion . '%');
          })
          ->orWhereHas('user', function ($query) use ($request) {
            $query->where('name', 'like', '%' . $request->condicion . '%');
          })
          ->paginate(10)
          ->withQueryString();
      } else {
        $registros = HistoricoInventario::latest()->paginate(10);
      }
      \Carbon\Carbon::setLocale('es');
      // map para obtener datos de la relacion
      $registros->map(function ($registro) {
        // tipoMovimiento, inventario, user
        $registro->tipoMovimiento = $registro->tipoMovimiento->nombre;
        $registro->usuario = $registro->user->name;
        // getCreatedAtAttribute
        $registro->tiempo = \Carbon\Carbon::parse($registro->created_at)->diffForHumans();
        // getUpdatedAtAttribute
        return $registro;
      });

      if ($registros->isEmpty()) {
        throw new \Exception('No se encontraron resultados');
      }

      // $encabezados = $registros->items()[0]->getFillable();
      $paginationHtml = $registros->links('vendor.pagination.bootstrap-5')->toHtml();
      $paginationHtml = str_replace($ruta_web, $nueva_ruta, $paginationHtml);

      return response()->json([
        'listado' => $registros,
        'encabezados' => $this->columnas_crud,
        'paginationHtml' => $paginationHtml,
      ]);
    } catch (\Exception $e) {
      return response()->json(['error' => true, 'mensaje' => $e->getMessage()], 500);
    }
  }
}
