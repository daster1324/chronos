<?php
    session_set_cookie_params((3600*24*30), "/", $_SERVER["SERVER_NAME"], 0, true);
    session_start();

    require('common.php');
    require('core/includer.php');

    // Se capturan los POSTs (salvo el de login)
    if(isset($_POST['accion']) && !empty($_POST['accion'])){ 
        var_dump($_POST); 
    
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
                            <input type="hidden" id="accion-facultad-listado" name="accion" value="remove">
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
                            <input type="hidden" id="accion-carrera-listado" name="accion" value="remove">
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
                <h4 id="accion-title">Añadir Itinerario</h4>
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
                                <input type="hidden" id="accion-itinerario-listado" name="accion" value="remove">
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
                                <input type="hidden" id="accion-departamento-listado" name="accion" value="remove">
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
                    echo "Ya existe una asignatura con esos datos";
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

        // Si dos líneas están seguidas, es porque hay dependencia

        //x Carrera      -> Dropdown (obligatorio)
        //x Itinerario   -> Dropdown (opcional) | Puede no haber itinerarios (itinerario único)

        //x Nombre       -> Texto    (obligatorio)

        //x Abreviatura  -> Texto    (opcional, pero muy recomendable)

        //x Curso        -> Dropdown (obligatorio)

        // Departamento -> Dropdown (obligatorio)
        // Departamento2-> Dropdown (opcional) | Ofrecerá todas las posiblidades salvo la seleccionada arriba

        // Créditos     -> Número   (obligatorio)

        // Docentes     -> Número   (opcional) | Habrá que establecer un valor antes del reparto docente
        
        ?>
        
        <div class="row justify-content-between">
            <!-- formulario -->
            <div id="formulario" class="col-md-5 mr-md-3 mb-3 mb-md-0 p-2 border">
                <h4 id="accion-title">Añadir Asignatura</h4>
                <form id="form-asignaturas" action="?gestionar=asignaturas" method="post">
                    <!-- Selector carrera -->
                    <div id="container-selector-carrera">
                        <label for="selector-carrera" class="my-1">Carrera</label>
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
                        <label for="selector-itinerario" class="my-1">Itinerario</label>
                        <select id="selector-itinerario" name="itinerario" class="custom-select text-dark" disabled required>
                            <option selected="selected" value="0">Común</option>
                        </select>
                    </div>
                    <!-- /Selector itinerario -->
                    <!-- Nombre Asignatura -->
                    <div class="form-group my-1">
                        <label for="nombre-asignatura" class="my-1">Nombre de la asignatura</label>
                        <input type="text" class="form-control" name="nombre-asignatura" id="nombre-asignatura" placeholder="Nombre de la asignatura" required>
                    </div>
                    <!-- /Nombre Asignatura -->
                    <div class="row">
                        <!-- Abreviatura Asignatura -->
                        <div class="form-group col-md my-1">
                            <label for="abreviatura" class="my-1">Abreviatura de la asignatura</label>
                            <input type="text" class="form-control" name="abreviatura" id="abreviatura" placeholder="Abreviatura">
                        </div>
                        <!-- /Abreviatura Asignatura -->
                        <!-- Selector curso -->
                        <div class="form-group col-md my-1" id="container-selector-curso">
                            <label for="selector-curso" class="my-1">Curso</label>
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

                    <input type="hidden" name="page" value="asignatura">
                    <input type="hidden" id="id-asignatura" name="id-asignatura" value="0">
                    <input type="hidden" id="accion-asignatura" name="accion" value="add">
                    <button type="submit" id="submit-asignatura" name="submit-asignatura" class="btn btn-primary w-100 mt-2">Añadir</button>
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
                                <input type="hidden" id="accion-itinerario-listado" name="accion" value="remove">
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
    function show_clases(){
        ?>
        
        <?php
    }

    //TODO: Falta por hacer
    function show_docentes(){
        ?>
        
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
                $pass = hash("sha256", $_SESSION['gestor-usuario'] + SALT + $_POST['new-pass-1']);
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
        $pass = hash("sha256", $user + SALT + $_POST['password']);

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
