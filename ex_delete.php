<?php
// Cómo hacer DELETE evitando SQL Injection

// 1º. Se añade el paquete Connection
require("./core/connection.php");

// 2º. Abrimos la conexión a la base de datos
$conn = Connection::connect();

// 3º. Se prepara la consulta SQL (el server la revisa, luego puede que pete si está mal escrita)
// DELETE FROM `clases` WHERE `id_asignatura` = 2;
if (!($sentencia = $conn->prepare("DELETE FROM `clases` WHERE `id_asignatura` = ?;"))) {
    echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
}

// 4º. Se vincula el parámetro a la consulta. Si se quiere hacer la misma query cambiando un dato,
//     no hace falta vincularlo otra vez. Solo hay que cambiar el dato y ya.
//
//     i -> integer || d -> double || s -> string || b -> blob y se envía en paquetes
//
//     $stmt = $mysqli->prepare("DELETE FROM `clases` WHERE `id_asignatura` = ?;");
//     $stmt->bind_param("i", $id_asignatura);
$id_asignatura = 2;

if (!$sentencia->bind_param("i", $id_asignatura)) {
    echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
}

// 5º. Ejecutar la consulta
$sentencia->execute();

// 6º. Cerrar la sentencia y la conexión.
$sentencia->close();

$conn->close();
?>
