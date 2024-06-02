<?php
error_reporting(0); // Desactivar toda notificación de error
date_default_timezone_set('America/Caracas'); // Zona horaria
@header("X-FRAME-OPTIONS: SAMEORIGIN"); // Solo podrá ser mostrada en un frame/iframe desde tu propio dominio.
header('Content-type: text/html; charset=utf-8');
if (!version_compare(PHP_VERSION, '5.5.0', '>=')){
    echo "Se Requiere una version PHP mayor o igual a: 5.5.0 <br> Su version PHP actual es: ".PHP_VERSION." Por favor actualice su version de PHP para continuar 😉";
    exit();
}

session_start();
$lfmc = array();
$lfmc['carpeta']   = 'corpoelec';
$lfmc['sitio_url'] = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].((!empty($lfmc['carpeta']))?"/{$lfmc['carpeta']}":'');

require_once('incluir/base_de_datos.php');
require_once('incluir/funciones_generales.php');
?>