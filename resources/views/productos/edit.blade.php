@extends('layouts/layoutMaster')

@section('vendor-style')
    @vite(['resources/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.scss', 'resources/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.scss', 'resources/assets/vendor/libs/select2/select2.scss', 'resources/assets/vendor/libs/@form-validation/form-validation.scss', 'resources/assets/vendor/libs/animate-css/animate.scss', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss', 'resources/assets/vendor/libs/dropzone/dropzone.scss'])
@endsection

@section('vendor-script')
    @vite(['resources/assets/vendor/libs/moment/moment.js', 'resources/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js', 'resources/assets/vendor/libs/select2/select2.js', 'resources/assets/vendor/libs/cleavejs/cleave.js', 'resources/assets/vendor/libs/cleavejs/cleave-phone.js', 'resources/assets/vendor/libs/sweetalert2/sweetalert2.js', 'resources/assets/vendor/libs/jquery-repeater/jquery-repeater.js', 'resources/assets/vendor/libs/dropzone/dropzone.js', 'resources/assets/vendor/libs/@form-validation/popular.js', 'resources/assets/vendor/libs/@form-validation/bootstrap5.js', 'resources/assets/vendor/libs/@form-validation/auto-focus.js'])
@endsection
@section('page-script')
    <script>
        var ruta = "{{ $ruta }}";
        document.documentElement.setAttribute('ruta', ruta);
        //
        var id_tabla = "{{ $id_tabla }}";
        //   csrf_token()
        var csrf_token = "{{ csrf_token() }}";
        //   ruta para editar
        var nombre_ruta = "{{ $ruta_update }}";
    </script>


    @vite(['resources/assets/js/funciones/' . $ruta . '/crud.js'])
@endsection

@section('title', 'Editar | ' . $nombre_crud)
@section('content')
    <div class="card ">
        <div class="card-header">
            Crear{{ $nombre_crud }}

        </div>
        <div class="card-body demo-vertical-spacing demo-only-element">
            {{-- formulario $ruta --}}
            <form class="needs-validation" id="formulario_crud" method="POST" action="{{ $ruta_update }}">
                @csrf
                @method('PATCH')
                @foreach ($encabezados as $key => $encabezado)
                    @if ($encabezado == 'sinonimos')
                        {{-- select --}}
                        <div class="mb-1 row">
                            <label class="form-label">{{ ucfirst($encabezado) }}</label>
                            <div class="col-sm-12">
                                <select class="mySelect_sinonimos" name="{{ $nombre_tabla }}[{{ $encabezado }}][]"
                                    {{ $tipoDato[$encabezado]['nullable'] ? '' : 'required' }}
                                    {{ $tipoDato[$encabezado]['longitud'] ? 'maxlength=' . $tipoDato[$encabezado]['longitud'] : '' }}
                                    {{ $tipoDato[$encabezado]['regex'] ? 'pattern=' . $tipoDato[$encabezado]['regex'] : '' }}
                                    {{ $tipoDato[$encabezado]['atributos'] ? $tipoDato[$encabezado]['atributos'] : '' }}>
                                    @foreach ($data[$encabezado] as $sinonimo)
                                        <option selected value="{{ $sinonimo }}">{{ $sinonimo }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        {{-- boton para crear otro elemento --}}
                    @else
                        <div class="mb-1 row">
                            <label class="form-label">{{ ucfirst($encabezado) }}</label>
                            <div class="col-sm-12">
                                <input type="{{ $tipoDato[$encabezado]['tipo_input'] }}" class="form-control"
                                    placeholder="{{ $tipoDato[$encabezado]['placeholder'] }}"
                                    value="{{ $data[$encabezado] }}"
                                    {{ $tipoDato[$encabezado]['nullable'] ? '' : 'required' }}
                                    {{ $tipoDato[$encabezado]['longitud'] ? 'maxlength=' . $tipoDato[$encabezado]['longitud'] : '' }}
                                    {{ $tipoDato[$encabezado]['regex'] ? 'pattern=' . $tipoDato[$encabezado]['regex'] : '' }}
                                    {{ isset($tipoDato[$encabezado]['atributos']) ? $tipoDato[$encabezado]['atributos'] : '' }}
                                    {{-- step, min, max --}}
                                    {{ isset($tipoDato[$encabezado]['step']) ? 'step=' . $tipoDato[$encabezado]['step'] : '' }}
                                    {{ isset($tipoDato[$encabezado]['min']) ? 'min=' . $tipoDato[$encabezado]['min'] : '' }}
                                    {{ isset($tipoDato[$encabezado]['max']) ? 'max=' . $tipoDato[$encabezado]['max'] : '' }}
                                    name="{{ $nombre_tabla }}[{{ $encabezado }}]">
                            </div>
                        </div>
                    @endif
                @endforeach
                <div class="mb-1 row">
                    <label class="col-form-label col-sm-3 text-sm-end pt-sm-0"></label>
                    <div class="col-sm-12 text-end">
                        <button type="submit" class="btn btn-primary" id="agregar">Editar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
