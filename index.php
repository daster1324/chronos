<?php
    session_set_cookie_params((3600*24*30), "/", $_SERVER["SERVER_NAME"], 0, true);
    session_start();

    require('common.php');
    require('core/includer.php');

    get_head();

    $cdao = new Carrera_dao();
    $idao = new Itinerario_dao();
?>
    <div class="main-content-index bg-dark">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="#">Chronos<sup class="logo-sup">BETA</sup></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="/">Inicio <span class="sr-only">(actual)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/proyecto">El proyecto</a>
                </li>
                </ul>
            </div>
        </nav>
        <div class="section px-2">
            <form id="form-inicial" class="app-form" method="post" onsubmit="return submitForm();">
                <select id="selector-carrera" name="carrera" class="custom-select text-dark my-1" required>
                    <option value="none" selected>Selecciona carrera</option>
                    <?php

                    
                    $listado = $cdao->getListado();
                    
                    foreach ($listado as $carrera) {
                        echo '<option value="'.$carrera->getId().'">'.$carrera->getNombre().'</option>';
                    }
                    
                    ?>
                </select>

                <select id="selector-itinerario" name="itinerario" class="custom-select text-dark my-1" disabled required>
                    <option value="none" disabled selected>Selecciona itinerario</option>
                </select>

                <input id="boton-enviar" class="btn btn-light w-100 my-1" type="submit" value="Empezar" disabled>

                <?php
                $car = NULL;
                $iti = NULL;
                if(isset($_SESSION['carrera'])) $car = ($_SESSION['carrera'] != "none") ? $cdao->getById($_SESSION['carrera']) : NULL;
                if(isset($_SESSION['itinerario'])) $iti = ($_SESSION['itinerario'] != "none") ? $idao->getById($_SESSION['itinerario']) : NULL;

                if($car != NULL && $iti != NULL)
                    echo '<a href="/asistente.php" class="btn btn-light btn-continue w-100 my-1">Continuar con <span class="continue-text">'. $car->getNombre() 
                        .'</span> <span class="continue-text">('. $iti->getNombre() .')</span></a>';

                else if($car != NULL && $iti == NULL)
                    echo '<a href="/asistente.php" class="btn btn-light btn-continue w-100 my-1">Continuar con <span class="continue-text">'. $car->getNombre() 
                        .'</span> <span class="continue-text">(Itinerario Único)</span></a>';

                ?>

            </form>
        </div>
    </div>

<?php 

    get_scriptsAndFooter(); 
    
    die();
?>