<?php

function plugin_informatica($fichero, $destino){
    include('loaders/informatica.php');
    echo test_informatica();
    try{
        if($destino == 'horario'){
            importar_horario_informatica($fichero);
        }
        else{
            importar_horario_docencia($fichero);
        }
        header("HTTP/1.1 301 Moved Permanently"); 
        header("Location: /gestion?gestionar=importar&message=1");
    }catch (Exception $e) {
        echo 'Excepción capturada: ',  $e->getMessage(), "\n";
        header("HTTP/1.1 301 Moved Permanently"); 
        header("Location: /gestion?gestionar=importar&message=2");
    }
}


function load_horario($facultad, $fichero){
    switch ($facultad) {
        case '1': // Informática
            plugin_informatica($fichero, 'horario');
        break;
        
        default: break;
    }
}

function load_docencia($facultad, $fichero){
    switch ($facultad) {
        case '1': // Informática
            plugin_informatica($fichero, 'docencia');
        break;
        
        default: break;
    }
}




?>