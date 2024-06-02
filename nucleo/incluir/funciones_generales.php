<?php
function CargarPagina($pagina_url, $ajax = false) {
    global $lfmc;
    $pagina = (($ajax) ? '../../../' : './') . "interfaz/paginas/$pagina_url.phtml";
    if (file_exists($pagina)) {
        $contenido_pagina = '';
        ob_start();
        require($pagina);
        $contenido_pagina = ob_get_contents();
        ob_end_clean();
        return $contenido_pagina;
    } else {
        return false;
    }
}
function CargarEnlace($enlace = '') {
    global $lfmc;
    return "{$lfmc['sitio_url']}/$enlace";
}
function TextoCorto($texto = "", $largo = 100) {
    global $lfmc;
    if (empty($texto) || !is_string($texto) || !is_numeric($largo) || $largo < 1) {
        return "---";
    }
    if (strlen($texto) > $largo) {
        $texto = mb_substr($texto, 0, $largo, "UTF-8") . "...";
    }
    return $texto;
}
function ObtenerPrecio($precio = "", $simbolo = 'Bs') {
    return number_format($precio, 2) . " $simbolo";
}
function ObtenerFecha($fecha) {
    global $lfmc;
    $h = explode(':', explode(' ', $fecha)[1]);
    $f = explode('-', explode(' ', $fecha)[0]);
    switch ($f[1]) {
        case '01':
            $mes = "Enero";
            break;
        case '02':
            $mes = "Febrero";
            break;
        case '03':
            $mes = "Marzo";
            break;
        case '04':
            $mes = "Abril";
            break;
        case '05':
            $mes = "Mayo";
            break;
        case '06':
            $mes = "Junio";
            break;
        case '07':
            $mes = "Julio";
            break;
        case '08':
            $mes = "Agosto";
            break;
        case '09':
            $mes = "Septiembre";
            break;
        case '10':
            $mes = "Octubre";
            break;
        case '11':
            $mes = "Noviembre";
            break;
        case '12':
            $mes = "Diciembre";
            break;
    }
    $consulta['fecha'] = "{$f[2]} de $mes de {$f[0]}";

    $h_               = ($h[0] > 12) ? (($h[0] - 12 == 0) ? 12 : $h[0] - 12) : (($h[0] == 0) ? 12 : $h[0]);
    $consulta['hora'] = (($h_ < 10) ? '0' + $h_ : $h_) . ':' . $h[1] . ':' . $h[2] . ' ' . (($h[0] >= 0 && $h[0] < 12) ? 'AM' : 'PM');
    return $consulta;
}
function GenerarClave($longitud_minima = 20, $longitud_maxima = 20, $minuscula = true, $mayusculas = true, $numeros = true, $caracteres_especiales = false) {
    $caracter = '';
    $clave    = '';
    if ($minuscula) {
        $caracter .= "abcdefghijklmnopqrstuvwxyz";
    }
    if ($mayusculas) {
        $caracter .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    }
    if ($numeros) {
        $caracter .= "123456789";
    }
    if ($caracteres_especiales) {
        $caracter .= "~@#$%^*()_+-={}|][";
    }
    if ($longitud_minima > $longitud_maxima) {
        $largo = mt_rand($longitud_maxima, $longitud_minima);
    } else {
        $largo = mt_rand($longitud_minima, $longitud_maxima);
    }
    for ($i = 0; $i < $largo; $i++) {
        $clave .= $caracter[(mt_rand(0, strlen($caracter) - 1))];
    }
    return $clave;
}
function SubirArchivo($archivo = array(), $carpeta = 'archivo') {
    global $lfmc;
    if (!file_exists("../../../almacenaje")) {
        Mkdir("../../../almacenaje");
    }
    if (!file_exists("../../../almacenaje/$carpeta")) {
        Mkdir("../../../almacenaje/$carpeta");
    }
    $extension  = pathinfo(pathinfo($archivo['name'], PATHINFO_FILENAME) . '.' . strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION)), PATHINFO_EXTENSION);
    $directorio = "almacenaje/$carpeta/" . date('Y-m-d_h-i-s-A') . "__" . GenerarClave(8, 8) . ".{$extension}";
    if (move_uploaded_file($archivo['tmp_name'], "../../../$directorio")) {
        return $directorio;
    } else {
        return "";
    }
}

