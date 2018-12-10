<?php
// Cómo hacer SELECT evitando SQL Injection

// 1º. Se añade el paquete Connection
require("./core/connection.php");

// 2º. Abrimos la conexión a la base de datos
$conn = Connection::connect();

// 3º. Se prepara la consulta SQL (el server la revisa, luego puede que pete si está mal escrita)
if (!($sentencia = $conn->prepare("SELECT * FROM `clases` WHERE id = ?;"))) {
    echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
}

// 4º. Se vincula el parámetro a la consulta. Si se quiere hacer la misma query cambiando un dato,
//     no hace falta vincularlo otra vez. Solo hay que cambiar el dato y ya.
//
//     i -> integer || d -> double || s -> string || b -> blob y se envía en paquetes
//
//     $stmt = $mysqli->prepare("INSERT INTO CountryLanguage VALUES (?, ?, ?, ?)");
//     $stmt->bind_param('issd', $code, $language, $official, $percent);
$id = 1;
if (!$sentencia->bind_param("i", $id)) {
    echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
}

// 5º. Ejecutar la consulta
$sentencia->execute();

// 6º. Recuperar los resultados y procesar los resultados
$result = $sentencia->get_result();

if($result->num_rows === 0) 
    exit('No rows');

while($row = $result->fetch_assoc()) {
    var_dump($row);
}

// 7º. Cerrar la sentencia y la conexión.
$sentencia->close();

$conn->close();
?>
