<?php
    session_set_cookie_params((3600*24*30), "/", $_SERVER["SERVER_NAME"], 0, true);
    session_start();

    require('common.php');
    require('core/includer.php');

    get_head();

    $cdao = new Carrera_dao();
    $idao = new Itinerario_dao();

    $car = NULL;
    $iti = NULL;

    if(isset($_SESSION['carrera'])) $car = ($_SESSION['carrera'] != "none") ? $cdao->getById($_SESSION['carrera']) : NULL;
    if(isset($_SESSION['itinerario'])) $iti = ($_SESSION['itinerario'] != "none") ? $idao->getById($_SESSION['itinerario']) : NULL;


    if($car == NULL){ header("Location: /"); die(); }    
    
    $head_car = $car->getNombre();
    $head_iti = ($iti != NULL) ? $iti->getNombre() : 'Itinerario Único';
    
?>

    <!-- Main Content -->
    <div class="main-content bg-dark text-light">
        <div id="header" class="text-center border border-bottom-0 border-light py-1">
            <?= $head_car . ' - ' . $head_iti ?>
        </div>

        <!-- Horario-Container -->
        <div id="horario-container" class="border border-light">

            <!-- Sidebar -->
            <div id="sidebar" class="border-right border-light">
                <div id="contador" class="text-center border-bottom border-light">
                    Créditos totales: <span id="creditos">0</span>
                </div>

                <div class="action-button">
                    <button data-toggle="modal" data-target="#addasignatura" type="button" class="btn btn-secondary">Añadir asignatura</button>
                </div>
                <div class="action-button">
                    <button onclick="vaciarHorario()" type="button" class="btn btn-secondary">Vaciar horario</button>
                </div>
                <div class="action-button">
                <button data-toggle="modal" data-target="#procesar-horario" type="button" class="btn btn-secondary">Procesar horario</button>
                </div>

                <div class="w-100 mt-3 mb-2 text-center">Asignaturas seleccionadas <i class="fas fa-exclamation-circle" data-container="body" data-toggle="popover" data-placement="right" data-content="El orden de esta lista indica la prioridad. Intentaremos crearte el horario tomando primero las asignaturas de más arriba."></i></div>

                <!-- Asignaturas Container -->
                <div id="asignaturas-container">
                </div>
                <!-- /Asignaturas Container -->

                <div class="action-button">
                    <a class="btn btn-secondary" href="/" role="button">Volver</a>
                </div>
                
            </div> 
            <!-- /Sidebar -->

            <!-- Horario -->
                <div id="horario">
                    <div id="cuatrimestres" class="border-bottom border-light">
                        <button type="button" onclick="cambiarCuatrimestre()" class="btn btn-secondary" title="Mostrar el otro cuatrimestre">1ºQ <i class="fas fa-exchange-alt"></i> 2ºQ</button>
                        <div id="cuatrimestre">Se está mostrando el primer cuatrimestre</div>
                    </div>

                    <!-- Semana -->
                    <div id="semana">
                        
                        <!-- horas -->
                        <?php  
                            print_horas();
                        ?>
                        <!-- /horas -->
                        <?php
                            print_dia("Lunes");
                            print_dia("Martes");
                            print_dia("Miércoles");
                            print_dia("Jueves");
                            print_dia("Viernes");
                            //print_dia("Sábado");

                        ?>
                    </div>
                    <!-- /Semana -->

                </div> 
            <!-- /Horario -->

        </div>
        <!-- /Horario-Container -->

    </div>
    <!-- /Main Content -->

    <!-- Modal Añadir Asignatura -->
        <div class="modal fade" id="addasignatura" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Añadir asignatura</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form-add-asignatura">
                        <select name="curso" id="addasignatura-curso" class="custom-select my-1">
                            <option value="none">Selecciona un curso</option>
                            <?php
                                $adao = new Asignatura_dao();
                                $cursos = $adao->getCursos($car->getId());
                                $space = "                        ";
                                foreach ($cursos as $curso) {
                                    if($curso == 0){
                                        echo $space.'<option value="' . $curso . '">Optativas</option>';
                                    }
                                    else{
                                        echo $space.'<option value="' . $curso . '">' . $curso . 'º</option>';
                                    }
                                }
                            ?>
                        </select>
                        <select name="asignatura" id="addasignatura-asignatura" class="custom-select my-1" disabled>
                            <option value="none">Selecciona una asignatura</option>
                        </select>
                    </form>                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Volver</button>
                    <button type="button" id="addasignatura-add" class="btn btn-primary" data-dismiss="modal" onclick="addAsignatura()" disabled>Añadir</button>
                </div>
                </div>
            </div>
        </div>
    <!-- /Modal Añadir Asignatura -->
    <!-- Modal Procesar Horario -->
    <div class="modal fade" id="procesar-horario" tabindex="-1" role="dialog" aria-labelledby="titulo-procesar-horario" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="titulo-procesar-horario">¿Cuál es tu disponibilidad?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-add-asignatura">
                    <p>Dinos que franja de horas es la que más se adapta a tu disponibilidad</p>
                    <select class="custom-select text-dark mb-4" name="disponibilidad" id="selector-disponibilidad">
                        <option value="allday" selected>Todo el día (9:00 ~ 20:00)</option>
                        <option value="morning">Mañanas (9:00 ~ 14:00)</option>
                        <option value="afternoon">Tardes (15:00 ~ 20:00)</option>
                    </select>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Volver</button>
                <button type="button" id="addasignatura-add" class="btn btn-primary" data-dismiss="modal" onclick="procesarHorario()">Comenzar</button>
            </div>
            </div>
        </div>
    </div>
    <!-- /Modal Procesar Horario -->
