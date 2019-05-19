<?php
    session_set_cookie_params((3600*24*30), "/", $_SERVER["SERVER_NAME"], 0, true);
    session_start();

    require('common.php');
    require('core/includer.php');

    // Funciones para contenido dinámico

    if(isset($_POST['submit-preferencias'])){
        $dodao = new Docente_dao();
        $estado = $dodao->store_preferencias($_POST['preferencias'], $_SESSION['docente-id']);
        unset($dodao);

        switch ($estado) {
            case 'added':
                $message = 1;
                break;

            case 'updated':
                $message = 2;
                break;

            case 'blocked':
                $message = 3;
                break;
            
            default: break;
        }

        header("HTTP/1.1 301 Moved Permanently"); 
        header("Location: /docentes?gestionar=docentes&message=".$message);  
    }

    function showLogin(){
    ?>
    <div class="main-content bg-dark">
        <div class="section px-2">
            <form id="form-inicial" action="/docentes" method="post">
                <h1 class="font-weight-light text-center text-light">Chronos - Docentes</h1>
                <div class="form-group">
                <input type="text" class="form-control" name="usuario" id="usuario-docente" placeholder="Usuario" autofocus>
                </div>
                <div class="form-group">
                <input type="password" class="form-control"  name="password" id="usuario-docente" placeholder="Contraseña">
                </div>
                <input id="login" class="btn btn-light w-100 my-1" type="submit" value="Iniciar Sesión">
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
                        <li class="nav-item mr-auto <?php active('/'); ?>">
                            <a class="nav-link" href="/docentes">Inicio <?php sr_only('/'); ?></a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            ¡Hola, <?php echo $_SESSION['docente-usuario']; ?>!
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="?gestionar=password">Cambiar contraseña</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="/logout?redirect=docentes">Cerrar sesión</a>
                            </div>
                        </li>
                        
                    </ul>
                </div>
            </nav>
            <div class="container pt-3">
            <?php
            
                $cur = (isset($_GET['gestionar'])) ? $_GET['gestionar'] : "/";

                switch ($cur) {
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

    function change_password(){
        if(isset($_POST['cambiar-password'])){

            if($_POST['new-pass-1'] === $_POST['new-pass-2']){
                $ddao = new Docente_dao();

                $id = $_SESSION['docente-id'];
                $pass = hash("sha256", $_SESSION['docente-usuario'] . SALT . $_POST['new-pass-1']);
                $oldpass = hash("sha256", $_SESSION['docente-usuario'] . SALT . $_POST['old-pass']);

                $user = $ddao->login($_SESSION['docente-usuario'], $oldpass, true);

                if($user != false){
                    $ddao->change_password($id, $pass);
                    $message = 1;
                }
                else{
                    $message = 2;
                }
            }
            else{
                $message = 2;
            }

            header("HTTP/1.1 301 Moved Permanently"); 
            header("Location: /docentes?gestionar=password&message=".$message);
        }

        if(isset($_GET['message'])){
            ?> <div class="alert alert-success" role="alert"> <?php
            switch ($_GET['message']) {
                case 1:
                    echo "Contraseña cambiada con éxito";
                break;

                case 2:
                    echo "Error al cambiar la contraseña. Comprueba que has introducido bien los datos.";
                break;
                
                default: break;
            }
            ?> </div> <?php
            unset($_GET['message']);
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

    function excepto_progresivo($i, $preferencias){
        $toReturn = array();
        for ($index=0; $index < $i; $index++) { 
            $toReturn[] = $preferencias[$index];
        }
        return $toReturn;
    }

    function show_inicio(){
        if(isset($_GET['message'])){
            ?> <div class="alert alert-success" role="alert"> <?php
            switch ($_GET['message']) {
                case 1:
                    echo "Preferencias añadidas";
                break;

                case 2:
                    echo "Preferencias modificadas";
                break;

                case 3:
                    echo "No puedes cambiar tus preferencias. Ya se ha establecido el orden de selección.";
                break;

                default: break;
            }
            ?> </div> <?php
            unset($_GET['message']);
        }
        $dodao = new Docente_dao();
        $docente = $dodao->getById($_SESSION['docente-id']);

        $hay_preferencias = strlen($docente->getPreferencias()) > 0;
        $preferencias = json_decode($docente->getPreferencias());

        $adao = new Asignatura_dao();

        if(!$hay_preferencias){
            $asignaturas = $adao->listadoExcepto();
        }
        

        ?>

        <div class="row justify-content-between">
            <!-- formulario -->
            <div id="formulario" class="col-6 mx-auto p-2 border">
                <h4 id="accion-title">Preferencias</h4>
                <form action="?gestionar=preferencias" method="post">

                    <?php
                        for ($i=1; $i < 7; $i++) {
                            if($hay_preferencias && $i <= count($preferencias)+1){
                                $asignaturas = $adao->listadoExcepto(excepto_progresivo($i-1, $preferencias));
                            }
                            ?>
                            <!-- Preferencia <?= $i; ?> -->
                                <label for="selector-preferencia-<?= $i; ?>" class="mt-2 mb-1">Preferencia <?= $i; ?></label>
                                <select id="selector-preferencia-<?= $i; ?>" name="preferencia-<?= $i; ?>" class="custom-select text-dark preferencia-asignatura" 
                                    <?php 
                                        if($i==1)
                                            echo 'required'; 
                                        if ($i > count($preferencias) + 1)
                                            echo 'disabled'; ?>
                                >
                                    <option disabled="disabled" selected="selected" value="">Selecciona una asignatura</option>
                                    <?php
                                    if($i == 1 || $i <= count($preferencias)+1)
                                    foreach ($asignaturas as $asignatura) {
                                        echo '<option ';

                                        if(isset($preferencias[$i-1]))
                                            if($asignatura['id'] == (int)$preferencias[$i-1])
                                                echo ' selected ';

                                        echo' value="'.$asignatura['id'].'">('.$asignatura['carrera'].') ['.$asignatura['id'].'] '.$asignatura['nombre'].'</option>';
                                    }
                                    ?>
                                </select>
                            <!-- /Preferencia <?= $i; ?> -->
                            <?php
                        }
                    ?>
                    <input type="hidden" name="preferencias" id="preferencias" value='0'>
                    <button type="submit" id="submit-preferencias" name="submit-preferencias" class="btn btn-primary w-100 mt-2">Guardar</button>
                </form>
            </div>
            <!-- /formulario -->
        </div>
        <?php
    }

    // Contenido de la web
    get_head();

    if(isset($_POST['usuario']) && isset($_POST['password'])){
        $ddao = new Docente_dao();

        $user = $_POST['usuario'];
        $pass = hash("sha256", $user . SALT . $_POST['password']);

        if($ddao->login($user, $pass)){
            showDashboard();
        }
        else{
            showLogin();
        }
    }
    else if(!isset($_SESSION['docente-id'])){
        showLogin();
    }
    else{
        showDashboard();
    }

    

    get_scriptsAndFooter(); 
    
    die();
?>
