<?php
//INSERT INTO `asignaturas` (`id`, `id_carrera`, `itinerario`, `nombre`, `abreviatura`, `curso`, `id_departamento`, `id_departamento_dos`, `creditos`) VALUES ('3', '1', 'ii', 'tst', 'tst', '1', '1', '1', '12')




// Cómo hacer INSERT evitando SQL Injection

// 1º. Se añade el paquete Connection
require("./core/connection.php");

// 2º. Abrimos la conexión a la base de datos
$conn = Connection::connect();

// 3º. Se prepara la consulta SQL (el server la revisa, luego puede que pete si está mal escrita)
// INSERT INTO `clases` (`id`, `id_asignatura`, `cuatrimestre`, `dia`, `hora`, `grupo`, `edificio`) VALUES ('2', '2', '2', 'm', '2', 'a', '1');
if (!($sentencia = $conn->prepare("INSERT INTO `clases` (`id_asignatura`, `cuatrimestre`, `dia`, `hora`, `grupo`, `edificio`) VALUES (?, ?, ?, ?, ?, ?);"))) {
    echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
}

// 4º. Se vincula el parámetro a la consulta. Si se quiere hacer la misma query cambiando un dato,
//     no hace falta vincularlo otra vez. Solo hay que cambiar el dato y ya.
//
//     i -> integer || d -> double || s -> string || b -> blob y se envía en paquetes
//
//     $stmt = $mysqli->prepare("INSERT INTO `clases` (`id_asignatura`, `cuatrimestre`, `dia`, `hora`, `grupo`, `edificio`) VALUES (?, ?, ?, ?, ?, ?);");
//     $stmt->bind_param("iisisi", $id_asignatura, $cuatrimestre, $dia, $hora, $grupo, $edificio);
$id_asignatura = 2;
$cuatrimestre = 2;
$dia = "m";
$hora = 2;
$grupo = "a";
$edificio = 1;

if (!$sentencia->bind_param("iisisi", $id_asignatura, $cuatrimestre, $dia, $hora, $grupo, $edificio)) {
    echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
}

// 5º. Ejecutar la consulta
$sentencia->execute();

// 6º. Recuperar el id autogenerado que se utilizó en la última consulta
$id = $sentencia->insert_id;

// 7º. Cerrar la sentencia y la conexión.
$sentencia->close();

$conn->close();
?>
