<?php

namespace App\Helpers;

use Config;
use Illuminate\Support\Str;

class Helpers
{
  public static function appClasses()
  {

    $data = config('custom.custom');


    // default data array
    $DefaultData = [
      'myLayout' => 'vertical',
      'myTheme' => 'theme-default',
      'myStyle' => 'light',
      'myRTLSupport' => true,
      'myRTLMode' => true,
      'hasCustomizer' => true,
      'showDropdownOnHover' => true,
      'displayCustomizer' => true,
      'contentLayout' => 'compact',
      'headerType' => 'fixed',
      'navbarType' => 'fixed',
      'menuFixed' => true,
      'menuCollapsed' => false,
      'footerFixed' => false,
      'customizerControls' => [
        'rtl',
        'style',
        'headerType',
        'contentLayout',
        'layoutCollapsed',
        'showDropdownOnHover',
        'layoutNavbarOptions',
        'themes',
      ],
      //   'defaultLanguage'=>'en',
    ];

    // if any key missing of array from custom.php file it will be merge and set a default value from dataDefault array and store in data variable
    $data = array_merge($DefaultData, $data);

    // All options available in the template
    $allOptions = [
      'myLayout' => ['vertical', 'horizontal', 'blank', 'front'],
      'menuCollapsed' => [true, false],
      'hasCustomizer' => [true, false],
      'showDropdownOnHover' => [true, false],
      'displayCustomizer' => [true, false],
      'contentLayout' => ['compact', 'wide'],
      'headerType' => ['fixed', 'static'],
      'navbarType' => ['fixed', 'static', 'hidden'],
      'myStyle' => ['light', 'dark', 'system'],
      'myTheme' => ['theme-default', 'theme-bordered', 'theme-semi-dark'],
      'myRTLSupport' => [true, false],
      'myRTLMode' => [true, false],
      'menuFixed' => [true, false],
      'footerFixed' => [true, false],
      'customizerControls' => [],
      // 'defaultLanguage'=>array('en'=>'en','fr'=>'fr','de'=>'de','ar'=>'ar'),
    ];

    //if myLayout value empty or not match with default options in custom.php config file then set a default value
    foreach ($allOptions as $key => $value) {
      if (array_key_exists($key, $DefaultData)) {
        if (gettype($DefaultData[$key]) === gettype($data[$key])) {
          // data key should be string
          if (is_string($data[$key])) {
            // data key should not be empty
            if (isset($data[$key]) && $data[$key] !== null) {
              // data key should not be exist inside allOptions array's sub array
              if (!array_key_exists($data[$key], $value)) {
                // ensure that passed value should be match with any of allOptions array value
                $result = array_search($data[$key], $value, 'strict');
                if (empty($result) && $result !== 0) {
                  $data[$key] = $DefaultData[$key];
                }
              }
            } else {
              // if data key not set or
              $data[$key] = $DefaultData[$key];
            }
          }
        } else {
          $data[$key] = $DefaultData[$key];
        }
      }
    }
    $styleVal = $data['myStyle'] == "dark" ? "dark" : "light";
    if (isset($_COOKIE['mode'])) {
      if ($_COOKIE['mode'] === "system") {
        if (isset($_COOKIE['colorPref'])) {
          $styleVal = Str::lower($_COOKIE['colorPref']);
        }
      } else {
        $styleVal = $_COOKIE['mode'];
      }
    }
    isset($_COOKIE['theme']) ? $themeVal = $_COOKIE['theme'] : $themeVal = $data['myTheme'];
    //layout classes
    $layoutClasses = [
      'layout' => $data['myLayout'],
      'theme' => $themeVal,
      'themeOpt' => $data['myTheme'],
      'style' => $styleVal,
      'styleOpt' => $data['myStyle'],
      'rtlSupport' => $data['myRTLSupport'],
      'rtlMode' => $data['myRTLMode'],
      'textDirection' => $data['myRTLMode'],
      'menuCollapsed' => $data['menuCollapsed'],
      'hasCustomizer' => $data['hasCustomizer'],
      'showDropdownOnHover' => $data['showDropdownOnHover'],
      'displayCustomizer' => $data['displayCustomizer'],
      'contentLayout' => $data['contentLayout'],
      'headerType' => $data['headerType'],
      'navbarType' => $data['navbarType'],
      'menuFixed' => $data['menuFixed'],
      'footerFixed' => $data['footerFixed'],
      'customizerControls' => $data['customizerControls'],
    ];

    // sidebar Collapsed
    if ($layoutClasses['menuCollapsed'] == true) {
      $layoutClasses['menuCollapsed'] = 'layout-menu-collapsed';
    }

    // Header Type
    if ($layoutClasses['headerType'] == 'fixed') {
      $layoutClasses['headerType'] = 'layout-menu-fixed';
    }
    // Navbar Type
    if ($layoutClasses['navbarType'] == 'fixed') {
      $layoutClasses['navbarType'] = 'layout-navbar-fixed';
    } elseif ($layoutClasses['navbarType'] == 'static') {
      $layoutClasses['navbarType'] = '';
    } else {
      $layoutClasses['navbarType'] = 'layout-navbar-hidden';
    }

    // Menu Fixed
    if ($layoutClasses['menuFixed'] == true) {
      $layoutClasses['menuFixed'] = 'layout-menu-fixed';
    }


    // Footer Fixed
    if ($layoutClasses['footerFixed'] == true) {
      $layoutClasses['footerFixed'] = 'layout-footer-fixed';
    }

    // RTL Supported template
    if ($layoutClasses['rtlSupport'] == true) {
      $layoutClasses['rtlSupport'] = '/rtl';
    }

    // RTL Layout/Mode
    if ($layoutClasses['rtlMode'] == true) {
      $layoutClasses['rtlMode'] = 'rtl';
      $layoutClasses['textDirection'] = 'rtl';
    } else {
      $layoutClasses['rtlMode'] = 'ltr';
      $layoutClasses['textDirection'] = 'ltr';
    }

    // Show DropdownOnHover for Horizontal Menu
    if ($layoutClasses['showDropdownOnHover'] == true) {
      $layoutClasses['showDropdownOnHover'] = true;
    } else {
      $layoutClasses['showDropdownOnHover'] = false;
    }

    // To hide/show display customizer UI, not js
    if ($layoutClasses['displayCustomizer'] == true) {
      $layoutClasses['displayCustomizer'] = true;
    } else {
      $layoutClasses['displayCustomizer'] = false;
    }

    return $layoutClasses;
  }

