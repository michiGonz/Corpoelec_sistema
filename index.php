<?php require_once('nucleo/iniciar_app.php');

    $enlace = explode('/', str_replace("/{$lfmc['carpeta']}/", '', $_SERVER['REQUEST_URI']));
    foreach($enlace as $clave => $valor){
        // $lfmc['link'][$clave-1] = $valor; // con $lfmc['carpeta'] sin definir
        $lfmc['link'][$clave] = $valor; // con $lfmc['carpeta'] definida
    }
    if(isset($_SESSION['usuario']) && empty($lfmc['link'][0])){
        $lfmc['link'][0] = 'dashboard';
    }else if(!isset($_SESSION['usuario']) && empty($lfmc['link'][0])){
        $lfmc['link'][0] = 'inicio';
    }else if(!file_exists("interfaz/paginas/{$lfmc['link'][0]}/contenido.phtml") && !file_exists("interfaz/paginas/{$lfmc['link'][0]}/{$lfmc['link'][1]}.phtml")){
        $lfmc['link'][0] = '404';
    }
    if(!isset($_SESSION['usuario']) && ($lfmc['link'][0]!="inicio" && $lfmc['link'][0]!="iniciar-sesion" && $lfmc['link'][0]!="registrarse" && $lfmc['link'][0]!="recuperar-clave")){
        $lfmc['link'][0] = '404';
    }
    if(isset($_SESSION['usuario']) && ($lfmc['link'][0]=="iniciar-sesion" || $lfmc['link'][0]=="registrarse" || $lfmc['link'][0]=="recuperar-clave")){
        $lfmc['link'][0] = '404';
    }
    if(file_exists("interfaz/paginas/{$lfmc['link'][0]}/{$lfmc['link'][1]}/{$lfmc['link'][2]}.phtml")){
        $lfmc['pagina']    = ucfirst(str_replace('-',' ',str_replace('_',' ',$lfmc['link'][0]))).' | '.str_replace('-',' ',str_replace('_',' ',$lfmc['link'][1])).' | '.str_replace('-',' ',str_replace('_',' ',$lfmc['link'][2]));
        $lfmc['contenido'] = CargarPagina($lfmc['link'][0].'/'.$lfmc['link'][1].'/'.$lfmc['link'][2]);
    }else if(file_exists("interfaz/paginas/{$lfmc['link'][0]}/{$lfmc['link'][1]}.phtml")){
        $lfmc['pagina']    = ucfirst(str_replace('-',' ',str_replace('_',' ',$lfmc['link'][0]))).' | '.str_replace('-',' ',str_replace('_',' ',$lfmc['link'][1]));
        $lfmc['contenido'] = CargarPagina($lfmc['link'][0].'/'.$lfmc['link'][1]);
    }else if(file_exists("interfaz/paginas/{$lfmc['link'][0]}/contenido.phtml")){
        $lfmc['pagina']    = ucfirst(str_replace('-',' ',str_replace('_',' ',$lfmc['link'][0])));
        $lfmc['contenido'] = CargarPagina($lfmc['link'][0].'/contenido');
    }
    if($lfmc['contenido']==""){
        $lfmc['pagina']    = "404";
        $lfmc['contenido'] = CargarPagina('404/contenido');
    }

    require_once('interfaz/index.phtml');
    
    mysqli_close($lfmc['sql']);
    unset($lfmc);
?>