<?php

class Itinerario_dao implements iDAO{
   
/*
    private $id;                // Integer 2 digitos - Obligatorio
    private $id_carrera;        // Integer 2 digitos - Obligatorio
    private $nombre;            // String 150 chars  - Obligatorio
*/
    public function __construct(){}

    /**
     * Devuelve un objeto con los datos del itinerario correspondiente al $id.
     * Devuelve NULL si no hay ningun itinerario con ese $id 
     * 
     * @param $id - id del itinerario a buscar
     */
    public function getById($id){
        $conn = Connection::connect();

        if (!($sentencia = $conn->prepare("SELECT * FROM `itinerarios` WHERE id = ?;"))) {
            echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
        }

        if (!$sentencia->bind_param("i", $id)) {
            echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
        }

        $sentencia->execute();

        $result = $sentencia->get_result();

        if($result->num_rows === 0)
            return NULL;

        $r = $result->fetch_assoc();

        $itinerario = new Itinerario($r["id_carrera"], $r["id_itinerario"], $r["nombre"]);

        $sentencia->close();
        $conn->close();

        return $itinerario;
    }

    /**
     * Devuelve un array con los itinerarios correspondientes a la carrera dada.
     * Devuelve NULL si no hay ninguna carrera
     * 
     * @param $id_carrera - id de la carrera
     */
    public function getByIdCarrera($id_carrera){
        $conn = Connection::connect();

        if (!($sentencia = $conn->prepare("SELECT * FROM `itinerarios` WHERE id_carrera = ?;"))) {
            echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
        }

        if (!$sentencia->bind_param("i", $id_carrera)) {
            echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
        }

        $sentencia->execute();

        $result = $sentencia->get_result();

        if($result->num_rows === 0)
            return NULL;

        while($r = $result->fetch_assoc())
        {
            $itinerarios[] = new Itinerario($r["id"], $r["id_carrera"], $r["nombre"]);
        }

        $sentencia->close();
        $conn->close();

        return $itinerarios;
    }

    /**
     * Guarda en la base de datos el itinerario proporcionado
     * En caso de que ya exista, se actualizan los datos
     * 
     * @param $it - itinerario a guardar
     */
    public function store($it){
        $conn = Connection::connect();

        $id  = $it->getId();
        $id_carrera  = $it->getIdCarrera();
        $nombre  = $it->getNombre();

        $actualizar = ($this->getById($it->getId()) != NULL);

        if($actualizar){
            if (!($sentencia = $conn->prepare("UPDATE `itinerarios` SET `nombre` = ? WHERE `id` = ?;"))) {
                echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
            }
            if (!$sentencia->bind_param("si", $nombre, $id)) {
                echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
            }
            $sentencia->execute();
            $sentencia->close();
            $conn->close();
        }
        else{
            if (!($sentencia = $conn->prepare("INSERT INTO `itinerarios` (`id`, `id_carrera`, `nombre`) VALUES (?, ?, ?);"))) {
                echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
            }
            if (!$sentencia->bind_param("iis", $id, $id_carrera, $nombre)) {
                echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
            }
            $sentencia->execute();
            $sentencia->close();
            $conn->close();
        }
    }

    /**
     * Elimina la itinerario correspondiente al $id proporcionado
     * Devuelve true si ha habido éxito en el borrado.
     * Devuelve false si no se ha podido borrar.
     * 
     * @param $id - id de la itinerario a borrar
     */
    public function remove($id){
        $conn = Connection::connect();

        if (!($sentencia = $conn->prepare("DELETE FROM `itinerarios` WHERE `id` = ?;"))) {
            echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
        }

        if (!$sentencia->bind_param("i", $id)) {
            echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
        }

        $sentencia->execute();

        $sentencia->close();
        $conn->close();
    }
    
}




?>