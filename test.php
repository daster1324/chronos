<?php

require("core/includer.php");

$clase_dao = new Clase_dao();

echo "Consultando asignatura<br>";
$clase = $clase_dao->getById(1);
var_dump($clase_dao);

echo "Eliminando asignatura<br>";
$clase_dao->remove($clase->getId());

//$asignatura = new Asignatura(1, 1, 'ii', 'TEST', 'TST', '1', 1, NULL, 1);
//
//$clase = new Clase(1, 1, 1, "1", 1, "a", 1);

echo "Guardando asignatura<br>";
$clase_dao->store($clase);


?>