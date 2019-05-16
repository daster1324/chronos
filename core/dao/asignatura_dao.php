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
        $r["id_departamento_dos"], $r["creditos"], $r["docentes"]);

        $sentencia->close();
        $conn->close();

        return $asignatura;
    }

    /**
     * Devuelve un array con las asignaturas del curso indicado en la carrera indicada y que no estén en el listado de seleccionadas.
     * Devuelve NULL si no hay ninguna asignatura con ese $id 
     * 
     * @param $carrera - id de la carrera
     * @param $curso - curso en cuestion
     * @param $selected - listado de asignaturas a no devolver
     */
    public function getByCarreraCurso($carrera, $curso, $selected = array()){
        $conn = Connection::connect();

        $stmt = "SELECT * FROM `asignaturas` WHERE id_carrera = ? AND curso = ?;";       

        if (!($sentencia = $conn->prepare($stmt))) {
            echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
        }

        if (!$sentencia->bind_param("is", $carrera, $curso)) {
            echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
        }

        $sentencia->execute();

        $result = $sentencia->get_result();

        if($result->num_rows === 0)
            return NULL;

        $asignaturas = array();

        while($r = $result->fetch_assoc())
        {
            if(!in_array($r["id"], $selected)){
                $asignaturas[] = new Asignatura($r["id"], $r["id_carrera"], $r["itinerario"], $r["nombre"],
                                                $r["abreviatura"], $r["curso"], $r["id_departamento"],
                                                $r["id_departamento_dos"], $r["creditos"], $r['docentes']);
            }
        }
        $sentencia->close();
        $conn->close();

        return $asignaturas;
    }

    /**
     * Devuelve un array con las asignaturas del curso indicado en la carrera indicada y que no estén en el listado de seleccionadas.
     * Devuelve NULL si no hay ninguna asignatura con ese $id 
     * 
     * @param $carrera - id de la carrera
     * @param $curso - curso en cuestion
     * @param $itinerario - itinerario
     */
    public function getByCarreraCursoItinerario($carrera, $curso, $itinerario){
        $conn = Connection::connect();

        $stmt = "SELECT * FROM `asignaturas` WHERE id_carrera = ? AND curso = ? AND (itinerario IS NULL or itinerario = ?);";       

        if (!($sentencia = $conn->prepare($stmt))) {
            echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
        }

        if (!$sentencia->bind_param("isi", $carrera, $curso, $itinerario)) {
            echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
        }

        $sentencia->execute();

        $result = $sentencia->get_result();

        $sentencia->close();
        $conn->close();

        if($result->num_rows === 0)
            return NULL;

        $asignaturas = array();

        while($r = $result->fetch_assoc())
        {
            $asignaturas[] = new Asignatura($r["id"], $r["id_carrera"], $r["itinerario"], $r["nombre"],
                                            $r["abreviatura"], $r["curso"], $r["id_departamento"],
                                            $r["id_departamento_dos"], $r["creditos"], $r['docentes']);   
        }

        return $asignaturas;
    }

    public function getListado(){
        $conn = Connection::connect();

        $stmt = "SELECT * FROM `asignaturas` ORDER BY `nombre`;";

        if (!($sentencia = $conn->prepare($stmt))) {
            echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
        }

        $sentencia->execute();

        $result = $sentencia->get_result();

        $sentencia->close();
        $conn->close();

        $asignaturas = array();

        while($r = $result->fetch_assoc())
        {
            $asignaturas[] = new Asignatura($r["id"], $r["id_carrera"], $r["itinerario"], $r["nombre"],
                                            $r["abreviatura"], $r["curso"], $r["id_departamento"],
                                            $r["id_departamento_dos"], $r["creditos"], $r["docentes"]);
        }
        
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

        $id = $a->getId();
        $id_carrera = $a->getId_carrera();
        $itinerario = $a->getItinerario();
        $nombre = $a->getNombre();
        $abreviatura = $a->getAbreviatura();
        $curso = $a->getCurso();
        $id_departamento = $a->getId_departamento();
        $id_departamento_dos = $a->getId_departamento_dos();
        $creditos = $a->getCreditos();
        $docentes = $a->getDocentes();
        
        if($actualizar){
            if (!($sentencia = $conn->prepare("UPDATE `asignaturas` SET `id_carrera` = ?, `itinerario` = ?, `nombre` = ?, `abreviatura` = ?, `curso` = ?, `id_departamento` = ?, `id_departamento_dos` = ?, `creditos` = ?, `docentes` = ? WHERE `id`= ?;"))) {
                echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
            }

            if (!$sentencia->bind_param("iisssiidii", $id_carrera, $itinerario, $nombre, $abreviatura, $curso, $id_departamento, $id_departamento_dos, $creditos, $docentes, $id)) {
                echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
            }
            
            $sentencia->execute();
    
            $sentencia->close();
            $conn->close();
        }
        else{
            if (!($sentencia = $conn->prepare("INSERT INTO `asignaturas` (`id`, `id_carrera`, `itinerario`, `nombre`, `abreviatura`, `curso`, `id_departamento`, `id_departamento_dos`, `creditos`, `docentes`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?);"))) {
                echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
            }
    
            if (!$sentencia->bind_param("iiisssiidi", $id, $id_carrera, $itinerario, $nombre, $abreviatura, $curso, $id_departamento, $id_departamento_dos, $creditos, $docentes)) {
                echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
            }
            
            $sentencia->execute();
    
            $sentencia->close();
            $conn->close();
        }
    }

    /**
     * Elimina la asignatura correspondiente al $id proporcionado.
     * Devuelve true si ha habido exito en el borrado.          
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

    /**
     * Devuelve los cursos disponibles en la carrera dada
     * 
     * @param $id_carrera - ID de la carrera a consultar
     */
    public function getCursos($id_carrera){
        $conn = Connection::connect();
    
        if (!($sentencia = $conn->prepare("SELECT DISTINCT curso FROM `asignaturas` WHERE id_carrera = ? ORDER BY curso;"))) {
            echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
        }

        if (!$sentencia->bind_param("i", $id_carrera)) {
            echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
        }

        $sentencia->execute();

        $result = $sentencia->get_result();

        $cursos = array();

        while($r = $result->fetch_assoc())
        {
            $cursos[] = $r["curso"];
        }

        $sentencia->close();
        $conn->close();

        return $cursos;
    }

    /**
     * Comprueba que la asignatura pertenece a la carrera e itinerario indicados.
     */
    public function checkAsignatura($id_carrera, $id_itinerario, $curso, $id_asignatura){
        $conn = Connection::connect();

        $stmt = "SELECT * FROM `asignaturas` WHERE id_carrera = ? AND (itinerario IS NULL OR itinerario = ?) AND curso = ? AND id = ?;";       

        if (!($sentencia = $conn->prepare($stmt))) {
            echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
        }

        if (!$sentencia->bind_param("iiis", $id_carrera, $id_itinerario, $curso, $id_asignatura)) {
            echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
        }

        $sentencia->execute();

        $result = $sentencia->get_result();

        if($result->num_rows === 0)
            return NULL;

        $asignaturas = array();

        while($r = $result->fetch_assoc())
        {
            $asignaturas[] = new Asignatura($r["id"], $r["id_carrera"], $r["itinerario"], $r["nombre"],
                                            $r["abreviatura"], $r["curso"], $r["id_departamento"],
                                            $r["id_departamento_dos"], $r["creditos"], $r['docentes']);
        }
        $sentencia->close();
        $conn->close();

        return $asignaturas;
    }

    public function count(){        
        $conn = Connection::connect();
    
        if (!($sentencia = $conn->prepare("SELECT count(`id`) AS `cuenta` FROM `asignaturas`;"))) {
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


    // Se salta un poco la encapsulación, pero acelera las búsquedas y quita carga de trabajo al usuario
    public function getListadoFiltrado($idcarrera = null){

        $conn = Connection::connect();
        $idao = new Itinerario_dao();

        if($idcarrera == null){
            $stmt = "SELECT `asignaturas`.`id` as 'id' , `asignaturas`.`nombre` as 'nombre', `carreras`.`nombre` as 'carrera', `asignaturas`.`itinerario` as 'itinerario'
                FROM `asignaturas`, `carreras`
                WHERE `carreras`.`id` = `asignaturas`.`id_carrera`
                ORDER BY `asignaturas`.`nombre`;";
        }
        else{
            $stmt = "SELECT `asignaturas`.`id` as 'id' , `asignaturas`.`nombre` as 'nombre', `carreras`.`nombre` as 'carrera', `asignaturas`.`itinerario` as 'itinerario'
                FROM `asignaturas`, `carreras`
                WHERE `carreras`.`id` = `asignaturas`.`id_carrera` 
                AND `asignaturas`.`id_carrera` = ?
                ORDER BY `asignaturas`.`nombre`;";
        }

        if (!($sentencia = $conn->prepare($stmt))) {
            echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
        }

        if($idcarrera != null){
            if (!$sentencia->bind_param("i", $idcarrera)) {
                echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
            }
        }

        $sentencia->execute();

        $result = $sentencia->get_result();

        $sentencia->close();
        $conn->close();

        $asignaturas = array();

        while($r = $result->fetch_assoc())
        {
            $it = ($r['itinerario'] == NULL) ? "Común" : $idao->getById($r['itinerario'])->getNombre();
            $asignaturas[] = array('id' => $r['id'], 'nombre' => $r['nombre'], 'carrera' => $r['carrera'], 'itinerario' => $it);
        }
        
        return $asignaturas;
    }

    // Devuelve todas las asignaturas excepto las indicadas en $seleccion
    public function listadoExcepto($seleccion = array()){
        $conn = Connection::connect();

        $stmt = "SELECT `asignaturas`.id, `asignaturas`.nombre, `carreras`.`nombre` as 'carrera'
                 FROM `asignaturas`, `carreras`
                 WHERE  `asignaturas`.`id_carrera` = `carreras`.`id`
                 ORDER BY `carrera`, `nombre`;";       

        if (!($sentencia = $conn->prepare($stmt))) {
            echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
        }

        $sentencia->execute();

        $result = $sentencia->get_result();
        
        $sentencia->close();
        $conn->close();

        $asignaturas = array();

        while($r = $result->fetch_assoc())
        {
            if(!in_array($r['id'], $seleccion))
                $asignaturas[] = array('id' => $r["id"], 'nombre' => $r["nombre"], 'carrera' => $r["carrera"]);
        }

        return $asignaturas;
    }

    // Filtrado para la gestión de Clases
    public function filtrarAsignaturas($facultad = -1, $carrera = -1, $itinerario = -1){
        $conn = Connection::connect();

        if($facultad > -1 && $carrera == -1 && $itinerario == -1){            
            $stmt = "SELECT `asignaturas`.`id`, `asignaturas`.`nombre`
            FROM  `asignaturas`, `carreras`, `facultades`
            WHERE `asignaturas`.`id_carrera` = `carreras`.`id`
            AND	  (`carreras`.`id_facultad` = `facultades`.`id` 
                  OR `carreras`.`id_facultad_dg` = `facultades`.`id`)
            AND   `facultades`.`id` = ?;";

            if (!($sentencia = $conn->prepare($stmt))) {
                echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
            }
            
            if (!$sentencia->bind_param("i", $facultad)) {
                echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
            }
        }
        else if($facultad > -1 && $carrera > -1 && $itinerario == -1){
            $stmt = "SELECT `asignaturas`.`id`, `asignaturas`.`nombre`
            FROM  `asignaturas`, `carreras`, `facultades`
            WHERE `asignaturas`.`id_carrera` = `carreras`.`id`
            AND	  (`carreras`.`id_facultad` = `facultades`.`id` 
                  OR `carreras`.`id_facultad_dg` = `facultades`.`id`)
            AND   `facultades`.`id` = ?
            AND   `asignaturas`.`id_carrera` = ?;";
            
            if (!($sentencia = $conn->prepare($stmt))) {
                echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
            }
            
            if (!$sentencia->bind_param("ii", $facultad, $carrera)) {
                echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
            }
        }
        else if($facultad > -1 && $carrera > -1){
            if($itinerario == 'c'){
                $stmt = "SELECT `asignaturas`.`id`, `asignaturas`.`nombre`
                        FROM  `asignaturas`, `carreras`, `facultades`
                        WHERE `asignaturas`.`id_carrera` = `carreras`.`id`
                        AND	  (`carreras`.`id_facultad` = `facultades`.`id` 
                            OR `carreras`.`id_facultad_dg` = `facultades`.`id`)
                        AND   `facultades`.`id` = ?
                        AND   `asignaturas`.`id_carrera` = ?
                        AND   `asignaturas`.`itinerario` IS NULL;";

                if (!($sentencia = $conn->prepare($stmt))) {
                    echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
                }
                
                if (!$sentencia->bind_param("ii", $facultad, $carrera)) {
                    echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
                }
            }
            else{
                $stmt = "SELECT `asignaturas`.`id`, `asignaturas`.`nombre`
                        FROM  `asignaturas`, `carreras`, `facultades`
                        WHERE `asignaturas`.`id_carrera` = `carreras`.`id`
                        AND	  (`carreras`.`id_facultad` = `facultades`.`id` 
                            OR `carreras`.`id_facultad_dg` = `facultades`.`id`)
                        AND   `facultades`.`id` = ?
                        AND   `asignaturas`.`id_carrera` = ?
                        AND   `asignaturas`.`itinerario` = ?;";

                if (!($sentencia = $conn->prepare($stmt))) {
                    echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
                }
                
                if (!$sentencia->bind_param("iii", $facultad, $carrera, $itinerario)) {
                    echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
                }
            }
        }        

        $sentencia->execute();

        $result = $sentencia->get_result();

        $sentencia->close();
        $conn->close();

        $asignaturas = array();

        while($r = $result->fetch_assoc())
        {
            $asignaturas[] = array('id' => $r['id'], 'nombre' => $r['nombre']);
        }

        return $asignaturas;
    }

    public function calcula_creditos(){
        $conn = Connection::connect();

        $stmt = "SELECT `id_asignatura`, `grupo`, count(*) as 'bloques' FROM `clases`  GROUP BY `id_asignatura`, `grupo` ORDER BY `id_asignatura`;";       

        if (!($sentencia = $conn->prepare($stmt))) {
            echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
        }

        $sentencia->execute();

        $result = $sentencia->get_result();

        $anterior = '';

        while($r = $result->fetch_assoc())
        {
            if($anterior != $r['id_asignatura']){

                switch ($r['bloques']) {
                    case 6: $creditos = 4.5; break; 
                    case 8: $creditos = 6; break;
                    case 12: $creditos = 9; break;
                    case 16: $creditos = 12; break;
                    
                    default: $creditos = 0; break;
                }

                $stmt = "UPDATE `asignaturas` SET `creditos`= ? WHERE `id` = ?;";

                if (!($sentencia = $conn->prepare($stmt))) {
                    echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
                }

                if (!$sentencia->bind_param("di", $creditos, $r['id_asignatura'])) {
                    echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
                }
                $sentencia->execute();

                $anterior = $r['id_asignatura'];
            }
        }

        $sentencia->close();
        $conn->close();
    }
}

?>