<?php
    session_set_cookie_params((3600*24*30), "/", $_SERVER["SERVER_NAME"], 0, true);
    session_start();

    require('common.php');
    require('core/includer.php');

    // Funciones para contenido dinámico

    function showLogin(){
    ?>
    <div class="main-content bg-dark">
        <div class="section px-2">
            <form id="form-inicial" action="/docentes" method="post" onsubmit="return submitForm();">
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
    <div class="main-content bg-light">
        <h1>¡HOLA <?php echo $_SESSION['docente-usuario']; ?>!</h1>
    </div>
    <?php 
    }

    // Contenido de la web
    get_head();

    if(isset($_POST['usuario']) && isset($_POST['password'])){
        $ddao = new Docente_dao();

        $user = $_POST['usuario'];
        $pass = hash("sha256", $user + SALT + $_POST['password']);

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
