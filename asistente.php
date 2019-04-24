<?php
    session_start();

    require('common.php');
    require('core/includer.php');

    get_head();

    $cdao = new Carrera_dao();
    $idao = new Itinerario_dao();

    $car = NULL;
    $iti = NULL;

    if(isset($_COOKIE['carrera'])) $car = ($_COOKIE['carrera'] != "none") ? $cdao->getById($_COOKIE['carrera']) : NULL;
    if(isset($_COOKIE['itinerario'])) $iti = ($_COOKIE['itinerario'] != "none") ? $idao->getById($_COOKIE['itinerario']) : NULL;


    if($car == NULL && $iti == NULL){ header("Location: /"); die(); }
        
?>
<body>

    <div class="main-content bg-dark text-light">
        <div id="header" class="text-center border border-bottom-0 border-light py-1">
            <?php echo $car->getNombre() . ' - ' . $iti->getNombre(); ?>
        </div>

        <div id="horario-container" class="border border-light">
            <div id="sidebar" class="border-right border-light">
                <div id="contador" class="text-center border-bottom border-light">
                    Créditos totales: <span id="creditos">0</span>
                </div>
                <div class="action-button">
                    <button type="button" class="btn btn-secondary">Añadir asignatura</button>
                </div>
                <div class="action-button">
                    <button type="button" class="btn btn-secondary">Vaciar horario</button>
                </div>
                <div class="action-button">
                    <button type="button" class="btn btn-secondary">Procesar horario</button>
                </div>
                <div class="action-button">
                    <button type="button" class="btn btn-secondary">Exportar</button>
                </div>
                <div id="asignaturas-container">
                    
                </div>
                <div class="action-button">
                    <a name="" id="" class="btn btn-secondary" href="/" role="button">Volver</a>
                </div>
                
            </div> <!-- Sidebar -->

            <div id="horario">
                <div id="cuatrimestres" class="border-bottom border-light">
                    <button type="button" class="btn btn-secondary">Mostrar otro cuatrimestre</button>
                    <div id="cuatrimestre">Se está mostrando el primer cuatrimestre</div>
                </div>
            </div> <!-- Horario -->

        </div> <!-- Horario-Container -->

    </div> <!-- Main Content -->

    <?php get_scriptsAndFooter(); ?>
