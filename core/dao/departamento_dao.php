<?php

class Departamento_dao implements iDAO{
    
/*
    private $id;            // Integer 2 digitos - Obligatorio
    private $nombre;        // String 100 chars  - Obligatorio
    private $id_facultad;   // Integer 2 digitos - Obligatorio
*/
    public function __construct(){}

    /**
     * Devuelve un objeto con los datos de la departamento correspondiente al $id.
     * Devuelve NULL si no hay ninguna departamento con ese $id 
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

        $departamento = new Departamento($r["id"], $r["nombre"], $r["id_facultad"]);

        $sentencia->close();
        $conn->close();

        return $departamento;
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
            if (!($sentencia = $conn->prepare("INSERT INTO `departamentos` (`id`, `nombre`, `id_facultad`) VALUES (?, ?, ?);"))) {
                echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
            }
            if (!$sentencia->bind_param("isi", $id, $nombre, $id_facultad)) {
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