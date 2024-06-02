<?php require_once('../../iniciar_app.php');
    if(Eliminar(T_marca,$_GET['id'])){
        $dato = array( 'estado' => 200, 'mensaje' => "Eliminado correctamente" );
    }else{
        $dato = array( 'estado' => 400, 'mensaje' => "Error al eliminar" );
    }
    echo json_encode($dato);
    exit();
?>