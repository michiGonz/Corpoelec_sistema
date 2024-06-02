<?php
ini_set('max_execution_time', '60'); // tiempo de ejecución de un script
// Conectar al servidor SQL
$host = "localhost";
$usuario = "root";
$clave = "";
$nombre = "corpoelec";
$puerto = "3306";
$lfmc['sql'] = mysqli_connect($host, $usuario, $clave, $nombre, $puerto);
if (mysqli_connect_errno()) {
    echo "Error al conectarse a la base de datos: " . mysqli_connect_error();
}
$lfmc['sql']->set_charset("utf8");


define('T_USUARIO', 'usuario');

define('T_PRODUCTO', 'producto');
define('T_STOCK', 'stock');
define('T_CATEGORIA', 'categoria');
define('T_SUB_CATEGORIA', 'categoria_sub');
define('T_marca', 'marca');


define('T_VENTA', 'venta');
define('T_VENTA_DET', 'venta_det');

define('T_ABASTECIMIENTO', 'abastecimiento');
define('T_ABASTECIMIENTO_DET', 'abastecimiento_det');