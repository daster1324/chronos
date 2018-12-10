<?php
// Cómo hacer UPDATE evitando SQL Injection

// 1º. Se añade el paquete Connection
require("./core/connection.php");

// 2º. Abrimos la conexión a la base de datos
$conn = Connection::connect();

// 3º. Se prepara la consulta SQL (el server la revisa, luego puede que pete si está mal escrita)
// UPDATE `clases` SET `cuatrimestre` = '1' WHERE `clases`.`id` = 2;
if (!($sentencia = $conn->prepare("UPDATE `clases` SET `cuatrimestre` = ? WHERE `id` = ?;"))) {
    echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
}

// 4º. Se vincula el parámetro a la consulta. Si se quiere hacer la misma query cambiando un dato,
//     no hace falta vincularlo otra vez. Solo hay que cambiar el dato y ya.
//
//     i -> integer || d -> double || s -> string || b -> blob y se envía en paquetes
//
//     $stmt = $mysqli->prepare("UPDATE `clases` SET `cuatrimestre` = ? WHERE `id` = ?;");
//     $stmt->bind_param("ii", $cuatrimestre, $id);
$id = 2;
$cuatrimestre = 1;

if (!$sentencia->bind_param("ii", $cuatrimestre, $id)) {
    echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
}

// 5º. Ejecutar la consulta
$sentencia->execute();

// 6º. Cerrar la sentencia y la conexión.
$sentencia->close();
$conn->close();

?>
