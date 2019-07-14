<?php
    session_set_cookie_params((3600*24*30), "/", $_SERVER["SERVER_NAME"], 0, true);
    session_start();

    require('common.php');
    require('core/includer.php');

    // Contenido de la web
    get_head();
?>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Chronos<sup class="logo-sup">BETA</sup></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="/">Inicio</a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="/proyecto">El proyecto <span class="sr-only">(actual)</span></a>
            </li>
            </ul>
        </div>
    </nav>
    <div class="container text-light mt-3">
        <div class="row">
            <div class="col-12">
                <h1>El proyecto</h1>
            </div>
            <div class="col-12 mt-2 text-justify">
                <h3>Surgimiento</h3>
                <p>Chronos surge de una necesidad que acaban teniendo casi todos los estudiantes universitarios: compaginar los horarios de asignaturas que no pertenecen a un mismo curso.</p>
                <p>Dependiendo de si son pocas asignaturas y de un mismo curso o, por el contrario, son bastantes y además pertenecientes a distintos cursos, cuadrar el horario puede resultar una tarea sencilla o titánica.</p>
            </div>
            <div class="col-12 mt-2 text-justify">
                <h3>¿Qué ofrece esta versión?</h3>
                <p>La versión que se ofrece es la primera iteración de una solución con un potencial altísimo.</p>
                <p>Por el momento, solo están recogidas los grados de la facultad de informática de la UCM. Tanto los grados de otras facultades como los másters y doble grados de la FdI no están disponibles.</p>
            </div>
            <div class="col-12 mt-2 text-justify text-warning font-weight-bold">
                <h3>AVISO</h3>
                <p>Cabe la posibilidad de que los datos que se muestren en esta aplicación no sean correctos por posibles posibles desactualizaciones o fallos no encontrados.</p>
                <p>Recomendamos que, una vez obtenido el horario a través de la aplicación, se verifique haciendo uso de <a href="https://informatica.ucm.es/informatica/horarios" target="_blank">los horarios de la web de la FdI.</a></p>
            </div>
        </div>
            
        </div>
    </div>

<?php
    
    get_scriptsAndFooter(); 
    
    die();
?>