<div id="debug"></div>

<?php 

    function print_horas(){
        ?>
        <div id="horas">
            <div class="bg-secondary font-weight-bold cabecera hora"><span>Hora</span></div>
            <div class="bg-secondary mt-1 font-weight-bold hora"><span>8:00</span></div>
            <div class="bg-secondary mt-1 font-weight-bold hora"><span>8:30</span></div>
            <div class="bg-secondary mt-1 font-weight-bold hora"><span>9:00</span></div>
            <div class="bg-secondary mt-1 font-weight-bold hora"><span>9:30</span></div>
            <div class="bg-secondary mt-1 font-weight-bold hora"><span>10:00</span></div>
            <div class="bg-secondary mt-1 font-weight-bold hora"><span>10:30</span></div>
            <div class="bg-secondary mt-1 font-weight-bold hora"><span>11:00</span></div>
            <div class="bg-secondary mt-1 font-weight-bold hora"><span>11:30</span></div>
            <div class="bg-secondary mt-1 font-weight-bold hora"><span>12:00</span></div>
            <div class="bg-secondary mt-1 font-weight-bold hora"><span>12:30</span></div>
            <div class="bg-secondary mt-1 font-weight-bold hora"><span>13:00</span></div>
            <div class="bg-secondary mt-1 font-weight-bold hora"><span>13:30</span></div>
            <div class="bg-secondary mt-1 font-weight-bold hora"><span>14:00</span></div>
            <div class="bg-secondary mt-1 font-weight-bold hora"><span>14:30</span></div>
            <div class="bg-secondary mt-1 font-weight-bold hora"><span>15:00</span></div>
            <div class="bg-secondary mt-1 font-weight-bold hora"><span>15:30</span></div>
            <div class="bg-secondary mt-1 font-weight-bold hora"><span>16:00</span></div>
            <div class="bg-secondary mt-1 font-weight-bold hora"><span>16:30</span></div>
            <div class="bg-secondary mt-1 font-weight-bold hora"><span>17:00</span></div>
            <div class="bg-secondary mt-1 font-weight-bold hora"><span>17:30</span></div>
            <div class="bg-secondary mt-1 font-weight-bold hora"><span>18:00</span></div>
            <div class="bg-secondary mt-1 font-weight-bold hora"><span>18:30</span></div>
            <div class="bg-secondary mt-1 font-weight-bold hora"><span>19:00</span></div>
            <div class="bg-secondary mt-1 font-weight-bold hora"><span>19:30</span></div>
            <div class="bg-secondary mt-1 font-weight-bold hora"><span>20:00</span></div>
        </div>

        <?php
    }

    function print_dia($dia){
        switch ($dia) {
            case 'Miércoles':
                $dia_id = 'miercoles';
                break;

            case 'Sábado':
                $dia_id = 'sabado';
                break;
            
            default:
                $dia_id = strtolower($dia);
                break;
        }

        $d = (strtolower($dia_id) == "miercoles") ? 'x' : strtolower($dia[0]);

        ?>
        <!-- <?= $dia ?> -->
        <div id="<?= $dia_id ?>" class="dia ml-1">
            <div class="bg-secondary font-weight-bold cabecera dia"><span><?= $dia ?></span></div>
            <div id="hora-<?= $d ?>-0" class="casillero hora-vacia mt-1 dia"><span></span></div>
            <div id="hora-<?= $d ?>-1" class="casillero hora-vacia mt-1 dia"><span></span></div>
            <div id="hora-<?= $d ?>-2" class="casillero hora-vacia mt-1 dia"><span></span></div>
            <div id="hora-<?= $d ?>-3" class="casillero hora-vacia mt-1 dia"><span></span></div>
            <div id="hora-<?= $d ?>-4" class="casillero hora-vacia mt-1 dia"><span></span></div>
            <div id="hora-<?= $d ?>-5" class="casillero hora-vacia mt-1 dia"><span></span></div>
            <div id="hora-<?= $d ?>-6" class="casillero hora-vacia mt-1 dia"><span></span></div>
            <div id="hora-<?= $d ?>-7" class="casillero hora-vacia mt-1 dia"><span></span></div>
            <div id="hora-<?= $d ?>-8" class="casillero hora-vacia mt-1 dia"><span></span></div>
            <div id="hora-<?= $d ?>-9" class="casillero hora-vacia mt-1 dia"><span></span></div>
            <div id="hora-<?= $d ?>-10" class="casillero hora-vacia mt-1 dia"><span></span></div>
            <div id="hora-<?= $d ?>-11" class="casillero hora-vacia mt-1 dia"><span></span></div>
            <div id="hora-<?= $d ?>-12" class="casillero hora-vacia mt-1 dia"><span></span></div>
            <div id="hora-<?= $d ?>-13" class="casillero hora-vacia mt-1 dia"><span></span></div>
            <div id="hora-<?= $d ?>-14" class="casillero hora-vacia mt-1 dia"><span></span></div>
            <div id="hora-<?= $d ?>-15" class="casillero hora-vacia mt-1 dia"><span></span></div>
            <div id="hora-<?= $d ?>-16" class="casillero hora-vacia mt-1 dia"><span></span></div>
            <div id="hora-<?= $d ?>-17" class="casillero hora-vacia mt-1 dia"><span></span></div>
            <div id="hora-<?= $d ?>-18" class="casillero hora-vacia mt-1 dia"><span></span></div>
            <div id="hora-<?= $d ?>-19" class="casillero hora-vacia mt-1 dia"><span></span></div>
            <div id="hora-<?= $d ?>-20" class="casillero hora-vacia mt-1 dia"><span></span></div>
            <div id="hora-<?= $d ?>-21" class="casillero hora-vacia mt-1 dia"><span></span></div>
            <div id="hora-<?= $d ?>-22" class="casillero hora-vacia mt-1 dia"><span></span></div>
            <div id="hora-<?= $d ?>-23" class="casillero hora-vacia mt-1 dia"><span></span></div>
            <div id="hora-<?= $d ?>-24" class="casillero hora-vacia mt-1 dia"><span></span></div>
        </div>
    <!-- <?= $dia ?> -->
    <?php
    }

    get_scriptsAndFooter(); 
    
    die();
?>
