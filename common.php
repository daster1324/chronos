<?php

define("SALT", "7a96d15502f3bf22fb619916778abaf9a4fd37a926c273834458e85c1b5b2e1d");

// Funciones para marcar la sección actual del menú
function active($apartado){
    $cur = (isset($_GET['gestionar'])) ? $_GET['gestionar'] : "/";

    if(($apartado == $cur)){
        echo "active";
    }
}
function sr_only($apartado){
    $cur = (isset($_GET['gestionar'])) ? $_GET['gestionar'] : "/";

    if($apartado == $cur){
        echo '<span class="sr-only">(current)</span>';
    }
}

function get_head(){
    $re = '/^(\/\w+).*$/m';
    $cur = $_SERVER['REQUEST_URI'];

    if($cur != "/"){
        preg_match_all($re, $_SERVER['REQUEST_URI'], $matches, PREG_SET_ORDER, 0);   
        $cur = $matches[0][1];
    }

    switch ($cur) {
        case '/':
            $title = "Chronos | Inicio";
            break;
    
        case '/asistente':
        case '/asistente.php':
            $title = "Chronos | Asistente";
            break;
    
        case '/docentes':
        case '/docentes.php':
            $title = "Chronos | Docentes";
            break;
    
        case '/gestion':
        case '/gestion.php':
            $title = "Chronos | Gestión";
            break;
    
        default:
            $title = "No deberías haber llegado a aquí...";
            break;
    }
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">

        <link rel="stylesheet" href="theme/css/bootstrap.css">
        <link rel="stylesheet" href="theme/css/style.css">

        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">

        <link rel="icon" type="image/png" sizes="16x16" href="/resources/images/favicon.ico">

        <title><?= $title ?></title>
    </head>
    <body class="bg-dark">
    <?php
}

function get_scriptsAndFooter(){
?>
    <script src="resources/js/jquery.min.js"></script>
    <script src="resources/js/ui/jquery-ui.js"></script>
    <script src="resources/js/popper.min.js"></script>
    <script src="theme/js/bootstrap.min.js"></script>
    <script src="resources/js/chronoscript.js"></script>
    
    <?php
    $re = '/^(\/\w+).*$/m';
    $cur = $_SERVER['REQUEST_URI'];

    if($cur != "/"){
        preg_match_all($re, $_SERVER['REQUEST_URI'], $matches, PREG_SET_ORDER, 0);   
        $cur = $matches[0][1];
    }

    switch ($cur) {
        case '/':
            echo '    <script src="resources/js/index.js"></script>';
            break;
    
        case '/asistente':
        case '/asistente.php':
            echo '    <script src="resources/js/asistente.js"></script>';
            break;
    
        case '/docentes':
        case '/docentes.php':
            echo '    <script src="resources/js/docentes.js"></script>';
            break;
    
        case '/gestion':
        case '/gestion.php':
            echo '    <script src="resources/js/gestion.js"></script>';
            break;
    
        default:
            echo '';
            break;
    }
    ?>
    </body>
</html>
<?php
}