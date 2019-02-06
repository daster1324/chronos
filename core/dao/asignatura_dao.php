<?php

class Asignatura_dao implements iDAO{

/*
    private $id;                    // Integer 10 digitos   - Obligatorio
    private $id_carrera;            // Integer 2 digitos    - Obligatorio
    private $itinerario;            // String 5 chars       - Opcional
    private $nombre;                // String 150 chars     - Obligatorio
    private $abreviatura;           // String 10 chars      - Opcional
    private $curso;                 // Integer 1 digito     - Obligatorio
    private $id_departamento;       // Integer 2 digitos    - Obligatorio
    private $id_departamento_dos;   // Integer 2 digitos    - Opcional
    private $creditos;              // Integer 2 digitos    - Obligatorio
*/

    public function __construct(){}

    /**
     * Devuelve un objeto con los datos de la asignatura correspondiente al $id.
     * Devuelve NULL si no hay ninguna asignatura con ese $id 
     * 
     * @param $id - id de la asignatura a buscar
     */
    public function getById($id){        
        $conn = Connection::connect();

        if (!($sentencia = $conn->prepare("SELECT * FROM `asignaturas` WHERE id = ?;"))) {
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

        $asignatura = new Asignatura($r["id"], $r["id_carrera"], $r["itinerario"], $r["nombre"],
        $r["abreviatura"], $r["curso"], $r["id_departamento"],
        $r["id_departamento_dos"], $r["creditos"]);

        $sentencia->close();
        $conn->close();

        return $asignatura;
    }

    /**
     * Devuelve un array con las asignaturas del curso indicado en la carrera indicada.
     * Devuelve NULL si no hay ninguna asignatura con ese $id 
     * 
     * @param $carrera - id de la carrera
     * @param $curso - curso en cuestión
     */
    public function getByCarreraCurso($carrera, $curso){
        $conn = Connection::connect();

        if (!($sentencia = $conn->prepare("SELECT * FROM `asignaturas` WHERE id_carrera = ? and curso = ?;"))) {
            echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
        }

        if (!$sentencia->bind_param("is", $carrera, $curso)) {
            echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
        }

        $sentencia->execute();

        $result = $sentencia->get_result();

        if($result->num_rows === 0)
            return NULL;

        while($r = $result->fetch_assoc())
        {
            $asignaturas[] = new Asignatura($r["id"], $r["id_carrera"], $r["itinerario"], $r["nombre"],
                                         $r["abreviatura"], $r["curso"], $r["id_departamento"],
                                         $r["id_departamento_dos"], $r["creditos"]);
        }
        $sentencia->close();
        $conn->close();

        return $asignaturas;
    }

    /**
     * Guarda en la base de datos la asignatura proporcionada
     * En caso de que ya exista, se actualizan los datos
     * 
     * @param $a - asignatura a guardar
     */
    public function store($a){
        $conn = Connection::connect();

        $actualizar = ($this->getById($a->getId()) != NULL);

        $id  = $a->getId();
        $id_carrera  = $a->getId_carrera();
        $itinerario  = $a->getItinerario();
        $nombre  = $a->getNombre();
        $abreviatura  = $a->getAbreviatura();
        $curso  = $a->getCurso();
        $id_departamento  = $a->getId_departamento();
        $id_departamento_dos  = $a->getId_departamento_dos();
        $creditos  = $a->getCreditos();

        if($actualizar){
            if (!($sentencia = $conn->prepare("UPDATE `asignaturas` SET `id_carrera` = ?, `itinerario` = ?, `nombre` = ?, `abreviatura` = ?, `curso` = ?, `id_departamento` = ?, `id_departamento_dos` = ?, `creditos` = ? WHERE `id` = ?"))) {
                echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
            }

            if (!$sentencia->bind_param("issssiiii", $id_carrera, $itinerario, $nombre, $abreviatura, $curso, $id_departamento, $id_departamento_dos, $creditos, $id)) {
                echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
            }
            
            $sentencia->execute();
    
            $sentencia->close();
            $conn->close();
        }
        else{
            if (!($sentencia = $conn->prepare("INSERT INTO `asignaturas` (`id`, `id_carrera`, `itinerario`, `nombre`, `abreviatura`, `curso`, `id_departamento`, `id_departamento_dos`, `creditos`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?);"))) {
                echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
            }
    
            if (!$sentencia->bind_param("iisssiisi", $id, $id_carrera, $itinerario, $nombre, $abreviatura, $curso, $id_departamento, $id_departamento_dos, $creditos)) {
                echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
            }
            
            $sentencia->execute();
    
            $sentencia->close();
            $conn->close();
        }
    }

    /**
     * Elimina la asignatura correspondiente al $id proporcionado.
     * Devuelve true si ha habido éxito en el borrado.          
     * Devuelve false si no se ha podido borrar.
     * 
     * @param $id - id de la asignatura a borrar
     */
    public function remove($id){
        $conn = Connection::connect();

        if (!($sentencia = $conn->prepare("DELETE FROM `asignaturas` WHERE `id` = ?;"))) {
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