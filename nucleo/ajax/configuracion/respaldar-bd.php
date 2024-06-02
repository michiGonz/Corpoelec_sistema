<?php require_once('../../iniciar_app.php');
$conn = new mysqli($host, $usuario, $clave, $nombre);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
$tables = array();
$result = $conn->query("SHOW TABLES");
while ($row = $result->fetch_row()) {
    $tables[] = $row[0];
}
$archivoRuta = "../../../respaldo/BD-" . date('Y-m-d_') . time() . ".sql";
$backupFile = fopen($archivoRuta, 'w');
foreach ($tables as $table) {
    fwrite($backupFile, "-- Estructura de la tabla '{$table}'\n drop table $table;\n");
    fwrite($backupFile, "CREATE TABLE IF NOT EXISTS `{$table}` (\n");
    $result = $conn->query("SHOW COLUMNS FROM `{$table}`");
    while ($row = $result->fetch_assoc()) {
        fwrite($backupFile, "  `{$row['Field']}` {$row['Type']}");
        if ($row['Null'] != 'YES') {
            fwrite($backupFile, " NOT NULL");
        }
        if ($row['Default'] !== null) {
            fwrite($backupFile, " DEFAULT '{$row['Default']}'");
        }
        if ($row['Extra'] != '') {
            fwrite($backupFile, " {$row['Extra']}");
        }
        fwrite($backupFile, ",\n");
    }
    fwrite($backupFile, "  PRIMARY KEY (`id`)\n");
    fwrite($backupFile, ");\n\n");
    fwrite($backupFile, "-- Datos de la tabla '{$table}'\n");
    $result = $conn->query("SELECT * FROM `{$table}`");
    while ($row = $result->fetch_assoc()) {
        $values = array();
        foreach ($row as $value) {
            $values[] = "'" . $conn->real_escape_string($value) . "'";
        }
        fwrite($backupFile, "INSERT INTO `{$table}` VALUES (" . implode(',', $values) . ");\n");
    }
    fwrite($backupFile, "\n");
}
fclose($backupFile);
$conn->close();
if (file_exists($archivoRuta)) {
    $dato = array('estado' => 200, 'mensaje' => "Base de datos respaldada con éxito");
} else {
    $dato = array('estado' => 400, 'mensaje' => "Error al respaldar ");
}
echo json_encode($dato);
exit();
