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


    if($car == NULL || $iti == NULL){ header("Location: /"); die(); }        
?>

    <!-- Main Content -->
    <div class="main-content bg-dark text-light">
        <div id="header" class="text-center border border-bottom-0 border-light py-1">
            <?php echo $car->getNombre() . ' - ' . $iti->getNombre(); ?>
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
                    <button onclick="procesarHorario()" type="button" class="btn btn-secondary">Procesar horario</button>
                </div>
                <!--div class="action-button">
                    <button onclick="exportar()" type="button" class="btn btn-secondary">Exportar</button>
                </div-->

                <div class="w-100 mt-3 mb-2 text-center">Asignaturas seleccionadas</div>

                <!-- Asignaturas Container -->
                <div id="asignaturas-container">
                </div>
                <!-- /Asignaturas Container -->

                <div class="action-button">
                    <a name="" id="" class="btn btn-secondary" href="/" role="button">Volver</a>
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
                <?php 
                    //TODO: Mirar si la carrera tiene las clases a en punto o a y media y mostrar las horas acordes
                    //      Mostrar todas las posibles horas es bastante ineficiente. En pantallas 720p, fuerza scroll por falta de sitio.
                    //      En los doble grados sí que es posible que hiciera falta mostrar todas las horas.
                ?>
                    <!-- horas -->
                    <div id="horas">
                        <div class="bg-secondary font-weight-bold cabecera hora"><span>Hora</span></div>
                        <div class="bg-secondary mt-1 font-weight-bold hora"><span>8:00</span></div>
                        <div class="bg-secondary mt-1 font-weight-bold hora"><span>9:00</span></div>
                        <div class="bg-secondary mt-1 font-weight-bold hora"><span>10:00</span></div>
                        <div class="bg-secondary mt-1 font-weight-bold hora"><span>11:00</span></div>
                        <div class="bg-secondary mt-1 font-weight-bold hora"><span>12:00</span></div>
                        <div class="bg-secondary mt-1 font-weight-bold hora"><span>13:00</span></div>
                        <div class="bg-secondary mt-1 font-weight-bold hora"><span>14:00</span></div>
                        <div class="bg-secondary mt-1 font-weight-bold hora"><span>15:00</span></div>
                        <div class="bg-secondary mt-1 font-weight-bold hora"><span>16:00</span></div>
                        <div class="bg-secondary mt-1 font-weight-bold hora"><span>17:00</span></div>
                        <div class="bg-secondary mt-1 font-weight-bold hora"><span>18:00</span></div>
                        <div class="bg-secondary mt-1 font-weight-bold hora"><span>19:00</span></div>
                        <div class="bg-secondary mt-1 font-weight-bold hora"><span>20:00</span></div>
                    </div>
                    <!-- /horas -->

                    <!-- lunes -->
                    <div id="lunes" class="dia ml-1">
                        <div class="bg-secondary font-weight-bold cabecera dia"><span>Lunes</span></div>
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 8:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 9:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 10:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 11:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 12:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 13:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 14:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 15:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 16:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 17:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 18:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 19:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 20:00 -->
                    </div>
                    <!-- /lunes -->

                    <!-- martes -->
                    <div id="martes" class="dia ml-1">
                        <div class="bg-secondary font-weight-bold cabecera dia"><span>Martes</span></div>
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 8:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 9:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 10:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 11:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 12:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 13:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 14:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 15:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 16:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 17:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 18:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 19:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 20:00 -->
                    </div>
                    <!-- /martes -->

                    <!-- miercoles -->
                    <div id="miercoles" class="dia ml-1">
                        <div class="bg-secondary font-weight-bold cabecera dia"><span>Miércoles</span></div>
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 8:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 9:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 10:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 11:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 12:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 13:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 14:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 15:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 16:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 17:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 18:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 19:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 20:00 -->
                    </div>
                    <!-- /miercoles -->

                    <!-- jueves -->
                    <div id="jueves" class="dia ml-1">
                        <div class="bg-secondary font-weight-bold cabecera dia"><span>Jueves</span></div>
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 8:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 9:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 10:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 11:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 12:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 13:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 14:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 15:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 16:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 17:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 18:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 19:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 20:00 -->
                    </div>
                    <!-- /jueves -->

                    <!-- viernes -->
                    <div id="viernes" class="dia ml-1">
                        <div class="bg-secondary font-weight-bold cabecera dia"><span>Viernes</span></div>
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 8:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 9:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 10:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 11:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 12:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 13:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 14:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 15:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 16:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 17:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 18:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 19:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 20:00 -->
                    </div>
                    <!-- /viernes -->

                    <!-- sabado -->
                    <div id="sabado" class="dia ml-1">
                        <div class="bg-secondary font-weight-bold cabecera dia"><span>Sábado</span></div>
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 8:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 9:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 10:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 11:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 12:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 13:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 14:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 15:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 16:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 17:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 18:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 19:00 -->
                        <div class="casillero hora-vacia mt-1 dia"><span></span></div><!-- 20:00 -->
                    </div>
                    <!-- /sabado -->
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
                            echo $space.'<option value="' . $curso . '">' . $curso . 'º</option>';
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
<div id="debug"></div>

<?php 
    get_scriptsAndFooter(); 
    
    die();
?>
