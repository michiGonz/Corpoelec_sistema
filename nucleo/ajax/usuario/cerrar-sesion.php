<?php require_once('../../iniciar_app.php');
    if(isset($_SESSION['usuario'])){
        session_unset();
        session_destroy();
        unset($_SESSION);
    }
    echo json_encode($dato);
    exit();
?>