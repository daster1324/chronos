<?php

class Departamento_dao implements iDAO{
    
/*
    private $id;            // Integer 2 digitos - Obligatorio
    private $nombre;        // String 100 chars  - Obligatorio
    private $siglas;        // String 10 chars   - Obligatorio
    private $id_facultad;   // Integer 2 digitos - Obligatorio
*/
    public function __construct(){}

    /**
     * Devuelve un objeto con los datos de la departamento correspondiente al $id.
     * Devuelve NULL si no hay ningun departamento con ese $id 
     * 
     * @param $id - id del departamento a buscar
     */
    public function getById($id){
        $conn = Connection::connect();

        if (!($sentencia = $conn->prepare("SELECT * FROM `departamentos` WHERE id = ?;"))) {
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

        $departamento = new Departamento($r["id"], $r["nombre"], $r["id_facultad"], $r['siglas']);

        $sentencia->close();
        $conn->close();

        return $departamento;
    }

    /**
     * Obtiene el ID del departamento correspondiente con las siglas proporcionadas
     */
    public function getIdBySiglas($siglas){
        $conn = Connection::connect();

        if (!($sentencia = $conn->prepare("SELECT * FROM `departamentos` WHERE siglas LIKE ?;"))) {
            echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
        }

        if (!$sentencia->bind_param("s", $siglas)) {
            echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
        }

        $sentencia->execute();
        $result = $sentencia->get_result();
        
        $sentencia->close();
        $conn->close();

        if($result->num_rows === 0)
            return NULL;

        $r = $result->fetch_assoc();

        return $r["id"];
    }

    /**
     * Devuelve un objeto con los datos de todos los departamentos.
     * Devuelve un array vacío si no hay ningun departamento
     */
    public function getListado(){
        $conn = Connection::connect();

        $stmt = "SELECT * FROM `departamentos`;";       

        if (!($sentencia = $conn->prepare($stmt))) {
            echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
        }

        $sentencia->execute();

        $result = $sentencia->get_result();

        $sentencia->close();
        $conn->close();

        $departamentos = array();

        while($r = $result->fetch_assoc())
        {
            $departamentos[$r["id"]] = new Departamento($r["id"], $r["nombre"], $r["id_facultad"], $r['siglas']);
        }

        return $departamentos;
    }

    public function getDepartamentosFacultad($idfacultad){
        $conn = Connection::connect();

        $stmt = "SELECT * FROM `departamentos` WHERE id_facultad = ?;";       

        if (!($sentencia = $conn->prepare($stmt))) {
            echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
        }

        if (!$sentencia->bind_param("s", $idfacultad)) {
            echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
        }

        $sentencia->execute();

        $result = $sentencia->get_result();

        $sentencia->close();
        $conn->close();

        $departamentos = array();

        while($r = $result->fetch_assoc())
        {
            $departamentos[$r["id"]] = new Departamento($r["id"], $r["nombre"], $r["id_facultad"], $r['siglas']);
        }

        return $departamentos;
    }

    public function busca($nombre, $if_facultad){
        $conn = Connection::connect();

        if (!($sentencia = $conn->prepare("SELECT * FROM `departamentos` WHERE nombre = ? AND id_facultad = ?;"))) {
            echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
        }

        if (!$sentencia->bind_param("si", $nombre, $id_facultad)) {
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

    public function getListadoSin($id){
        $conn = Connection::connect();

        $stmt = "SELECT * FROM `departamentos` WHERE id != ?;";       

        if (!($sentencia = $conn->prepare($stmt))) {
            echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
        }

        if (!$sentencia->bind_param("i", $id)) {
            echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
        }

        $sentencia->execute();

        $result = $sentencia->get_result();

        $sentencia->close();
        $conn->close();

        $departamentos = array();

        while($r = $result->fetch_assoc())
        {
            $departamentos[$r["id"]] = new Departamento($r["id"], $r["nombre"], $r["id_facultad"], $r['siglas']);
        }

        return $departamentos;
    }


    /**
     * Guarda en la base de datos la departamento proporcionada
     * En caso de que ya exista, se actualizan los datos
     * 
     * @param $d - departamento a guardar
     */
    public function store($d){
        $conn = Connection::connect();

        $actualizar = ($this->getById($d->getId()) != NULL);

        $id  = $d->getId();
        $nombre  = $d->getNombre();
        $id_facultad  = $d->getid_facultad();
        $siglas = $d->getSiglas();

        if($actualizar){
            if (!($sentencia = $conn->prepare("UPDATE `departamentos` SET `nombre` = ?, `id_facultad` = ? WHERE `id` = ?;"))) {
                echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
            }
            if (!$sentencia->bind_param("sii", $nombre, $id_facultad, $id)) {
                echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
            }
            $sentencia->execute();
            $sentencia->close();
            $conn->close();
        }
        else{
            if (!($sentencia = $conn->prepare("INSERT INTO `departamentos` (`id`, `nombre`, `id_facultad`, `siglas`) VALUES (?, ?, ?, ?);"))) {
                echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
            }
            if (!$sentencia->bind_param("isis", $id, $nombre, $id_facultad, $siglas)) {
                echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
            }
            $sentencia->execute();
            $sentencia->close();
            $conn->close();
        }
    }

    /**
     * Elimina la departamento correspondiente al $id proporcionado
     * Devuelve true si ha habido éxito en el borrado.
     * Devuelve false si no se ha podido borrar.
     * 
     * @param $id - id del departamento a borrar
     */
    public function remove($id){
        $conn = Connection::connect();

        if (!($sentencia = $conn->prepare("DELETE FROM `departamentos` WHERE `id` = ?;"))) {
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
    
        if (!($sentencia = $conn->prepare("SELECT count(`id`) AS `cuenta` FROM `departamentos`;"))) {
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