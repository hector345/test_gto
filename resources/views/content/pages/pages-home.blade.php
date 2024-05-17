@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Home')

@section('content')
    <h4>Funcionalidad del Sistema de Inventarios</h4>
    <p>El sistema de inventarios de la empresa debe contar con las siguientes características:</p>

    <h5>Inicio de Sesión</h5>
    <p>El sistema debe permitir el inicio de sesión de los usuarios.</p>

    <h5>Módulo de Inventario de productos</h5>
    <ul>
        <li>Permite ver el inventario de la empresa.</li>
        <li>Agregar nuevos productos al inventario con cantidad inicial de 0.</li>
        <li>Aumentar el inventario de los productos (Entrada de productos).</li>
        <li>Mostrar un mensaje de error si se intenta disminuir la cantidad de inventario actual.</li>
        <li>Dar de baja un producto sin eliminar el registro, solo actualizando el estatus.</li>
        <li>Reactivar productos dados de baja.</li>
        <li>Ver productos activos e inactivos.</li>
    </ul>

    <h5>Módulo de Salida de Productos</h5>
    <ul>
        <li>Permite restar inventario del almacén.</li>
        <li>Solo muestra los productos activos.</li>
        <li>No permite sacar una cantidad mayor de un producto de la que está en inventario, mostrando un mensaje de error
            si se intenta hacerlo.</li>
    </ul>

    <h5>Historial de Movimientos</h5>
    <ul>
        <li>Listado de movimientos de “Entrada” y “Salida” de productos.</li>
        <li>Posibilidad de filtrar el listado por tipo de movimiento (entrada o salida).</li>
        <li>Registro de quién realizó cada movimiento.</li>
        <li>Registro de la fecha y hora de cada movimiento.</li>
    </ul>

    <h5>Roles y Permisos</h5>
    <table>
        <thead>
            <tr>
                <th>Permiso</th>
                <th>Administrador</th>
                <th>Almacenista</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Ver módulo inventario</td>
                <td>✓</td>
                <td>✓</td>
            </tr>
            <tr>
                <td>Agregar nuevos productos</td>
                <td>✓</td>
                <td>✕</td>
            </tr>
            <tr>
                <td>Aumentar inventario</td>
                <td>✓</td>
                <td>✕</td>
            </tr>
            <tr>
                <td>Dar de baja/reactivar un producto</td>
                <td>✓</td>
                <td>✕</td>
            </tr>
            <tr>
                <td>Ver módulo para Salida de productos</td>
                <td>✕</td>
                <td>✓</td>
            </tr>
            <tr>
                <td>Sacar inventario del almacén</td>
                <td>✕</td>
                <td>✓</td>
            </tr>
            <tr>
                <td>Ver módulo del histórico</td>
                <td>✓</td>
                <td>✕</td>
            </tr>
        </tbody>
    </table>
@endsection
