<?php
// Clase encargada de incluir todos los php del core en orden de dependencia
header('Content-Type: text/html; charset=UTF-8');

// Estructura de datos
require("asignatura.php");
require("carrera.php");
require("clase.php");
require("departamento.php");
require("facultad.php");
require("itinerario.php");
require("docente.php");
require("gestor.php");

// Conector a la BD
require("connection.php");

// Dao
require("dao/i_dao.php");
require("dao/asignatura_dao.php");
require("dao/carrera_dao.php");
require("dao/clase_dao.php");
require("dao/departamento_dao.php");
require("dao/facultad_dao.php");
require("dao/itinerario_dao.php");
require("dao/docente_dao.php");
require("dao/gestor_dao.php");

// Loader
require("loader.php");

//algoritmos
require("algoritmos/algoritmo_backtracking.php");
require("algoritmos/i_Algoritmo.php");

?>
