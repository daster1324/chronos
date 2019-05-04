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
                        
                        header("HTTP/1.1 301 Moved Permanently"); 
                        header("Location: /gestion?gestionar=facultades&message=".$mensaje);
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
                        $_SESSION['message'] = 1;
                        header("HTTP/1.1 301 Moved Permanently"); 
                        header("Location: /gestion?gestionar=facultades&message=2");
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

                        $_SESSION['message'] = 3;
                        header("HTTP/1.1 301 Moved Permanently"); 
                        header("Location: /gestion?gestionar=facultades&message=3");
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
        $adao = new Asignatura_dao();
        $cldao= new Clase_dao();
        $ddao = new Docente_dao();

        $count['facultades']  = $fdao->count();
        $count['carreras']    = $cdao->count();
        $count['itinerarios'] = $idao->count();
        $count['asignaturas'] = $adao->count();
        $count['clases']      = $cldao->count();
        $count['docentes']    = $ddao->count();
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
                <div class="col-md m-3 p-2 border text-center">
                    <div class="dashboardbox">
                    <?php echo $count['docentes']; ?> docentes <br>
                    <a class="btn btn-primary my-2" href="?gestionar=docentes" role="button">Gestionar docentes</a>
                    </div>
                </div>
            </div>
        <?php
    }

    function show_facultades(){
        $fdao = new Facultad_dao();
        $listado = $fdao->getList();

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
            <div id="formulario" class="col-md-5 mr-3 p-2 border">
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
                            <input type="checkbox" name="facultad[]" value="<?php echo $facultad->getId(); ?>">
                            <?php echo $facultad->getNombre() . ' ('. $facultad->getCampus() .')'; ?>
                            <span class="editar-button" onclick="editar_facultad(<?php echo $facultad->getId(); ?>)">Editar</span>
                            <input type="hidden" name="page" value="facultad">
                            <input type="hidden" id="accion-facultad" name="accion" value="remove">
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

    //TODO: Falta por hacer
    function show_carreras(){
        $cdao = new Carrera_dao();
        $listado = $cdao->getListado();

        if(isset($_GET['message'])){
            ?> <div class="alert alert-success" role="alert"> <?php
            switch ($_GET['message']) {
                case 1:
                    echo "Carrera añadida";
                break;

                case 2:
                    echo "Carrera(s) eliminada(s)";
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
            <div id="formulario" class="col-md-5 mr-3 p-2 border">
                <h4 id="accion-title">Añadir carrera</h4>
                <form action="?gestionar=carreras" method="post">
                    <div class="form-group">
                      <input type="text" class="form-control" name="nombre-carrera" id="nombre-carrera" placeholder="Nombre" required>
                    </div>
                    <div class="form-group">
                      <input type="text" class="form-control" name="campus-carrera" id="campus-carrera" placeholder="Campus" required>
                    </div>
                    <input type="hidden" name="page" value="carrera">
                    <input type="hidden" id="id-carrera" name="id-carrera" value="0">
                    <input type="hidden" id="accion-carrera" name="accion" value="add">
                    <button type="submit" id="submit-carrera" name="submit-carrera" class="btn btn-primary w-100">Añadir</button>
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
                    $fdao = new Facultad_dao();
                    ?>
                    <form action="?gestionar=carreras" method="post">
                        <fieldset>
                        <?php
                        foreach ($listado as $carrera) {
                            $f = $fdao->getById($carrera->getId_facultad());
                            $f = $f->getNombre();
                        ?>
                        <div class="gestion-list-element p-2 mb-2 border">
                            <input type="checkbox" name="carrera[]" value="<?php echo $carrera->getId(); ?>">
                            <?php echo $carrera->getNombre() . ' ('. $f .')'; ?>
                            <span class="editar-button" onclick="carrera(<?php echo $carrera->getId(); ?>)">Editar</span>
                            <input type="hidden" name="page" value="carrera">
                            <input type="hidden" id="accion-carrera" name="accion" value="remove">
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

    //TODO: Falta por hacer
    function show_itinerarios(){
        ?>
        
        <?php
    }

    //TODO: Falta por hacer
    function show_asignaturas(){
        ?>
        
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
