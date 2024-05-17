@extends('layouts/layoutMaster')

@php
    $nombre = $nombre_crud;
    // obtener el nombre de la ruta en web.php y dividirlo en un array separado por puntos
    $nombreMod = explode('.', Route::currentRouteName())[0];
@endphp

@section('title', $nombre)

@section('vendor-style')
    @vite(['resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss', 'resources/assets/vendor/libs/select2/select2.scss', 'resources/assets/vendor/libs/@form-validation/form-validation.scss', 'resources/assets/vendor/libs/animate-css/animate.scss', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss', 'resources/assets/vendor/libs/dropzone/dropzone.scss'])
    {{-- assets/vendor/libs/tagify/tagify.css --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/tagify/tagify.css') }}" />
@endsection

@section('vendor-script')
    @vite(['resources/assets/vendor/libs/moment/moment.js', 'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js', 'resources/assets/vendor/libs/select2/select2.js', 'resources/assets/vendor/libs/cleavejs/cleave.js', 'resources/assets/vendor/libs/cleavejs/cleave-phone.js', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.js', 'resources/assets/vendor/libs/jquery-repeater/jquery-repeater.js', 'resources/assets/vendor/libs/dropzone/dropzone.js', 'resources/assets/vendor/libs/@form-validation/popular.js', 'resources/assets/vendor/libs/@form-validation/bootstrap5.js', 'resources/assets/vendor/libs/@form-validation/auto-focus.js', 'resources/assets/vendor/libs/tagify/tagify.js'])
@endsection

@section('page-script')
    <script>
        var ruta = "{{ $nombreMod }}";
        window.route = ruta;
    </script>
    @vite(['resources/assets/js/funciones/' . $nombreMod . '/crud.js'])
@endsection



@section('content')
    <div class="card ">
        <div class="card-header">
            {{ $nombre }}
        </div>
        <div class="card-body">
            {{-- buscador --}}
            <div class="row align-items-center mb-1">
                <div class="col-10 col-sm-10 col-md-10 col-lg-10 col-xl-10 col-xxl-10">
                    <div class="input-group input-group-merge">
                        <span class="input-group-text"><i class="ti ti-search"></i></span>
                        <input type="text" id="buscar" class="form-control" placeholder="Buscar..."
                            aria-label="Buscar..." aria-describedby="buscar">
                    </div>
                </div>
            </div>

            <div id="tabla-{{ $nombreMod }}">
                <table class="table table-hover table-striped table-sm">
                    <thead class="text-center">
                        <tr>
                            @foreach ($encabezados as $encabezado)
                                {{-- si es $encabezados esta en el array $encabezados->columnas_crud --}}
                                {{-- si es el primero --}}
                                @if ($loop->first)
                                    <th scope="col" class="d-table-cell">
                                        {{ $encabezado }}
                                    </th>
                                @else
                                    <th scope="col" class="d-none d-lg-table-cell">
                                        {{ $encabezado }}
                                    </th>
                                @endif
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        {{-- obtener los datos de la tabla --}}
                        @foreach ($listado->items() as $item)
                            <tr id="tabla-registro-{{ $item->id }}">
                                @foreach ($encabezados as $encabezado)
                                    {{-- si es el primero --}}
                                    @if ($loop->first)
                                        <td class="d-table-cell">
                                            {{ $item->$encabezado }}
                                        </td>
                                    @else
                                        {{-- si tipoDato[nombre_campo][tipo] es un json genera <span
                                  class="badge rounded-pill bg-primary">Primary</span> --}}
                                        @if (isset($tipoDato[$encabezado]) && $tipoDato[$encabezado]['tipo'] == 'json' && isset($item->$encabezado))
                                            <td class="d-none d-lg-table-cell">
                                                @foreach ($item->$encabezado as $itemJson)
                                                    <span
                                                        class="badge my-1 me-1 rounded-pill bg-primary">{{ $itemJson }}</span>
                                                @endforeach
                                            </td>
                                            {{-- y si es "visible" se muestra icono de ojo y si no icono de ojo tachado --}}
                                        @elseif ($encabezado == 'visible')
                                            <td class="d-none d-lg-table-cell">
                                                @if ($item->$encabezado)
                                                    <i class="ti ti-eye"></i>
                                                @else
                                                    <i class="ti ti-eye-off"></i>
                                                @endif
                                            </td>
                                        @else
                                            <td class="d-none d-lg-table-cell">
                                                {{ $item->$encabezado }}
                                            </td>
                                        @endif
                                    @endif
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer" id="paginado-{{ $nombreMod }}">
            {{ $listado->links('vendor.pagination.bootstrap-5') }}
        </div>
    </div>
@endsection