function Registrar($tabla, $registrar_datos = array(), $regresar_id = false) {
    global $lfmc;
    if (empty($registrar_datos)) {
        return false;
    }
    $claves   = implode(', ', array_keys($registrar_datos));
    $valores  = '\'' . implode('\', \'', $registrar_datos) . '\'';
    $consulta = mysqli_query($lfmc['sql'], "INSERT INTO " . $tabla . " ($claves) VALUES ($valores)");
    if ($consulta) {
        if ($regresar_id == true) {
            return mysqli_insert_id($lfmc['sql']);
        } else {
            return true;
        }
    } else {
        return false;
    }
}
function Actualizar($tabla_nombre, $id, $datos_array = array(), $columna_id = 'id') {
    global $lfmc;
    if (empty($datos_array)) {
        return false;
    }
    foreach ($datos_array as $clave => $valor) {
        $datos[] = "$clave = '$valor'";
    }
    $datos    = implode(', ', $datos);
    $consulta = mysqli_query($lfmc['sql'], "UPDATE " . $tabla_nombre . " SET $datos WHERE $columna_id = '$id'");
    if ($consulta) {
        return true;
    } else {
        return false;
    }
}
function Eliminar($tabla, $id, $where = 'id') {
    global $lfmc;
    if (mysqli_query($lfmc['sql'], "DELETE FROM $tabla WHERE $where = '$id'")) {
        return true;
    } else {
        return false;
    }
}

function Obtener($tabla, $id, $where = 'id', $id2 = '', $where2 = '') {
    global $lfmc;
    if ($id2 != '' && $where2 != '') {
        $where2 = "AND $where2 = '$id2'";
    }
    return mysqli_fetch_assoc(mysqli_query($lfmc['sql'], "SELECT * FROM $tabla WHERE $where ='$id' $where2"));
}
function ObtenerTodos($tabla, $id = '', $where = '', $id2 = '', $where2 = '', $id3 = '', $where3 = '', $ordenar_por = 'id', $ordenar_tipo = 'DESC') {
    global $lfmc;
    if ($id != '' && $where != '') {
        $where = "AND $where = '$id'";
    }
    if ($id2 != '' && $where2 != '') {
        $where2 = "AND $where2 = '$id2'";
    }
    if ($id3 != '' && $where3 != '') {
        $where3 = "AND $where3 = '$id3'";
    }
    $consulta = mysqli_query($lfmc['sql'], "SELECT * FROM $tabla WHERE id != '' $where $where2 $where3 ORDER BY $ordenar_por $ordenar_tipo");
    while ($dato = mysqli_fetch_assoc($consulta)) {
        $datos[] = $dato;
    }
    return $datos;
}
function Total($tabla, $id = '', $where = '', $id2 = '', $where2 = '', $id3 = '', $where3 = '') {
    global $lfmc;
    if ($id != '' && $where != '') {
        $where = "WHERE $where = '$id'";
    }
    if ($id2 != '' && $where2 != '') {
        $where2 = "AND $where2 = '$id2'";
    }
    if ($id3 != '' && $where3 != '') {
        $where3 = "AND $where3 = '$id3'";
    }
    return mysqli_fetch_assoc(mysqli_query($lfmc['sql'], "SELECT COUNT(id) as contar FROM $tabla $where $where2 $where3"))['contar'];
}
function Existe($tabla, $id, $where = "id") {
    global $lfmc;
    $consulta = mysqli_fetch_row(mysqli_query($lfmc['sql'], "SELECT COUNT(id) FROM $tabla WHERE $where = '$id'"));
    return ($consulta[0] >= 1) ? true : false;
}



function ObtenerProductos($id = '', $where = '', $id2 = '', $where2 = '', $id3 = '', $where3 = '', $ordenar_por = 'id', $ordenar_tipo = 'DESC') {
    global $lfmc;
    if ($id != '' && $where != '') {
        $where = "AND $where = '$id'";
    }
    if ($id2 != '' && $where2 != '') {
        $where2 = "AND $where2 = '$id2'";
    }
    if ($id3 != '' && $where3 != '') {
        $where3 = "AND $where3 = '$id3'";
    }
    $consulta = mysqli_query($lfmc['sql'], "SELECT id FROM " . T_PRODUCTO . " WHERE id != '' $where $where2 $where3 ORDER BY $ordenar_por $ordenar_tipo");
    while ($dato = mysqli_fetch_assoc($consulta)) {
        $datos[] = ObtenerProducto($dato['id']);
    }
    return $datos;
}
function ObtenerProducto($id, $where = 'id', $id2 = '', $where2 = '') {
    global $lfmc;
    if ($id2 != '' && $where2 != '') {
        $where2 = "AND $where2 = '$id2'";
    }
    $producto                  = mysqli_fetch_assoc(mysqli_query($lfmc['sql'], "SELECT * FROM " . T_PRODUCTO . " WHERE $where ='$id' $where2"));
    $producto['categoria']     = Obtener(T_CATEGORIA, $producto['categoria_id']);
    $producto['sub_categoria'] = Obtener(T_SUB_CATEGORIA, $producto['sub_categoria_id']);
    $producto['marca']         = Obtener(T_marca, $producto['marca_id']);
    $producto['stocks']        = ObtenerTodos(T_STOCK, $producto['id'], 'producto_id');
    $producto['cntd']          = 0;
    foreach ($producto['stocks'] as $stock) {
        $producto['cntd'] = $producto['cntd'] + $stock['cntd'];
    }
    return $producto;
}

