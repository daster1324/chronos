<?php

class Carrera_dao implements iDAO{

/*
    private $id;             // Integer 2 digitos - Obligatorio
    private $nombre;         // String 150 chars  - Obligatorio
    private $id_facultad;    // Integer 2 digitos - Obligatorio
    private $id_facultad_dg; // Integer 2 digitos - Opcional
*/
    public function __construct(){}

    /**
     * Devuelve un objeto con los datos de la carrera correspondiente al $id.
     * Devuelve NULL si no hay ninguna carrera con ese $id 
     * 
     * @param $id - id de la carrera a buscar
     */
    public function getById($id){
        $conn = Connection::connect();

        if (!($sentencia = $conn->prepare("SELECT * FROM `carreras` WHERE id = ?;"))) {
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

        $carrera = new Carrera($r["id"], $r["nombre"], $r["id_facultad"], $r["id_facultad_dg"]);

        $sentencia->close();
        $conn->close();

        return $carrera;
    }

    /**
     * Devuelve un objeto con los datos de todas las carreras.
     * Devuelve NULL si no hay ninguna carrera registrada 
     * 
     */
    public function getListado(){
        $conn = Connection::connect();

        if (!($sentencia = $conn->prepare("SELECT * FROM `carreras` ORDER BY `nombre`;"))) {
            echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
        }

        $sentencia->execute();

        $result = $sentencia->get_result();

        if($result->num_rows === 0)
            return NULL;

        while($r = $result->fetch_assoc())
        {
            $carreras[] = new Carrera($r["id"], $r["nombre"], $r["id_facultad"], $r["id_facultad_dg"]);
        }

        $sentencia->close();
        $conn->close();

        return $carreras;
    }

    public function getListadoByFacultad($facultad){
        $conn = Connection::connect();

        if (!($sentencia = $conn->prepare("SELECT * FROM `carreras` WHERE `id_facultad` = ? ORDER BY `nombre`;"))) {
            echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
        }

        if (!$sentencia->bind_param("i", $facultad)) {
            echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
        }

        $sentencia->execute();

        $result = $sentencia->get_result();

        $carreras = array();

        while($r = $result->fetch_assoc())
        {
            $carreras[] = new Carrera($r["id"], $r["nombre"], $r["id_facultad"], $r["id_facultad_dg"]);
        }

        $sentencia->close();
        $conn->close();

        return $carreras;
    }


    public function busca($nombre, $id_facultad, $id_facultad_dg){
        $conn = Connection::connect();

        if (!($sentencia = $conn->prepare("SELECT * FROM `carreras` WHERE nombre = ? AND id_facultad = ? AND id_facultad_dg = ?;"))) {
            echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
        }

        if (!$sentencia->bind_param("sii", $nombre, $id_facultad, $id_facultad_dg)) {
            echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
        }

        $sentencia->execute();

        $result = $sentencia->get_result();
        
        $sentencia->close();
        $conn->close();

        if($result->num_rows === 0)
            return false;

        return true;
    }

    /**
     * Guarda en la base de datos la carrera proporcionada
     * En caso de que ya exista, se actualizan los datos
     * 
     * @param $c - carrera a guardar
     */
    public function store($c){
        $conn = Connection::connect();
        
        $id  = $c->getId();
        $nombre  = $c->getNombre();
        $id_facultad  = $c->getId_facultad();
        $id_facultad_dg  = $c->getId_facultad_dg();

        $actualizar = ($this->getById($c->getId()) != NULL);

        if($actualizar){
            if (!($sentencia = $conn->prepare("UPDATE `carreras` SET `nombre` = ?, `id_facultad` = ?, `id_facultad_dg` = ? WHERE `id` = ?;"))) {
                echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
            }
            if (!$sentencia->bind_param("siii", $nombre, $id_facultad, $id_facultad_dg, $id)) {
                echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
            }
            $sentencia->execute();
            $sentencia->close();
            $conn->close();
        }
        else{
            if (!($sentencia = $conn->prepare("INSERT INTO `carreras` (`id`, `nombre`, `id_facultad`, `id_facultad_dg`) VALUES (?, ?, ?, ?);"))) {
                echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
            }
            if (!$sentencia->bind_param("isii", $id, $nombre, $id_facultad, $id_facultad_dg)) {
                echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
            }
            $sentencia->execute();
            $sentencia->close();
            $conn->close();
        }
    }

    /**
     * Elimina la carrera correspondiente al $id proporcionado
     * Devuelve true si ha habido éxito en el borrado.
     * Devuelve false si no se ha podido borrar.
     * 
     * @param $id - id de la carrera a borrar
     */
    public function remove($id){
        $conn = Connection::connect();

        if (!($sentencia = $conn->prepare("DELETE FROM `carreras` WHERE `id` = ?;"))) {
            echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
        }

        if (!$sentencia->bind_param("i", $id)) {
            echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
        }

        $sentencia->execute();

        $sentencia->close();
        $conn->close();
    }

    public function count(){        
        $conn = Connection::connect();
    
        if (!($sentencia = $conn->prepare("SELECT count(`id`) AS `cuenta` FROM `carreras`;"))) {
            echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
        }

        $sentencia->execute();

        $result = $sentencia->get_result();

        $sentencia->close();
        $conn->close();

        if($result->num_rows === 0)
            return 0;

        $r = $result->fetch_assoc();

        return $r['cuenta'];
    }
}

?>