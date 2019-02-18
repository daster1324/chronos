<?php

class Clase_dao implements iDAO{

/*
    private $id;            // Long Integer 15 digitos  - Obligatorio
    private $id_asignatura; // Integer 10 digitos       - Obligatorio
    private $cuatrimestre;  // Integer 1 digito         - Obligatorio
    private $dia;           // String 1 char            - Obligatorio
    private $hora;          // Integer 1 digito         - Obligatorio
    private $grupo;         // String 10 chars          - Obligatorio
    private $edificio;      // Integer 1 digito         - Obligatorio
*/
    private $clase;

    public function __construct(){
        $this->clase = NULL;
    }

    /**
     * Devuelve un objeto con los datos de la clase correspondiente al $id.
     * Devuelve NULL si no hay ninguna clase con ese $id 
     * 
     * @param $id - id de la clase a buscar
     */
    public function getById($id){
        $conn = Connection::connect();

        if (!($sentencia = $conn->prepare("SELECT * FROM `clases` WHERE id = ?;"))) {
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

        $asignatura = new Clase($r["id"], $r["id_asignatura"], $r["cuatrimestre"], $r["dia"],
        $r["hora"], $r["grupo"], $r["edificio"]);

        $sentencia->close();
        $conn->close();

        return $asignatura;
    }

    /**
     * Devuelve un array con las clases correspondientes a la asignatura dada.
     * Devuelve NULL si no hay ninguna clase
     * 
     * @param $asignatura - asignatura de la clase a buscar
     */
    public function getByIdAsignatura($id){
        $conn = Connection::connect();

        if (!($sentencia = $conn->prepare("SELECT * FROM `clases` WHERE id_asignatura = ?;"))) {
            echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
        }

        if (!$sentencia->bind_param("i", $id)) {
            echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
        }

        $sentencia->execute();

        $result = $sentencia->get_result();

        if($result->num_rows === 0)
            return NULL;

        while($r = $result->fetch_assoc())
        {
            $clases[] = new Clase($r["id"], $r["id_asignatura"], $r["cuatrimestre"], $r["dia"],
            $r["hora"], $r["grupo"], $r["edificio"]);
        }

        var_dump($clases);

        $sentencia->close();
        $conn->close();

        return $clases;
    }

    /**
     * Guarda en la base de datos la clase proporcionada
     * En caso de que ya exista, se actualizan los datos
     * 
     * @param $clase - clase a guardar
     */
    public function store($clase){
        $conn = Connection::connect();

        $id = $clase->getId();
        $id_asignatura = $clase->getId_asignatura();
        $cuatrimestre = $clase->getCuatrimestre();
        $dia = $clase->getDia();
        $hora = $clase->getHora();
        $grupo = $clase->getGrupo();
        $edificio = $clase->getEdificio();

        $actualizar = ($this->getById($clase->getId()) != NULL);

        if($actualizar){
            if (!($sentencia = $conn->prepare("UPDATE `clases` SET `id_asignatura` = ?, `cuatrimestre` = ?, `dia` = ?, `hora` = ?, `grupo` = ?, `edificio` = ? WHERE `id` = ?"))) {
                echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
            }
            if (!$sentencia->bind_param("iisisii", $id_asignatura, $cuatrimestre, $dia, $hora, $grupo, $edificio, $id)) {
                echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
            }
            $sentencia->execute();
            $sentencia->close();
            $conn->close();
        }
        else{
            if (!($sentencia = $conn->prepare("INSERT INTO `clases` (`id`, `id_asignatura`, `cuatrimestre`, `dia`, `hora`, `grupo`, `edificio`) VALUES (?, ?, ?, ?, ?, ?, ?);"))) {
                echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
            }
            if (!$sentencia->bind_param("iiisisi", $id, $id_asignatura, $cuatrimestre, $dia, $hora, $grupo, $edificio)) {
                echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
            }
            $sentencia->execute();
            $sentencia->close();
            $conn->close();
        }
    }

    /**
     * Elimina la clase correspondiente al $id proporcionado
     * Devuelve true si ha habido éxito en el borrado.
     * Devuelve false si no se ha podido borrar.
     */
    public function remove($id){
        $conn = Connection::connect();

        if (!($sentencia = $conn->prepare("DELETE FROM `clases` WHERE `id` = ?;"))) {
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