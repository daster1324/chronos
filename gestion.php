<?php
    session_set_cookie_params((3600*24*30), "/", $_SERVER["SERVER_NAME"], 0, true);
    session_start();

    require('common.php');
    require('core/includer.php');

    // Se capturan los POSTs (salvo el de login)
    if(isset($_POST['accion']) && !empty($_POST['accion'])){ 
        echo '<div class="bg-light">';
        var_dump($_POST); 
        echo '</div>';
    
        if($_POST['accion'] == "add"){
            switch ($_POST['page']) {
                case 'facultad':
                    if(strlen($_POST['nombre-facultad']) > 0 && strlen($_POST['campus-facultad']) > 0){
                        $fdao = new Facultad_dao();

                        $existe = $fdao->busca($_POST['nombre-facultad'], $_POST['campus-facultad']);
                        $mensaje = 1;

                        if(!$existe){
                            $fdao->store(new Facultad(NULL, $_POST['nombre-facultad'], $_POST['campus-facultad']));
                        }
                        else{
                            $mensaje = 4;
                        }
                        
                        unset($fdao);
                        header("HTTP/1.1 301 Moved Permanently"); 
                        header("Location: /gestion?gestionar=facultades&message=".$mensaje);
                    }
                break;

                case 'carrera':
                    if(strlen($_POST['nombre-carrera']) > 0 && strlen($_POST['facultad']) > 0){
                        $cdao = new Carrera_dao();

                        $nombre = $_POST['nombre-carrera'];
                        $facultad = $_POST['facultad'];
                        $facultad_dg = ($_POST['facultad-dg'] == "none") ? NULL : $_POST['facultad-dg'];

                        $existe = $cdao->busca($nombre, $facultad, $facultad_dg);
                        $mensaje = 1;

                        if(!$existe){
                            $cdao->store(new Carrera(NULL, $nombre, $facultad, $facultad_dg));
                        }
                        else{
                            $mensaje = 4;
                        }
                        
                        unset($cdao);
                        header("HTTP/1.1 301 Moved Permanently"); 
                        header("Location: /gestion?gestionar=carreras&message=".$mensaje);
                    }
                break;
                
                case 'itinerario':
                    if(strlen($_POST['nombre-itinerario']) > 0 && strlen($_POST['carrera']) > 0){
                        $idao = new Itinerario_dao();

                        $nombre = $_POST['nombre-itinerario'];
                        $carrera = $_POST['carrera'];

                        $existe = $idao->busca($nombre, $carrera);
                        $mensaje = 1;

                        if(!$existe){
                            $idao->store(new Itinerario(NULL, $carrera, $nombre));
                        }
                        else{
                            $mensaje = 4;
                        }
                        
                        unset($idao);
                        header("HTTP/1.1 301 Moved Permanently"); 
                        header("Location: /gestion?gestionar=itinerarios&message=".$mensaje);
                    }
                break;
                
                case 'departamento':
                    if(strlen($_POST['nombre-departamento']) > 0 && strlen($_POST['facultad']) > 0){
                        $ddao = new Departamento_dao();

                        $nombre = $_POST['nombre-departamento'];
                        $facultad = $_POST['facultad'];

                        $existe = $ddao->busca($nombre, $facultad);
                        $mensaje = 1;

                        if(!$existe){
                            $ddao->store(new Departamento(NULL, $nombre, $facultad));
                        }
                        else{
                            $mensaje = 4;
                        }
                        
                        unset($ddao);
                        header("HTTP/1.1 301 Moved Permanently"); 
                        header("Location: /gestion?gestionar=departamentos&message=".$mensaje);
                    }
                break;

                case 'asignatura':
                    $gea = $_POST['gea'];
                    $carrera = $_POST['carrera'];
                    $itinerario = (!isset($_POST['itinerario']) || $_POST['itinerario'] == 0) ? NULL : $_POST['itinerario'];
                    $nombre = $_POST['nombre-asignatura'];
                    $abreviatura = ($_POST['abreviatura'] != "") ? $_POST['abreviatura'] : NULL;
                    $curso = $_POST['curso'];
                    $dep1 = $_POST['departamento-1'];
                    $dep2 = ($_POST['departamento-2'] != "") ? $_POST['departamento-2'] : NULL;
                    $creditos = $_POST['creditos'];
                    $docentes = $_POST['docentes'];

                    $adao = new Asignatura_dao();
                    $existe = $adao->getById($gea);
                    $mensaje = 1;

                    if($existe == NULL){
                        $adao->store(new Asignatura($gea, $carrera, $itinerario, $nombre, $abreviatura, $curso, $dep1, $dep2, $creditos, $docentes));
                    }
                    else{
                        $mensaje = 4;
                    }

                    unset($adao);
                    header("HTTP/1.1 301 Moved Permanently"); 
                    header("Location: /gestion?gestionar=asignaturas&message=".$mensaje);
                break;
                
                case 'clase':

                break;

                case 'docente':
                    $facultad = $_POST['facultad'];
                    $departamento = $_POST['departamento'];
                    $nombre = $_POST['nombre-docente'];
                    $usuario = $_POST['usuario-docente'];
                    $pass = $usuario . SALT . $_POST['password-docente'];
                    $pass = hash("sha256", $pass);

                    $dodao = new Docente_dao();

                    $existe = $dodao->getByUsuario($usuario);
                    $mensaje = 1;

                    if($existe == NULL){
                        $dodao->store(new Docente(NULL, $nombre, $departamento, "", 0, $usuario, $pass));
                    }
                    else{
                        $mensaje = 4;
                    }

                    unset($dodao);
                    header("HTTP/1.1 301 Moved Permanently"); 
                    header("Location: /gestion?gestionar=docentes&message=".$mensaje);
                break;

                default:
                    # code...
                    break;
            }
        }
        
        if($_POST['accion'] == "edit"){
            switch ($_POST['page']) {
                case 'facultad':
                    if(strlen($_POST['nombre-facultad']) > 0 && strlen($_POST['campus-facultad']) > 0){
                        $fdao = new Facultad_dao();
                        $fdao->store(new Facultad($_POST['id-facultad'], $_POST['nombre-facultad'], $_POST['campus-facultad']));
                        
                        unset($fdao);
                        header("HTTP/1.1 301 Moved Permanently"); 
                        header("Location: /gestion?gestionar=facultades&message=2");
                    }
                break;
                
                case 'carrera':
                    if(strlen($_POST['nombre-carrera']) > 0 && strlen($_POST['facultad']) > 0){
                        $id = $_POST['id-carrera'];
                        $nombre = $_POST['nombre-carrera'];
                        $facultad = $_POST['facultad'];
                        $facultad_dg = ($_POST['facultad-dg'] == "none") ? NULL : $_POST['facultad-dg'];

                        $fdao = new Carrera_dao();
                        $fdao->store(new Carrera($id, $nombre, $facultad, $facultad_dg));
                        
                        unset($fdao);
                        header("HTTP/1.1 301 Moved Permanently"); 
                        header("Location: /gestion?gestionar=carreras&message=2");
                    }
                break;
                
                case 'itinerario':
                    if(strlen($_POST['nombre-itinerario']) > 0 && strlen($_POST['carrera']) > 0){
                        $id = $_POST['id-itinerario'];
                        $nombre = $_POST['nombre-itinerario'];
                        $id_carrera = $_POST['carrera'];

                        $idao = new Itinerario_dao();
                        $idao->store(new Itinerario($id, $id_carrera, $nombre));
                        
                        unset($idao);
                        header("HTTP/1.1 301 Moved Permanently"); 
                        header("Location: /gestion?gestionar=itinerarios&message=2");
                    }
                break;
                
                case 'departamento':
                    if(strlen($_POST['nombre-departamento']) > 0 && strlen($_POST['facultad']) > 0){
                        $id = $_POST['id-departamento'];
                        $nombre = $_POST['nombre-departamento'];
                        $id_facultad = $_POST['facultad'];

                        $ddao = new Departamento_dao();
                        $ddao->store(new Departamento($id, $nombre, $id_facultad));
                        
                        unset($ddao);
                        header("HTTP/1.1 301 Moved Permanently"); 
                        header("Location: /gestion?gestionar=departamentos&message=2");
                    }
                break;
                
                case 'asignatura':
                    $gea = $_POST['id'];
                    $carrera = $_POST['carrera'];
                    $itinerario = (!isset($_POST['itinerario']) || $_POST['itinerario'] == 0) ? NULL : $_POST['itinerario'];
                    $nombre = $_POST['nombre-asignatura'];
                    $abreviatura = ($_POST['abreviatura'] != "") ? $_POST['abreviatura'] : NULL;
                    $curso = $_POST['curso'];
                    $dep1 = $_POST['departamento-1'];
                    $dep2 = ($_POST['departamento-2'] != "") ? $_POST['departamento-2'] : NULL;
                    $creditos = $_POST['creditos'];
                    $docentes = $_POST['docentes'];

                    $adao = new Asignatura_dao();
                    $adao->store(new Asignatura($gea, $carrera, $itinerario, $nombre, $abreviatura, $curso, $dep1, $dep2, $creditos, $docentes));

                    unset($adao);
                    header("HTTP/1.1 301 Moved Permanently"); 
                    header("Location: /gestion?gestionar=asignaturas&message=2");
                break;

                case 'clase':

                break;

                case 'docente':
                    $id = $_POST['id-docente'];
                    $nombre = $_POST['nombre-docente'];
                    $departamento = $_POST['departamento'];
                    $pass = ($_POST['password-docente'] == "¿Te crees hacker o qué?") ? NULL : hash("sha256", $_POST['user-docente'] . SALT . $_POST['password-docente']);

                    $dodao = new Docente_dao();
                    $dodao->store(new Docente($id, $nombre, $departamento, "", 0, "", $pass));

                    unset($dodao);
                    header("HTTP/1.1 301 Moved Permanently"); 
                    header("Location: /gestion?gestionar=docentes&message=2");
                break;
                
                default:
                    # code...
                    break;
            }
        }
        
        if($_POST['accion'] == "remove"){
            switch ($_POST['page']) {
                case 'facultad':
                    if(isset($_POST['facultad'])){
                        $fdao = new Facultad_dao();

                        foreach ($_POST['facultad'] as $id) {
                            $fdao->remove($id);
                        }

                        unset($fdao);
                        header("HTTP/1.1 301 Moved Permanently"); 
                        header("Location: /gestion?gestionar=facultades&message=3");
                    }
                break;
                
                case 'carrera':
                    if(isset($_POST['carrera'])){
                        $cdao = new Carrera_dao();

                        foreach ($_POST['carrera'] as $id) {
                            $cdao->remove($id);
                        }

                        unset($cdao);
                        header("HTTP/1.1 301 Moved Permanently"); 
                        header("Location: /gestion?gestionar=carreras&message=3");
                    }
                break;
                
                case 'itinerario':
                    if(isset($_POST['itinerario'])){
                        $idao = new Itinerario_dao();

                        foreach ($_POST['itinerario'] as $id) {
                            $idao->remove($id);
                        }

                        unset($idao);
                        header("HTTP/1.1 301 Moved Permanently"); 
                        header("Location: /gestion?gestionar=itinerarios&message=3");
                    }
                break;

                case 'departamento':
                    if(isset($_POST['departamento'])){
                        $ddao = new Departamento_dao();

                        foreach ($_POST['departamento'] as $id) {
                            $ddao->remove($id);
                        }

                        unset($ddao);
                        header("HTTP/1.1 301 Moved Permanently"); 
                        header("Location: /gestion?gestionar=departamentos&message=3");
                    }
                break;

                case 'asignatura':
                    if(isset($_POST['asignatura'])){
                        $adao = new Asignatura_dao();

                        foreach ($_POST['asignatura'] as $id) {
                            $adao->remove($id);
                        }

                        unset($adao);
                        header("HTTP/1.1 301 Moved Permanently"); 
                        header("Location: /gestion?gestionar=asignaturas&message=3");
                    }
                break;

                case 'clase':

                break;

                case 'docente':
                    if(isset($_POST['docente'])){
                        $dodao = new Docente_dao();

                        foreach ($_POST['docente'] as $id) {
                            $dodao->remove($id);
                    }

                    unset($dodao);
                    header("HTTP/1.1 301 Moved Permanently"); 
                    header("Location: /gestion?gestionar=docentes&message=3");
                }
                break;

                default:
                    # code...
                    break;
            }
        }
    }

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

    // Funciones para ver páginas
    function showLogin(){
        ?>
        <div class="main-content bg-dark">
            <div class="section px-2">
                <form id="form-inicial" action="/gestion" method="post">
                    <h1 class="font-weight-light text-center text-light">Chronos - Gestión</h1>
                    <div class="form-group">
                    <input type="text" class="form-control" name="usuario" id="usuario-gestion" placeholder="Usuario" autofocus>
                    </div>
                    <div class="form-group">
                    <input type="password" class="form-control"  name="password" id="usuario-gestion" placeholder="Contraseña">
                    </div>
                    <input id="login" class="btn btn-light w-100 my-1" type="submit" value="Iniciar Sesión">
                </form>
            </div>
        </div>
        <?php 
    }

    function show_inicio(){
        
        $fdao = new Facultad_dao();
        $cdao = new Carrera_dao();
        $idao = new Itinerario_dao();
        $ddao = new Departamento_dao();
        $adao = new Asignatura_dao();
        $cldao= new Clase_dao();
        $dodao = new Docente_dao();

        $count['facultades']    = $fdao->count();
        $count['carreras']      = $cdao->count();
        $count['itinerarios']   = $idao->count();
        $count['departamentos'] = $ddao->count();
        $count['asignaturas']   = $adao->count();
        $count['clases']        = $cldao->count();
        $count['docentes']      = $dodao->count();

        unset($fdao);
        unset($cdao);
        unset($idao);
        unset($ddao);
        unset($adao);
        unset($cldao);
        unset($dodao);
        ?>
            <div class="row justify-content-between">
                <div class="col-md m-3 p-2 border text-center">
                    <div class="dashboardbox">
                    <?php echo $count['facultades']; ?> facultades <br>
                    <a class="btn btn-primary my-2" href="?gestionar=facultades" role="button">Gestionar facultades</a>
                    </div>
                </div>
                <div class="col-md m-3 p-2 border text-center">
                    <div class="dashboardbox">
                    <?php echo $count['carreras']; ?> carreras <br>
                    <a class="btn btn-primary my-2" href="?gestionar=carreras" role="button">Gestionar carreras</a>
                    </div>
                </div>
                <div class="col-md m-3 p-2 border text-center">
                    <div class="dashboardbox">
                    <?php echo $count['itinerarios']; ?> itinerarios <br>
                    <a class="btn btn-primary my-2" href="?gestionar=itinerarios" role="button">Gestionar itinerarios</a>
                    </div>
                </div>
            </div>
            <div class="row justify-content-between">
                <div class="col-md m-3 p-2 border text-center">
                    <div class="dashboardbox">
                    <?php echo $count['departamentos']; ?> departamentos <br>
                    <a class="btn btn-primary my-2" href="?gestionar=departamentos" role="button">Gestionar departamentos</a>
                    </div>
                </div>
                <div class="col-md m-3 p-2 border text-center">
                    <div class="dashboardbox">
                    <?php echo $count['asignaturas']; ?> asignaturas <br>
                    <a class="btn btn-primary my-2" href="?gestionar=asignaturas" role="button">Gestionar asignaturas</a>
                    </div>
                </div>
                <div class="col-md m-3 p-2 border text-center">
                    <div class="dashboardbox">
                    <?php echo $count['clases']; ?> clases <br>
                    <a class="btn btn-primary my-2" href="?gestionar=clases" role="button">Gestionar clases</a>
                    </div>
                </div>
            </div>
            <div class="row justify-content-between">
                <div class="col-md m-3 p-2 text-center">
                </div>
                <div class="col-md m-3 p-2 border text-center">
                    <div class="dashboardbox">
                        <?php echo $count['docentes']; ?> docentes <br>
                        <a class="btn btn-primary my-2" href="?gestionar=docentes" role="button">Gestionar docentes</a>
                    </div>
                </div>
                <div class="col-md m-3 p-2 text-center">
                </div>
            </div>
        <?php
    }

    function show_facultades(){
        $fdao = new Facultad_dao();
        $listado = $fdao->getListado();

        unset($fdao);

        if(isset($_GET['message'])){
            ?> <div class="alert alert-success" role="alert"> <?php
            switch ($_GET['message']) {
                case 1:
                    echo "Facultad añadida";
                break;

                case 2:
                    echo "Facultad(es) eliminada(s)";
                break;
                
                case 3:
                    echo "Facultad(es) eliminada(s)";
                break;

                case 4:
                    echo "Ya existe una facultad con esos datos";
                break;

                default: break;
            }
            ?> </div> <?php
            unset($_GET['message']);
        }
        ?>

        <div class="row justify-content-between">
            <!-- formulario -->
            <div id="formulario" class="col-md-5 mr-md-3 mb-3 mb-md-0 p-2 border">
                <h4 id="accion-title">Añadir facultad</h4>
                <form action="?gestionar=facultades" method="post">
                    <div class="form-group">
                      <input type="text" class="form-control" name="nombre-facultad" id="nombre-facultad" placeholder="Nombre" required>
                    </div>
                    <div class="form-group">
                      <input type="text" class="form-control" name="campus-facultad" id="campus-facultad" placeholder="Campus" required>
                    </div>
                    <input type="hidden" name="page" value="facultad">
                    <input type="hidden" id="id-facultad" name="id-facultad" value="0">
                    <input type="hidden" id="accion-facultad" name="accion" value="add">
                    <button type="submit" id="submit-facultad" name="submit-facultad" class="btn btn-primary w-100">Añadir</button>
                </form>
            </div>
            <!-- /formulario -->
            <!-- listado -->
            <div id="listado" class="col-md p-2 border">
                <?php
                if(count($listado) == 0) {
                    echo "No hay facultades";
                }
                else{
                    ?>
                    <form action="?gestionar=facultades" method="post">
                        <fieldset>
                        <?php
                        foreach ($listado as $facultad) {
                        ?>
                        <div class="gestion-list-element p-2 mb-2 border">
                            <input class="align-middle" type="checkbox" name="facultad[]" value="<?php echo $facultad->getId(); ?>" id="facultad-<?php echo $facultad->getId(); ?>">
                            <label for="facultad-<?php echo $facultad->getId(); ?>"><?php echo $facultad->getNombre() . ' ('. $facultad->getCampus() .')'; ?></label>
                            <span class="editar-button" onclick="editar_facultad(<?php echo $facultad->getId(); ?>)">Editar</span>
                            <input type="hidden" name="page" value="facultad">
                            <input type="hidden" name="accion" value="remove">
                        </div>
                        <?php  
                        }
                        ?>
                        </fieldset>
                        <button type="button" class="btn btn-primary" onclick="borrar()">Borrar</button>
                    </form>
                <?php 
                } 
                ?>
            </div>
            <!-- /listado -->
        </div>
        <?php
    }

    function show_carreras(){
        $cdao = new Carrera_dao();
        $listado = $cdao->getListado();
        unset($cdao);

        $fdao = new Facultad_dao();
        $facultades = $fdao->getListado();
        

        if(isset($_GET['message'])){
            ?> <div class="alert alert-success" role="alert"> <?php
            switch ($_GET['message']) {
                case 1:
                    echo "Carrera añadida";
                break;

                case 2:
                    echo "Carrera modificada";
                break;
                
                case 3:
                    echo "Carrera(s) eliminada(s)";
                break;

                case 4:
                    echo "Ya existe una carrera con esos datos";
                break;

                default: break;
            }
            ?> </div> <?php
            unset($_GET['message']);
        }
        ?>

        <div class="row justify-content-between">
            <!-- formulario -->
            <div id="formulario" class="col-md-5 mr-md-3 mb-3 mb-md-0 p-2 border">
                <h4 id="accion-title">Añadir carrera</h4>
                <form id="form-carreras" action="?gestionar=carreras" method="post">
                    <label for="selector-facultad" class="my-1">Facultad</label>
                    <select id="selector-facultad" name="facultad" class="custom-select text-dark" required>
                        <option disabled="disabled" selected="selected" value="">Selecciona facultad</option>
                        <?php
                        foreach ($facultades as $facultad) {
                            echo '<option value="'.$facultad->getId().'">'.$facultad->getNombre().'</option>';
                        }
                        ?>
                    </select>
                    <label for="selector-facultad-dg" class="my-1">Facultad doble grado</label>
                    <select id="selector-facultad-dg" name="facultad-dg" class="custom-select text-dark" disabled>
                        <option value="none" selected>Selecciona facultad D.G.</option>
                    </select>
                    <div class="form-group my-1">
                        <label for="nombre-carrera" class="my-1">Nombre de la carrera</label>
                        <input type="text" class="form-control" name="nombre-carrera" id="nombre-carrera" placeholder="Nombre" required>
                    </div>
                    <input type="hidden" name="page" value="carrera">
                    <input type="hidden" id="id-carrera" name="id-carrera" value="0">
                    <input type="hidden" id="accion-carrera" name="accion" value="add">
                    <button type="submit" id="submit-carrera" name="submit-carrera" class="btn btn-primary w-100 mt-1">Añadir</button>
                </form>
            </div>
            <!-- /formulario -->
            <!-- listado -->
            <div id="listado" class="col-md p-2 border">
                <?php
                if(count($listado) == 0) {
                    echo "No hay carreras";
                }
                else{
                    ?>
                    <form action="?gestionar=carreras" method="post">
                        <fieldset>
                        <?php
                        foreach ($listado as $carrera) {
                            $f = $fdao->getById($carrera->getId_facultad());
                            $f = $f->getNombre();
                            $fdg = $fdao->getById($carrera->getId_facultad_dg());
                            $fdg = ($fdg == NULL) ? "" : ' - '.$fdg->getNombre();
                        ?>
                        <div class="gestion-list-element p-2 mb-2 border">
                            <input type="checkbox" name="carrera[]" value="<?php echo $carrera->getId(); ?>" id="carrera-<?php echo $carrera->getId(); ?>">
                            <label for="carrera-<?php echo $carrera->getId(); ?>"><?php echo $carrera->getNombre() . ' ('. $f . $fdg .')'; ?></label>
                            <span class="editar-button" onclick="editar_carrera(<?php echo $carrera->getId(); ?>)">Editar</span>
                            <input type="hidden" name="page" value="carrera">
                            <input type="hidden" name="accion" value="remove">
                        </div>
                        <?php  
                        }
                        ?>
                        </fieldset>
                        <button id="borrar-seleccion" type="button" class="btn btn-primary" onclick="borrar()">Borrar</button>
                    </form>
                <?php 
                } 
                ?>
            </div>
            <!-- /listado -->
        </div>
        <?php
        unset($fdao);
    }

    function show_itinerarios(){
        if(isset($_GET['message'])){
            ?> <div class="alert alert-success" role="alert"> <?php
            switch ($_GET['message']) {
                case 1:
                    echo "Itinerario añadido";
                break;

                case 2:
                    echo "Itinerario modificado";
                break;
                
                case 3:
                    echo "Itinerario(s) eliminado(s)";
                break;

                case 4:
                    echo "Ya existe un itinerario con esos datos";
                break;

                default: break;
            }
            ?> </div> <?php
            unset($_GET['message']);
        }
        $cdao = new Carrera_dao();
        $carreras = $cdao->getListado();

        $idao = new Itinerario_dao();
        $listado = $idao->getListado();

        ?>

        <div class="row justify-content-between">
            <!-- formulario -->
            <div id="formulario" class="col-md-5 mr-md-3 mb-3 mb-md-0 p-2 border">
                <h4 id="accion-title">Añadir itinerario</h4>
                <form action="?gestionar=itinerarios" method="post">
                    <label for="selector-carrera" class="my-1">Carrera</label>
                    <select id="selector-carrera" name="carrera" class="custom-select text-dark" required>
                        <option disabled="disabled" selected="selected" value="">Selecciona una carrera</option>
                        <?php
                        foreach ($carreras as $carrera) {
                            echo '<option value="'.$carrera->getId().'">'.$carrera->getNombre().'</option>';
                        }
                        ?>
                    </select>
                    <div class="form-group my-1">
                        <label for="nombre-itinerario" class="my-1">Nombre del itinerario</label>
                        <input type="text" class="form-control" name="nombre-itinerario" id="nombre-itinerario" placeholder="Nombre" required>
                    </div>
                    <input type="hidden" name="page" value="itinerario">
                    <input type="hidden" id="id-itinerario" name="id-itinerario" value="0">
                    <input type="hidden" id="accion-itinerario" name="accion" value="add">
                    <button type="submit" id="submit-itinerario" name="submit-itinerario" class="btn btn-primary w-100 mt-1">Añadir</button>
                </form>
            </div>
            <!-- /formulario -->
            <!-- listado -->
            <div id="listado" class="col-md p-2 border">
                <?php
                if(count($listado) == 0) {
                    echo "No hay itinerarios";
                }
                else{
                    ?>
                    <form action="?gestionar=itinerarios" method="post">
                        <fieldset>
                        <?php
                        foreach ($listado as $itinerario) {
                            $c = $cdao->getById($itinerario->getIdCarrera());
                            $c = $c->getNombre();
                            ?>
                            <div class="gestion-list-element p-2 mb-2 border">
                                <input type="checkbox" name="itinerario[]" value="<?php echo $itinerario->getId(); ?>" id="itinerario-<?php echo $itinerario->getId(); ?>">
                                <label for="itinerario-<?php echo $itinerario->getId(); ?>"><?php echo $itinerario->getNombre() . ' ('. $c .')'; ?></label>
                                <span class="editar-button" onclick="editar_itinerario(<?php echo $itinerario->getId(); ?>)">Editar</span>
                                <input type="hidden" name="page" value="itinerario">
                                <input type="hidden" name="accion" value="remove">
                            </div>
                            <?php  
                        }
                        ?>
                        </fieldset>
                        <button id="borrar-seleccion" type="button" class="btn btn-primary" onclick="borrar()">Borrar</button>
                    </form>
                <?php 
                } 
                ?>
            </div>
            <!-- /listado -->
        </div>
        <?php
    }

    function show_departamentos(){
        if(isset($_GET['message'])){
            ?> <div class="alert alert-success" role="alert"> <?php
            switch ($_GET['message']) {
                case 1:
                    echo "Departamento añadido";
                break;

                case 2:
                    echo "Departamento modificado";
                break;
                
                case 3:
                    echo "Departamento(s) eliminado(s)";
                break;

                case 4:
                    echo "Ya existe un departamento con esos datos";
                break;

                default: break;
            }
            ?> </div> <?php
            unset($_GET['message']);
        }
        $fdao = new Facultad_dao();
        $facultades = $fdao->getListado();

        $ddao = new Departamento_dao();
        $listado = $ddao->getListado();

        ?>

        <div class="row justify-content-between">
            <!-- formulario -->
            <div id="formulario" class="col-md-5 mr-md-3 mb-3 mb-md-0 p-2 border">
                <h4 id="accion-title">Añadir Departamento</h4>
                <form action="?gestionar=departamentos" method="post">
                    <label for="selector-facultad" class="my-1">Facultad</label>
                    <select id="selector-facultad" name="facultad" class="custom-select text-dark" required>
                        <option disabled="disabled" selected="selected" value="">Selecciona una facultad</option>
                        <?php
                        foreach ($facultades as $facultad) {
                            echo '<option value="'.$facultad->getId().'">'.$facultad->getNombre().'</option>';
                        }
                        ?>
                    </select>
                    <div class="form-group my-1">
                        <label for="nombre-departamento" class="my-1">Nombre del departamento</label>
                        <input type="text" class="form-control" name="nombre-departamento" id="nombre-departamento" placeholder="Nombre" required>
                    </div>
                    <input type="hidden" name="page" value="departamento">
                    <input type="hidden" id="id-departamento" name="id-departamento" value="0">
                    <input type="hidden" id="accion-departamento" name="accion" value="add">
                    <button type="submit" id="submit-departamento" name="submit-departamento" class="btn btn-primary w-100 mt-1">Añadir</button>
                </form>
            </div>
            <!-- /formulario -->
            <!-- listado -->
            <div id="listado" class="col-md p-2 border">
                <?php
                if(count($listado) == 0) {
                    echo "No hay departamentos";
                }
                else{
                    ?>
                    <form action="?gestionar=departamentos" method="post">
                        <fieldset>
                        <?php
                        foreach ($listado as $departamento) {
                            $f = $fdao->getById($departamento->getId_facultad());
                            $f = $f->getNombre();
                            ?>
                            <div class="gestion-list-element p-2 mb-2 border">
                                <input class="align-middle" type="checkbox" name="departamento[]" value="<?php echo $departamento->getId(); ?>" id="departamento-<?php echo $departamento->getId(); ?>">
                                <label for="departamento-<?php echo $departamento->getId(); ?>"><?php echo $departamento->getNombre() . ' ('. $f .')'; ?></label>
                                <span class="editar-button" onclick="editar_departamento(<?php echo $departamento->getId(); ?>)">Editar</span>
                                <input type="hidden" name="page" value="departamento">
                                <input type="hidden" name="accion" value="remove">
                            </div>
                            <?php  
                        }
                        ?>
                        </fieldset>
                        <button id="borrar-seleccion" type="button" class="btn btn-primary" onclick="borrar()">Borrar</button>
                    </form>
                <?php 
                } 
                ?>
            </div>
            <!-- /listado -->
        </div>
        <?php
    }


    function show_asignaturas(){
        if(isset($_GET['message'])){
            ?> <div class="alert alert-success" role="alert"> <?php
            switch ($_GET['message']) {
                case 1:
                    echo "Asignatura añadida";
                break;

                case 2:
                    echo "Asignatura modificada";
                break;
                
                case 3:
                    echo "Asignatura(s) eliminada(s)";
                break;

                case 4:
                    echo "Ya existe una asignatura con ese código GEA";
                break;

                default: break;
            }
            ?> </div> <?php
            unset($_GET['message']);
        }

        $cdao = new Carrera_dao();
        $carreras = $cdao->getListado();

        $idao = new Itinerario_dao();

        $ddao = new Departamento_dao();
        $departamentos = $ddao->getListado();

        $adao = new Asignatura_dao();
        $asignaturas = $adao->getListado();
        ?>
        
        <div class="row justify-content-between">
            <!-- formulario -->
            <div id="formulario" class="col-md-5 mr-md-3 mb-3 mb-md-0 p-2 border">
                <h4 id="accion-title">Añadir asignatura</h4>
                <form id="form-asignaturas" action="?gestionar=asignaturas" method="post">
                    <fieldset class="pr-2">
                        <!-- Selector carrera -->
                            <div id="container-selector-carrera">
                                <label for="selector-carrera" class="my-1">Carrera*</label>
                                <select id="selector-carrera" name="carrera" class="custom-select text-dark" required>
                                    <option disabled="disabled" selected="selected" value="">Selecciona una carrera</option>
                                    <?php
                                    foreach ($carreras as $carrera) {
                                        echo '<option value="'.$carrera->getId().'">'.$carrera->getNombre().'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        <!-- /Selector carrera -->

                        <!-- Selector itinerario -->
                            <div id="container-selector-itinerario">
                                <label for="selector-itinerario" class="my-1">Itinerario*</label>
                                <select id="selector-itinerario" name="itinerario" class="custom-select text-dark" disabled required>
                                    <option selected="selected" value="0">Común</option>
                                </select>
                            </div>
                        <!-- /Selector itinerario -->

                        <!-- Nombre Asignatura -->
                            <div class="form-group my-1">
                                <label for="nombre-asignatura" class="my-1">Nombre de la asignatura*</label>
                                <input type="text" class="form-control" name="nombre-asignatura" id="nombre-asignatura" placeholder="Nombre de la asignatura" required>
                            </div>
                        <!-- /Nombre Asignatura -->
                        
                        <div class="row align-items-end">
                            <!-- Abreviatura Asignatura -->
                                <div class="form-group col-md my-1">
                                    <label for="abreviatura" class="my-1">Abreviatura de la asignatura</label>
                                    <input type="text" class="form-control" name="abreviatura" id="abreviatura" placeholder="Abreviatura">
                                </div>
                            <!-- /Abreviatura Asignatura -->

                            <!-- Selector curso -->
                                <div class="form-group col-md my-1" id="container-selector-curso">
                                    <label for="selector-curso" class="my-1">Curso*</label>
                                    <select id="selector-curso" name="curso" class="custom-select text-dark" required>
                                        <option selected="selected" value="0">Optativa</option>
                                        <option value="1">1º</option>
                                        <option value="2">2º</option>
                                        <option value="3">3º</option>
                                        <option value="4">4º</option>
                                        <option value="5">5º</option>
                                        <option value="6">6º</option>
                                    </select>
                                </div>
                            <!-- /Selector curso -->
                        </div>

                        <!-- Selector Departamento 1 -->
                            <div id="container-selector-departamento-1">
                                <label for="selector-departamento-1" class="my-1">Departamento*</label>
                                <select id="selector-departamento-1" name="departamento-1" class="custom-select text-dark" required>
                                    <option selected="selected" value="">Selecciona un departamento</option>
                                    <?php
                                    foreach ($departamentos as $departamento) {
                                        echo '<option value="'.$departamento->getId().'">'.$departamento->getNombre().'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        <!-- /Selector Departamento 1 -->

                        <!-- Selector Departamento 2 -->
                            <div id="container-selector-departamento-2">
                                <label for="selector-departamento-2" class="my-1">Departamento 2</label>
                                <select id="selector-departamento-2" name="departamento-2" class="custom-select text-dark" disabled>
                                    <option selected="selected" value="">Selecciona un departamento</option>
                                </select>
                            </div>
                        <!-- /Selector Departamento 2 -->

                        <div class="row align-items-end">
                            <!-- Cod. GEA -->
                                <div class="form-group col-md my-1">
                                    <label for="gea" class="my-1" title="Código GEA de la asignatura">Cód. GEA*</label>
                                    <input type="number" min="0" max="999999999" class="form-control" name="gea" id="gea" placeholder="803270" required>
                                </div>
                            <!-- /Cod. GEA -->

                            <!-- Creditos -->
                                <div class="form-group col-md my-1">
                                    <label for="creditos" class="my-1">Créditos</label>
                                    <input type="number" min="0" step=".5" class="form-control" name="creditos" id="creditos" placeholder="6" required>
                                </div>
                            <!-- /Creditos -->

                            <!-- Docentes -->
                                <div class="form-group col-md my-1">
                                    <label for="docentes" class="my-1" title="¿Cuántos docentes impartirán la asignatura?">Nº de docentes</label>
                                    <input type="number" min="0" class="form-control" name="docentes" id="docentes" placeholder="6" required>
                                </div>
                            <!-- /Docentes -->
                        </div>
                        <input type="hidden" name="page" value="asignatura">
                        <input type="hidden" id="id-asignatura" name="id" value="0">
                        <input type="hidden" id="accion-asignatura" name="accion" value="add">
                    </fieldset>
                    <button type="submit" id="submit-asignatura" name="submit-asignatura" class="btn btn-primary w-100 mt-2">Añadir</button>
                </form>
            </div>
            <!-- /formulario -->
            <!-- listado -->
            <div id="listado" class="col-md p-2 border">
                <?php
                if(count($asignaturas) == 0) {
                    echo "No hay asignaturas";
                }
                else{
                    ?>
                    <div id="container-filtro-asignaturas" class="row align-items-center mb-2">
                        <div class="col-auto">
                            <label for="filtro-asignaturas" class="my-1">Filtrar por carrera</label>
                        </div>
                        <div class="col">
                        <select id="filtro-asignaturas" name="filtro-carrera" class="custom-select text-dark col" required>
                            <option selected="selected" value="">Todas</option>
                            <?php
                            foreach ($carreras as $carrera) {
                                echo '<option value="'.$carrera->getId().'">'.$carrera->getNombre().'</option>';
                            }
                            ?>
                        </select>
                        </div>
                    </div>
                    <form action="?gestionar=asignaturas" method="post">
                        <fieldset>
                        <?php
                        foreach ($asignaturas as $asignatura) {
                            $c = $cdao->getById($asignatura->getId_carrera());
                            $c = $c->getNombre();

                            if($asignatura->getItinerario() != NULL){
                                $i = $idao->getById($asignatura->getItinerario());
                                $i = $i->getNombre();
                            }
                            else{
                                $i = "Común";
                            }
                            ?>
                            <div class="gestion-list-element p-2 mb-2 border">
                                <input type="checkbox" name="asignatura[]" value="<?php echo $asignatura->getId(); ?>" id="asignatura-<?php echo $asignatura->getId(); ?>">
                                <label for="asignatura-<?php echo $asignatura->getId(); ?>">[<?php echo $asignatura->getId(); ?>] <?php echo $asignatura->getNombre() . ' ('. $c .')  ('. $i .')'; ?></label>
                                <span class="editar-button" onclick="editar_asignatura(<?php echo $asignatura->getId(); ?>)">Editar</span>
                            </div>
                            <?php  
                        }
                        ?>
                            <input type="hidden" name="page" value="asignatura">
                            <input type="hidden" name="accion" value="remove">
                        </fieldset>
                        <button id="borrar-seleccion" type="button" class="btn btn-primary" onclick="borrar()">Borrar</button>
                    </form>
                <?php 
                } 
                ?>
            </div>
            <!-- /listado -->
        </div>
        <?php
    }

    //TODO: Falta por hacer
    function show_clases(){
        //Filtrado para añadir
        $fdao = new Facultad_dao();
        $adao = new Asignatura_dao();

        // Listado de la derecha
        $cldao = new Clase_dao();

        $facultades = $fdao->getListado();
        $asignaturas = $adao->getListado();

        $clases = $cldao->getListado();
        

        if(isset($_GET['message'])){
            ?> <div class="alert alert-success" role="alert"> <?php
            switch ($_GET['message']) {
                case 1:
                    echo "Clase añadida";
                break;

                case 2:
                    echo "Clase(s) eliminada(s)";
                break;
                
                case 3:
                    echo "Clase(s) eliminada(s)";
                break;

                case 4:
                    echo "Ya existe una clase con esos datos";
                break;

                default: break;
            }
            ?> </div> <?php
            unset($_GET['message']);
        }
        ?>

        <div class="row justify-content-between">
            <!-- formulario -->
                <div id="formulario" class="col-md-5 mr-md-3 mb-3 mb-md-0 p-2 border">
                    <h4 id="accion-title">Añadir Clases</h4>
                    <form id="form-add-clase" action="?gestionar=clases" method="post">
                        <fieldset>
                            <!-- Selector Facultad -->
                                <div id="container-selector-facultad">
                                    <label for="selector-facultad" class="my-1">Facultad*</label>
                                    <select id="selector-facultad" name="facultad" class="custom-select text-dark" required>
                                        <option disabled="disabled" selected="selected" value="">Selecciona una facultad</option>
                                        <?php
                                            foreach ($facultades as $facultad) {
                                                echo '<option value="'.$facultad->getId().'">'.$facultad->getNombre().'</option>';
                                            }
                                        ?>
                                    </select>
                                </div>
                            <!-- /Selector Facultad -->
                            <!-- Selector Carrera -->
                                <div id="container-selector-carrera">
                                    <label for="selector-carrera" class="my-1">Carrera*</label>
                                    <select id="selector-carrera" name="carrera" class="custom-select text-dark" disabled required>
                                        <option disabled="disabled" selected="selected" value="">Selecciona una carrera</option>
                                    </select>
                                </div>
                            <!-- /Selector Carrera -->
                            <!-- Selector Itinerario -->
                                <div id="container-selector-itinerario">
                                    <label for="selector-itinerario" class="my-1">Itinerario</label>
                                    <select id="selector-itinerario" name="itinerario" class="custom-select text-dark" disabled>
                                        <option disabled="disabled" selected="selected" value="">Selecciona una itinerario</option>
                                    </select>
                                </div>
                            <!-- Selector Itinerario -->
                            <!-- Selector Asignatura -->
                                <div id="container-selector-asignatura">
                                    <label for="selector-asignatura" class="my-1">Asignatura*</label>
                                    <select id="selector-asignatura" name="asignatura" class="custom-select text-dark" required>
                                        <option disabled="disabled" selected="selected" value="">Selecciona una asignatura</option>
                                        <?php
                                            foreach ($asignaturas as $a) {
                                                echo '<option value="'.$a->getId().'">'.$a->getNombre().' ('.$a->getId().')</option>';
                                            }
                                        ?>
                                    </select>
                                </div>
                            <!-- Selector Asignatura -->
                            
                            <label for="btn-add-clase" id="label-btn-add-clase" class="mt-2">Clases*</label>
                            <div id="class-list-container" class="p-2 pt-0 mt-2 border">
                                <div id="list-elements">
                                    <div class="list-clase mb-2 text-center">
                                        Aun no hay clases añadidas.<br>▼ Pulsa el botón para añadir ▼
                                    </div>
                                </div>
                                <button type="button" id="btn-add-clase" class="btn btn-info w-100" data-toggle="modal" data-target="#add-clase-modal" disabled>Añadir clase</button>
                            </div>
                            

                            <input type="hidden" name="page" value="facultad">
                            <input type="hidden" id="id-facultad" name="id-facultad" value="0">
                            <input type="hidden" id="accion-facultad" name="accion" value="add">
                        </fieldset>
                        <button type="submit" id="submit-facultad" name="submit-facultad" class="btn btn-primary mt-4 w-100" disabled>Terminar</button>
                    </form>
                </div>
            <!-- /formulario -->
            <!-- listado -->
                <div id="listado" class="col-md p-2 border">
                    <?php
                    if(count($clases) == 0) {
                        echo "No hay clases";
                    }
                    else{
                        ?>
                        <form action="?gestionar=clases" method="post">
                            <fieldset>
                            <?php
                            foreach ($facultades as $facultad) {
                                ?>
                                <div class="gestion-list-element p-2 mb-2 border">
                                    <input class="align-middle" type="checkbox" name="facultad[]" value="<?php echo $facultad->getId(); ?>" id="facultad-<?php echo $facultad->getId(); ?>">
                                    <label for="facultad-<?php echo $facultad->getId(); ?>"><?php echo $facultad->getNombre() . ' ('. $facultad->getCampus() .')'; ?></label>
                                    <span class="editar-button" onclick="editar_facultad(<?php echo $facultad->getId(); ?>)">Editar</span>
                                    <input type="hidden" name="page" value="facultad">
                                    <input type="hidden" name="accion" value="remove">
                                </div>
                                <?php  
                            }
                            ?>
                            </fieldset>
                            <button type="button" class="btn btn-primary" onclick="borrar()">Borrar</button>
                        </form>
                    <?php 
                    } 
                    ?>
                </div>
            <!-- /listado -->
        </div>

        <!-- Modal -->
            <div class="modal fade" id="add-clase-modal" tabindex="-1" role="dialog" aria-labelledby="add-clase-modal-title" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-dark" id="add-clase-modal-title">Añadir clase</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Volver">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body text-dark">
                            <div class="dia-add-clase odd py-3">
                                <span>Lunes</span>
                                <?php print_dia("lunes"); ?>
                                <div class="clases-added mt-3 pt-3 border-top border-dark" id="clases-added-lunes"></div>
                            </div>
                            <div class="dia-add-clase even py-3">
                                <span>Martes</span>
                                <?php print_dia("martes"); ?>
                                <div class="clases-added mt-3 pt-3 border-top border-dark" id="clases-added-martes"></div>
                            </div>
                            <div class="dia-add-clase odd py-3">
                                <span>Miércoles</span>
                                <?php print_dia("miercoles"); ?>
                                <div class="clases-added mt-3 pt-3 border-top border-dark" id="clases-added-miercoles"></div>
                            </div>
                            <div class="dia-add-clase even py-3">
                                <span>Jueves</span>
                                <?php print_dia("jueves"); ?>
                                <div class="clases-added mt-3 pt-3 border-top border-dark" id="clases-added-jueves"></div>
                            </div>
                            <div class="dia-add-clase odd py-3">
                                <span>Viernes</span>
                                <?php print_dia("viernes"); ?>
                                <div class="clases-added mt-3 pt-3 border-top border-dark" id="clases-added-viernes"></div>
                            </div>
                            <div class="dia-add-clase even py-3">
                                <span>Sábado</span>
                                <?php print_dia("sabado"); ?>
                                <div class="clases-added mt-3 pt-3 border-top border-dark" id="clases-added-sabado"></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="commit()">Confirmar</button>
                        </div>
                    </div>
                </div>
            </div>
        <!-- Modal -->
        <?php
        unset($fdao);
    }

    function print_dia($dia){
        ?>
                                <select name="horas[]" id="hora-inicio-<?= $dia ?>" class="form-control mt-2" required>
                                    <option value="" selected disabled>Hora Inicio</option>
                                    <option value="0">08:00</option>
                                    <option value="1">08:30</option>
                                    <option value="2">09:00</option>
                                    <option value="3">09:30</option>
                                    <option value="4">10:00</option>
                                    <option value="5">10:30</option>
                                    <option value="6">11:00</option>
                                    <option value="7">11:30</option>
                                    <option value="8">12:00</option>
                                    <option value="9">12:30</option>
                                    <option value="10">13:00</option>
                                    <option value="11">13:30</option>
                                    <option value="12">14:00</option>
                                    <option value="13">14:30</option>
                                    <option value="14">15:00</option>
                                    <option value="15">15:30</option>
                                    <option value="16">16:00</option>
                                    <option value="17">16:30</option>
                                    <option value="18">17:00</option>
                                    <option value="19">17:30</option>
                                    <option value="20">18:00</option>
                                    <option value="21">18:30</option>
                                    <option value="22">19:00</option>
                                    <option value="23">19:30</option>
                                </select>
                                <select name="duración" id="duracion-<?= $dia ?>"class="form-control mt-2" required>
                                    <option value="" selected disabled>Duración</option>
                                    <option value="1">1 hora</option>
                                    <option value="2">2 horas</option>
                                    <option value="3">3 horas</option>
                                    <option value="4">4 horas</option>
                                </select>
                                <button type="button" class="btn btn-primary mt-2" onclick="addClase('<?= $dia ?>')">Añadir</button>
        <?php
    }


    function show_docentes(){
        if(isset($_GET['message'])){
            ?> <div class="alert alert-success" role="alert"> <?php
            switch ($_GET['message']) {
                case 1:
                    echo "Docente añadido";
                break;

                case 2:
                    echo "Docente modificado";
                break;
                
                case 3:
                    echo "Docente(s) eliminado(s)";
                break;

                case 4:
                    echo "Ya existe un docente con esos datos";
                break;

                default: break;
            }
            ?> </div> <?php
            unset($_GET['message']);
        }

        $fdao = new Facultad_dao();
        $facultades = $fdao->getListado();

        $ddao = new Departamento_dao();
        $departamentos = $ddao->getListado();

        $dodao = new Docente_dao();
        $docentes = $dodao->getListadoPublico();

        ?>

        <div class="row justify-content-between">
            <!-- formulario -->
            <div id="formulario" class="col-md-5 mr-md-3 mb-3 mb-md-0 p-2 border">
                <h4 id="accion-title">Añadir docente</h4>
                <form action="?gestionar=docentes" method="post">
                    <!-- Selector Facultad -->
                        <label for="selector-facultad-docente" class="my-1">Facultad</label>
                        <select id="selector-facultad-docente" name="facultad" class="custom-select text-dark" required>
                            <option disabled="disabled" selected="selected" value="">Selecciona una facultad</option>
                            <?php
                            foreach ($facultades as $facultad) {
                                echo '<option value="'.$facultad->getId().'">'.$facultad->getNombre().'</option>';
                            }
                            ?>
                        </select>
                    <!-- /Selector Facultad -->
                    <!-- Selector Departamento -->
                        <label for="selector-departamento-docente" class="my-1">Departamento</label>
                        <select id="selector-departamento-docente" name="departamento" class="custom-select text-dark" required>
                            <option disabled="disabled" selected="selected" value="">Selecciona un departamento</option>
                        </select>
                    <!-- /Selector Departamento -->   
                    <!-- Nombre Docente -->
                        <div class="form-group my-1">
                            <label for="nombre-docente" class="my-1">Nombre del docente</label>
                            <input type="text" class="form-control" name="nombre-docente" id="nombre-docente" placeholder="Nombre" required>
                        </div>
                    <!-- /Nombre Docente -->

                    <!-- Usuario Docente -->
                        <div class="form-group my-1">
                            <label for="usuario-docente" class="my-1">Usuario</label>
                            <input type="text" class="form-control" name="usuario-docente" id="usuario-docente" placeholder="Usuario" autocomplete="nope" required>
                        </div>
                    <!-- /Usuario Docente -->
                    <!-- Password Docente -->
                        <div class="form-group my-1">
                            <label for="password-docente" class="my-1">Contraseña</label>
                            <input type="password" class="form-control" name="password-docente" id="password-docente" placeholder="Contraseña" autocomplete="nope" required>
                        </div>
                    <!-- /Password Docente -->

                    <input type="hidden" name="page" value="docente">
                    <input type="hidden" id="id-docente" name="id-docente" value="0">
                    <input type="hidden" id="user-docente" name="user-docente" value="0">
                    <input type="hidden" id="accion-docente" name="accion" value="add">
                    <button type="submit" id="submit-docente" name="submit-docente" class="btn btn-primary w-100 mt-1">Añadir</button>
                </form>
            </div>
            <!-- /formulario -->
            <!-- listado -->
            <div id="listado" class="col-md p-2 border">
                <?php
                if(count($docentes) == 0) {
                    echo "No hay docentes";
                }
                else{
                    ?>
                    <form action="?gestionar=docentes" method="post">
                        <fieldset>
                        <?php
                        foreach ($docentes as $docente) {
                            $f = $ddao->getById($docente['departamento']);
                            $f = $fdao->getById($f->getId_facultad());
                            $f = $f->getNombre();
                            ?>
                            <div class="gestion-list-element p-2 mb-2 border">
                                <input type="checkbox" name="docente[]" value="<?php echo $docente['id']; ?>" id="docente-<?php echo $docente['id']; ?>">
                                <label for="docente-<?php echo $docente['id']; ?>"><?php echo $docente['nombre'] . ' ('. $f .')'; ?></label>
                                <span class="editar-button" onclick="editar_docente(<?php echo $docente['id']; ?>)">Editar</span>
                                <input type="hidden" name="page" value="docente">
                                <input type="hidden" name="accion" value="remove">
                            </div>
                            <?php  
                        }
                        ?>
                        </fieldset>
                        <button id="borrar-seleccion" type="button" class="btn btn-primary" onclick="borrar()">Borrar</button>
                    </form>
                <?php 
                } 
                ?>
            </div>
            <!-- /listado -->
        </div>
        <?php
    }

    //TODO: Falta por hacer
    function show_importar(){
        ?>
        
        <?php
    }

    function change_password(){
        if(isset($_POST['cambiar-password'])){
            if($_POST['new-pass-1'] === $_POST['new-pass-2']){
                $gdao = new Gestor_dao();

                $id = $_SESSION['gestor-id'];
                $pass = hash("sha256", $_SESSION['gestor-usuario'] . SALT . $_POST['new-pass-1']);
            }
        }
        ?>
        <div class="row justify-content-center text-light">
            <div class="col-md-6">
                <h3>Cambio de contraseña</h3>
                <form class="mt-3" action="?gestionar=password" method="post">
                    <div class="form-group">
                    <label for="old-pass">Contraseña actual</label>
                    <input type="password" class="form-control" name="old-pass" id="old-pass" placeholder="Contraseña actual" required>
                    </div>
                    <div class="form-group">
                    <label for="new-pass-1">Nueva contraseña</label>
                    <input type="password" class="form-control" name="new-pass-1" id="new-pass-1" placeholder="Nueva contraseña" required>
                    </div>
                    <div class="form-group">
                    <label for="new-pass-2">Repite la nueva contraseña</label>
                    <input type="password" class="form-control" name="new-pass-2" id="new-pass-2" placeholder="Repite la nueva contraseña" required>
                    </div>
                    <button type="submit" name="cambiar-password" class="btn btn-primary">Cambiar</button>
                </form>
            </div>
        </div>
        <?php
    }

    function showDashboard(){
        ?>
        <div class="main-content text-light">
            <nav class="navbar navbar-expand-md navbar-dark border-bottom">
                <a class="navbar-brand" href="#">Chronos</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav w-100">
                        <li class="nav-item <?php active('/'); ?>">
                            <a class="nav-link" href="/gestion">Inicio <?php sr_only('/'); ?></a>
                        </li>
                        <li class="nav-item <?php active('facultades'); ?>">
                            <a class="nav-link" href="?gestionar=facultades">Facultades <?php sr_only('facultades'); ?></a>
                        </li>
                        <li class="nav-item <?php active('carreras'); ?>">
                            <a class="nav-link" href="?gestionar=carreras">Carreras <?php sr_only('carreras'); ?></a>
                        </li>
                        <li class="nav-item <?php active('departamentos'); ?>">
                            <a class="nav-link" href="?gestionar=departamentos">Departamentos <?php sr_only('departamentos'); ?></a>
                        </li>
                        <li class="nav-item <?php active('itinerarios'); ?>">
                            <a class="nav-link" href="?gestionar=itinerarios">Itinerarios <?php sr_only('itinerarios'); ?></a>
                        </li>
                        <li class="nav-item <?php active('asignaturas'); ?>">
                            <a class="nav-link" href="?gestionar=asignaturas">Asignaturas <?php sr_only('asignaturas'); ?></a>
                        </li>
                        <li class="nav-item <?php active('clases'); ?>">
                            <a class="nav-link" href="?gestionar=clases">Clases <?php sr_only('clases'); ?></a>
                        </li>
                        <li class="nav-item <?php active('docentes'); ?>">
                            <a class="nav-link" href="?gestionar=docentes">Docentes <?php sr_only('docentes'); ?></a>
                        </li>
                        <li class="nav-item mr-auto <?php active('importar'); ?>">
                            <a class="nav-link" href="?gestionar=importar">Importar <?php sr_only('importar'); ?></a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            ¡Hola, <?php echo $_SESSION['gestor-usuario']; ?>!
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="?gestionar=password">Cambiar contraseña</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="/logout">Cerrar sesión</a>
                            </div>
                        </li>
                        
                    </ul>
                </div>
            </nav>
            <div class="container pt-3">
                <?php

                $cur = (isset($_GET['gestionar'])) ? $_GET['gestionar'] : "/";

                switch ($cur) {
                    case 'facultades':
                        show_facultades();
                    break;

                    case 'carreras':
                        show_carreras();
                    break;

                    case 'itinerarios':
                        show_itinerarios();
                    break;

                    case 'departamentos':
                        show_departamentos();
                    break;

                    case 'asignaturas':
                        show_asignaturas();
                    break;

                    case 'clases':
                        show_clases();
                    break;

                    case 'docentes':
                        show_docentes();
                    break;

                    case 'importar':
                        show_importar();
                    break;

                    case 'password':
                        change_password();
                    break;

                    default:
                        show_inicio();
                    break;
                }
                ?>
            </div>
        </div>
        <?php 
    }

    function horarioLoad(){
        ?>
                <div id="horario-load-container">
                    <div class="col-12">
                    <form action="" method="post" id="horario-load-form" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="horario-load-input">Seleccione el fichero o arrástrelo sobre el botón. (.csv)</label>
                            <input type="file" name="file" class="form-control-file" id="horario-load-input" accept=".csv">
                        </div>
                        <button type="submit" name="submit" class="btn btn-secondary mb-2">Subir fichero</button>
                    </form>
                    </div>
                </div>
        <?php
    }

    // Contenido de la web
    get_head();

    if(isset($_POST['usuario']) && isset($_POST['password'])){
        $gdao = new Gestor_dao();

        $user = $_POST['usuario'];
        $pass = hash("sha256", $user . SALT . $_POST['password']);

        if($gdao->login($user, $pass)){
            showDashboard();
        }
        else{
            showLogin();
        }
    }
    else if(!isset($_SESSION['gestor-id'])){
        showLogin();
    }
    else{
        if(isset($_POST['submit'])){
            if(isset($_FILES['file'])){
                var_dump($_FILES);
                $fichero = fopen($_FILES['file']['tmp_name'], 'r');
                while (!feof($fichero) ) {
                    $lineas[] = fgetcsv($fichero, 400, $delimiter=";");
                }
                fclose($fichero);
                var_dump($lineas);
            }
        }
        else
            showDashboard();
    }

    get_scriptsAndFooter(); 
    
    die();
?>


<div class="popover bg-dark text-light" role="tooltip"><div class="arrow"></div><h3 class="popover-header"></h3><div class="popover-body"></div></div>