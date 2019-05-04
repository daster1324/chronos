<?php
    session_set_cookie_params((3600*24*30), "/", $_SERVER["SERVER_NAME"], 0, true);
    session_start();

    require('common.php');
    require('core/includer.php');

    get_head();

    $cdao = new Carrera_dao();
    $idao = new Itinerario_dao();
?>
    <div class="main-content bg-dark">
        <div class="section px-2">
            <form id="form-inicial" class="app-form" method="post" onsubmit="return submitForm();">
                <h1 class="font-weight-light text-center text-light">Chronos</h1>
                <select id="selector-carrera" name="carrera" class="custom-select text-dark my-1">
                    <option value="none" selected>Selecciona carrera</option>
                    <?php

                    
                    $listado = $cdao->getListado();
                    
                    foreach ($listado as $carrera) {
                        echo '<option value="'.$carrera->getId().'">'.$carrera->getNombre().'</option>';
                    }
                    
                    ?>
                </select>

                <select id="selector-itinerario" name="itinerario" class="custom-select text-dark my-1" disabled>
                    <option value="none" selected>Selecciona itinerario</option>
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

                ?>

            </form>
        </div>
    </div>

<?php 

    get_scriptsAndFooter(); 
    
    die();
?>