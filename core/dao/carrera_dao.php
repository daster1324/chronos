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
     * Guarda en la base de datos la carrera proporcionada
     * En caso de que ya exista, se actualizan los datos
     * 
     * TODO: falta la parte de actualizar
     * 
     * @param $c - carrera a guardar
     */
    public function store($c){
        $conn = Connection::connect();

        if (!($sentencia = $conn->prepare("INSERT INTO `carreras` (`id`, `nombre`, `id_facultad`, `id_facultad_dg`) VALUES (?, ?, ?, ?);"))) {
            echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
        }

        $id  = $c->getId();
        $nombre  = $c->getNombre();
        $id_facultad  = $c->getId_facultad();
        $id_facultad_dg  = $c->getId_facultad_dg();

        if (!$sentencia->bind_param("isii", $id, $nombre, $id_facultad, $id_facultad_dg)) {
            echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
        }
        
        $sentencia->execute();

        $sentencia->close();
        $conn->close();
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
}

?>