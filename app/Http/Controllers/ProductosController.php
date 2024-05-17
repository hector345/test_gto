<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductoServicio;
use App\Helpers\Helpers;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Rules\UniqueInTableAndSynonyms;
use App\Models\Producto;
// Schema
use Illuminate\Support\Facades\Schema;
use App\Rules\GreaterThanCurrentQuantity;
use Illuminate\Support\Facades\Auth;


class ProductosController extends Controller
{
  public $nombre_crud = " Producto";

  // array de nombre de columnas de la tabla del crud que seran visibles en la vista
  public $columnas_crud = [
    'nombre',
    'precio',
    'cantidad',
    'visible',
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
      $registros = Producto::withTrashed()->latest()->paginate(10)->withQueryString();
    } else {
      // si no tiene ?page=1 o cualquier numero, entonces se usa la pagina 1
      $registros = Producto::withTrashed()->latest()->paginate(10);
    }


    // with inventario
    $registros->load('inventario');
    // poner al mismo nivel el campo "cantidad" de la relacion
    $registros->map(function ($registro) {
      if ($registro->inventario) {
        $registro->cantidad = $registro->inventario->cantidad;
      } else {
        $registro->cantidad = 0; // o cualquier valor predeterminado que desees
      }
      // Agregar la columna "visible"
      $registro->visible = $registro->deleted_at ? false : true;
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
    // si el usuario no tiene rol de administrador sacar de aqui
    $user = Auth::user();
    $roles_del_usuario = $user->getRoleNames(); // Devuelve una colección de los nombres de los roles
    $roles_permitidos = ['Administrador'];
    // regresar base_url
    if (!$user->hasAnyRole($roles_permitidos)) {
      abort(403, 'No tienes permiso para acceder a este módulo');
    }
    // consultar los campos y tipos de datos de la tabla
    $registro = Producto::class;
    $tableColumns = \Schema::getColumnListing((new $registro)->getTable());
    $nombre_ruta = Route::currentRouteName();
    $nombre_ruta_sin_punto = explode('.', $nombre_ruta)[0];
    $data = new Producto;
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
    $nombre_tabla = (new Producto)->getTable();
    $datos = $request->all();
    try {
      DB::beginTransaction();
      $registro = Producto::create($datos[$nombre_tabla]);
      // agregar cantidad a la relacion inventario
      $registro->inventario()->create($datos['productos']['inventario']);

      // Validar la cantidad después de crear el producto
      $validator = Validator::make($datos['productos']['inventario'], [
        'cantidad' => [
          'numeric',
          'min:0',
        ],
      ], [
        'cantidad.min' => 'La cantidad no puede ser menor a 0',
      ]);

      if ($validator->fails()) {
        DB::rollback();
        return response()->json([
          'message' => 'Error al crear ' . $this->nombre_crud,
          'error' => true,
          'errors' => $validator->errors(),
        ]);
      }

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
    $data = Producto::where('id', $id)->firstOrFail();
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
    $data = Producto::where('id', $id)->firstOrFail();
    $nombre_ruta = Route::currentRouteName();
    // primer elemento
    $nombre_ruta_sin_punto = explode('.', $nombre_ruta)[0];
    $nombre_tabla = (new Producto)->getTable();
    // dd($nombre_tabla);
    // return view('Productos.create', [
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
    $nombre_tabla = (new Producto)->getTable();
    try {
      DB::beginTransaction();
      $registro = Producto::where('id', $id_tabla)->with('inventario')->firstOrFail();
      $nombre_tabla = $registro->getTable();
      $cantidad_anterior = $registro->inventario->cantidad;
      $data = $request->all(); // Get all form data
      // agregar id a $data['productos'] usando el id de tabla
      $data['productos']['id'] = $id_tabla;

      // agregar producto_id a $data['productos']['inventario']
      $data['productos']['inventario']['producto_id'] = $registro->id;
      // dd($data);
      // Validar la cantidad después de encontrar el producto
      $validator = Validator::make($data[$nombre_tabla], [
        'nombre' => [
          'required',
          new UniqueInTableAndSynonyms($nombre_tabla, $id_tabla),
        ],
        'inventario.cantidad' => [
          'numeric',
          'min:0',
          new GreaterThanCurrentQuantity($registro, $cantidad_anterior),
        ],
      ], [
        'nombre.required' => 'El nombre es requerido',
        'nombre.UniqueInTableAndSynonyms' => 'El nombre ya existe',
        'inventario.cantidad.min' => 'La cantidad no puede ser menor a 0',
        'inventario.cantidad.GreaterThanCurrentQuantity' => 'La cantidad no puede ser menor a la actual',
      ]);

      if ($validator->fails()) {
        DB::rollback();
        return response()->json([
          'message' => 'Error al editar ' . $this->nombre_crud,
          'error' => true,
          'errors' => $validator->errors(),
        ]);
      } else {
        $registro->update($data['productos']);
        // agregar producto_id a $data['productos']['inventario']
        $data['productos']['inventario']['producto_id'] = $registro->id;

        $registro->inventario()->update($data['productos']['inventario']);
        $nombre_ruta = Route::currentRouteName();
        $nombre_ruta_sin_punto = explode('.', $nombre_ruta)[0];
        DB::commit();
        return response()->json([
          'message' => 'Se actualizó ' . $this->nombre_crud . ' con éxito',
          'data' => $registro,
          'url_redirect' => route($nombre_ruta_sin_punto . '.index'),
          'error' => false,
        ]);
      }
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
    // Auth::user()->hasRole('Administrador')
    if (Auth::user()->hasRole('Administrador')) {
      try {
        DB::beginTransaction();

        // buscar por id
        $data = Producto::where('id', $id)->firstOrFail();
        $data->delete();
        DB::commit();

        return response()->json(['error' => false, 'mensaje' => 'Se eliminó ' . $this->nombre_crud . ' con éxito']);
      } catch (\Exception $e) {
        DB::rollback();
        // Aquí puedes manejar el error como prefieras, por ejemplo, devolver una respuesta JSON con un mensaje de error.
        return response()->json(['error' => true, 'mensaje' => $e->getMessage()], 500);
      }
    } else {
      return response()->json(['error' => true, 'mensaje' => 'No tienes permiso para esta acción'], 403);
    }
  }
  // metodo para restaurar

  public function restore($id)
  {
    if (Auth::user()->hasRole('Administrador')) {

      try {
        DB::beginTransaction();
        // buscar por id
        $data = Producto::withTrashed()->where('id', $id)->firstOrFail();
        // restaurar
        $data->restore();
        DB::commit();
        return response()->json(['error' => false, 'mensaje' => 'Se restauró ' . $this->nombre_crud . ' con éxito']);
      } catch (\Exception $e) {
        DB::rollback();
        // Aquí puedes manejar el error como prefieras, por ejemplo, devolver una respuesta JSON con un mensaje de error.
        return response()->json(['error' => true, 'mensaje' => $e->getMessage()], 500);
      }
    } else {
      return response()->json(['error' => true, 'mensaje' => 'No tienes permiso para esta acción'], 403);
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
        $registro = new Producto();
        $fillableColumns = $registro->getFillable();

        $registros = Producto::withTrashed()->latest()
          ->where(function ($query) use ($request, $fillableColumns) {
            foreach ($fillableColumns as $column) {
              $query->orWhere($column, 'like', '%' . $request->condicion . '%');
            }
          })
          ->paginate(10)
          ->withQueryString();
      } else {
        $registros = Producto::withTrashed()->latest()->paginate(10);
      }
      $registros->load('inventario');
      // poner al mismo nivel el campo "cantidad" de la relacion
      $registros->map(function ($registro) {
        if ($registro->inventario) {
          $registro->cantidad = $registro->inventario->cantidad;
        } else {
          $registro->cantidad = 0; // o cualquier valor predeterminado que desees
        }
        $registro->visible = $registro->deleted_at ? false : true;
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