  public static function updatePageConfig($pageConfigs)
  {
    $demo = 'custom';
    if (isset($pageConfigs)) {
      if (count($pageConfigs) > 0) {
        foreach ($pageConfigs as $config => $val) {
          Config::set('custom.' . $demo . '.' . $config, $val);
        }
      }
    }
  }
  public static function formatear_fecha($fecha)
  {
    $meses = array(
      'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio',
      'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'
    );
    $fecha_arr = explode('-', $fecha);
    $mes = $meses[(int)$fecha_arr[1] - 1];
    $dia = (int)$fecha_arr[2];
    $anio = (int)$fecha_arr[0];
    return $dia . ' de ' . $mes . ' de ' . $anio;
  }
  // funcion para validar si un correo es valido
  public static function validarEmail($correo)
  {
    if (filter_var($correo, FILTER_VALIDATE_EMAIL)) {
      return true;
    } else {
      return false;
    }
  }
  public static function obtenerColumnasIgnorarCRUD()
  {
    return ['id', 'created_at', 'updated_at', 'deleted_at', 'team_id',   'creado_por',   'actualizado_por',   'borrado_por'];
  }

  // tipo de datos por nombre
  public static function obtenerTipoDatoCRUD()
  {
    return [
      "nombre" => [
        "tipo" => "string",
        "tipo_input" => "text",
        "longitud" => 255,
        "nullable" => false,
        "default" => null,
        // tambien se pueden aceptar numeros
        "regex" => "/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s-0-9]+$/",
        "placeholder" => "ingrese el nombre"
      ],
      "descripcion" => [
        "tipo" => "string",
        "tipo_input" => "textarea",
        "longitud" => 255,
        "nullable" => false,
        "default" => null,
        // tambien se pueden aceptar numeros
        "regex" => "/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s-0-9]+$/",
        "placeholder" => "ingrese la descripcion"
      ],
      "telefono" => [
        "tipo" => "string",
        "tipo_input" => "text",
        "longitud" => 10,
        "nullable" => false,
        "default" => null,
        "regex" => "/^[0-9]+$/",
        "placeholder" => "ingrese el telefono"
      ],
      "sinonimos" => [
        "tipo" => "json",
        "tipo_input" => "select",
        "atributos" => "multiple='multiple'",
        "longitud" => null,
        "nullable" => true,
        "default" => null,
        "regex" => null,
        "placeholder" => "ingrese los sinonimos"
      ],
      "visible" => [
        "tipo" => "boolean",
        "tipo_input" => "checkbox",
        "longitud" => null,
        "nullable" => false,
        "default" => false,
        "regex" => null,
        "placeholder" => "ingrese si es visible",
        "class" => "form-check-input",
        "value" => "1"
      ],
      "productos_id" => [
        "tipo" => "select",
        "tipo_input" => "select",
        "longitud" => null,
        "nullable" => true,
        "default" => null,
        "regex" => null,
        "placeholder" => "ingrese el producto",
        "atributos" => ""
      ],
      'precio' => [
        "tipo" => "number",
        "tipo_input" => "number",
        "nullable" => false,
        "default" => 0,
        "longitud" => null,
        // 8 enteros y 2 decimales
        "regex" => null,

        // placeholder="1.0" step="0.01" min="0" max="10"
        "placeholder" => "0.00",
        "step" => "0.01",
        "min" => "0",
      ],
    ];
  }
}
