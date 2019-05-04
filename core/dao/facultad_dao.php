<?php

class Facultad_dao implements iDAO{
   
/*
    private $id;        // Integer 2 digitos - Obligatorio
    private $nombre;    // String 100 chars  - Obligatorio
    private $campus;    // String 100 chars  - Optional
*/
    public function __construct(){}

    /**
     * Devuelve un objeto con los datos de la facultad correspondiente al $id.
     * Devuelve NULL si no hay ninguna facultad con ese $id 
     * 
     * @param $id - id de la facultad a buscar
     */
    public function getById($id){
        $conn = Connection::connect();

        if (!($sentencia = $conn->prepare("SELECT * FROM `facultades` WHERE id = ?;"))) {
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

        $facultad = new Facultad($r["id"], $r["nombre"], $r["campus"]);

        $sentencia->close();
        $conn->close();

        return $facultad;
    }

    public function busca($nombre, $campus){
        $conn = Connection::connect();

        if (!($sentencia = $conn->prepare("SELECT * FROM `facultades` WHERE nombre = ? AND campus = ?;"))) {
            echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
        }

        if (!$sentencia->bind_param("ss", $nombre, $campus)) {
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
     * Guarda en la base de datos la facultad proporcionada
     * En caso de que ya exista, se actualizan los datos
     * 
     * @param $f - facultad a guardar
     */
    public function store($f){
        $conn = Connection::connect();

        $id  = $f->getId();
        $nombre  = $f->getNombre();
        $campus  = $f->getCampus();

        $actualizar = ($this->getById($f->getId()) != NULL);

        if($actualizar){
            if (!($sentencia = $conn->prepare("UPDATE `facultades` SET `nombre` = ?, `campus` = ? WHERE `id` = ?;"))) {
                echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
            }
            if (!$sentencia->bind_param("ssi", $nombre, $campus, $id)) {
                echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
            }
            $sentencia->execute();
            $sentencia->close();
            $conn->close();
        }
        else{
            if (!($sentencia = $conn->prepare("INSERT INTO `facultades` (`id`, `nombre`, `campus`) VALUES (?, ?, ?);"))) {
                echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
            }
            if (!$sentencia->bind_param("iss", $id, $nombre, $campus)) {
                echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
            }
            $sentencia->execute();
            $sentencia->close();
            $conn->close();
        }
    }

    /**
     * Elimina la facultad correspondiente al $id proporcionado
     * Devuelve true si ha habido éxito en el borrado.
     * Devuelve false si no se ha podido borrar.
     * 
     * @param $id - id de la facultad a borrar
     */
    public function remove($id){
        $conn = Connection::connect();

        if (!($sentencia = $conn->prepare("DELETE FROM `facultades` WHERE `id` = ?;"))) {
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
    
        if (!($sentencia = $conn->prepare("SELECT count(`id`) AS `cuenta` FROM `facultades`;"))) {
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

    public function getList(){
        $conn = Connection::connect();

        $stmt = "SELECT * FROM `facultades`;";       

        if (!($sentencia = $conn->prepare($stmt))) {
            echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
        }

        $sentencia->execute();

        $result = $sentencia->get_result();

        $sentencia->close();
        $conn->close();

        if($result->num_rows === 0)
            return NULL;

        $facultades = array();

        while($r = $result->fetch_assoc())
        {
            $facultades[$r["id"]] = new Facultad($r["id"], $r["nombre"], $r["campus"]);
        }

        return $facultades;
    }
}




?>