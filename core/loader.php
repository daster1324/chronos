<?php

function plugin_informatica($fichero, $destino){
    include('loaders/informatica.php');
    echo test_informatica();
    if($destino == 'horario'){
        importar_horario_informatica($fichero);
    }
    else{
        importar_horario_docencia($fichero);
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