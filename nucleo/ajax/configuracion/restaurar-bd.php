<?php require_once('../../iniciar_app.php');
$conn = new mysqli($host, $usuario, $clave, $nombre);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$sql = file_get_contents('../../.'.$_GET['bd_ruta']);

if ($conn->multi_query($sql) === TRUE) {
    $dato = array('estado' => 200, 'mensaje' => "Restauración realizada con éxito");
} else {
    $dato = array('estado' => 400, 'mensaje' => "Error al restaurar");
}
$conn->close();
echo json_encode($dato);
exit();
?>