<?php
    require('common.php');
    require('core/includer.php');

    get_head();
?>
<body>
    <div class="main-content bg-dark">
        <div class="section px-2">
            <form id="form-inicial" action="/async.php" method="post">
                <h1 class="font-weight-light text-center text-light">Chronos</h1>
                <select id="selector-carrera" name="carrera" class="custom-select text-dark my-1">
                    <option value="none" selected>Selecciona carrera</option>
                    <?php

                    $cdao = new Carrera_dao();
                    $listado = $cdao->getListado();
                    
                    foreach ($listado as $carrera) {
                        echo '<option value="'.$carrera->getId().'">'.$carrera->getNombre().'</option>';
                    }
                    
                    ?>
                </select>

                <select id="selector-itinerario" name="itinerario" class="custom-select text-dark my-1" disabled>
                    <option value="none" selected>Selecciona itinerario</option>
                </select>

                <input id="boton-enviar" class="btn btn-light w-100 my-1" type="submit" value="Continuar" disabled>
            </form>
        </div>
    </div>

    <?php get_scripts(); ?>