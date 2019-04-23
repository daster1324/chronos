<?php
    require('common.php');
    require('core/includer.php');

    get_head();
?>
<body>
    <div class="main-content bg-dark">
        <div class="section px-2">
            <div id="contenedor-principal">
                <pre class="bg-light p-4">
                <p>Valores recibidos</p>
                    <?php var_dump($_POST); ?>
                </pre>
                <div class="bg-light p-4">
                <p>Cookies recibidas</p>
                <?php 
                    if(isset($_COOKIE['carrera'])) $car = ($_COOKIE['carrera'] != "none") ? $_COOKIE['carrera'] : NULL;
                    if(isset($_COOKIE['itinerario'])) $iti = ($_COOKIE['itinerario'] != "none") ? $_COOKIE['itinerario'] : NULL;

                    echo "Cookie carrera: " . $car . "<br/>";
                    echo "Cookie itinerario: " . $iti;
                ?>
                </div>
            </div>
        </div>
    </div>

    <?php get_scriptsAndFooter(); ?>
