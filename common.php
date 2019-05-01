<?php

function get_head(){
    $cur = $_SERVER['REQUEST_URI'];
    $title = "";
    switch ($cur) {
        case '/':
            $title = "Chronos | Inicio";
            break;
    
        case '/asistente':
        case '/asistente.php':
            $title = "Chronos | Asistente";
            break;
    
        case '/profesores':
        case '/profesores.php':
            $title = "Chronos | Profesores";
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
    $cur = $_SERVER['REQUEST_URI'];
    $title = "";
    switch ($cur) {
        case '/':
            echo '    <script src="resources/js/index.js"></script>';
            break;
    
        case '/asistente':
        case '/asistente.php':
            echo '    <script src="resources/js/asistente.js"></script>';
            break;
    
        case '/profesores':
        case '/profesores.php':
            echo '    <script src="resources/js/profesores.js"></script>';
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