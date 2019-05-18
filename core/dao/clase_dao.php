<?php

class Clase_dao implements iDAO{

/*
    private $id;            // Long Integer 15 digitos  - Obligatorio
    private $id_asignatura; // Integer 10 digitos       - Obligatorio
    private $cuatrimestre;  // Integer 1 digito         - Obligatorio
    private $dia;           // String 1 char            - Obligatorio
    private $hora;          // Integer 1 digito         - Obligatorio
    private $grupo;         // String 10 chars          - Obligatorio
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

        $asignatura = new Clase($r["id"], $r["id_asignatura"], $r["carrera"], $r["cuatrimestre"], $r["dia"],
        $r["hora"], $r["grupo"]);

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
            $clases[] = new Clase($r["id"], $r["id_asignatura"], $r["id_carrera"], $r["cuatrimestre"], $r["dia"],
            $r["hora"], $r["grupo"]);
        }

        $sentencia->close();
        $conn->close();

        return $clases;
    }

    /**
     * Devuelve un array con las clases correspondientes a la asignatura dada,
     *  con un formato agrupado por grupos.
     * Devuelve NULL si no hay ninguna clase
     * 
     * @param $asignatura - asignatura de la clase a buscar
     */
    public function getByIdAsignaturaFormat($id){
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
            $clase = new Clase($r["id"], $r["id_asignatura"], $r["id_carrera"], $r["cuatrimestre"], $r["dia"],
            $r["hora"], $r["grupo"]);

            $clases[$clase->getgrupo()][] = $clase; //con esto en teoria tengo las clases agrupas por grupos
        }
        /*codigo original
        while($r = $result->fetch_assoc())
        {
            $clases[] = new Clase($r["id"], $r["id_asignatura"], $r["cuatrimestre"], $r["dia"],
            $r["hora"], $r["grupo"]);
        }*/
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
        $id_carrera = $clase->getId_carrera();
        $cuatrimestre = $clase->getCuatrimestre();
        $dia = $clase->getDia();
        $hora = $clase->getHora();
        $grupo = $clase->getGrupo();

        $actualizar = ($this->getById($clase->getId()) != NULL);

        if($actualizar){
            if (!($sentencia = $conn->prepare("UPDATE `clases` SET `id_asignatura` = ?, `cuatrimestre` = ?, `dia` = ?, `hora` = ?, `grupo` = ? WHERE `id` = ?"))) {
                echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
            }
            if (!$sentencia->bind_param("iisisi", $id_asignatura, $cuatrimestre, $dia, $hora, $grupo, $id)) {
                echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
            }
            $sentencia->execute();
            $sentencia->close();
            $conn->close();
        }
        else{
            if (!($sentencia = $conn->prepare("INSERT INTO `clases` (`id`, `id_asignatura`, `id_carrera`, `cuatrimestre`, `dia`, `hora`, `grupo`) VALUES (?, ?, ?, ?, ?, ?, ?);"))) {
                echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
            }
            if (!$sentencia->bind_param("iiiisis", $id, $id_asignatura, $id_carrera, $cuatrimestre, $dia, $hora, $grupo)) {
                echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
            }
            $sentencia->execute();
            $sentencia->close();
            $conn->close();
        }
    }

    /**
     * Recibe un conjunto de días y horas que corresponden a las clases del grupo indicado
     * en el cuatrimestre indicado, de la clase indicada
     * 
     * @param $gea - Código GEA de la asignatura
     * @param $cuatrimestre - Cuatrimestre al que pertenece el horario
     * @param $grupo - Grupo al que pertenece el horario
     * @param $horario - JSON con los días, horas y duraciones de las clases
     */
    public function procesaHorario($gea, $carrera, $cuatrimestre, $grupo, $horario){
        $dec_horario = json_decode($horario);

        foreach ($dec_horario as $nombre_dia => $duraciones) {
            $dia = $this->parse_dia($nombre_dia);

            $clase_hora = 0;
            foreach ($duraciones as $duracion) {
                if($duracion != null){
                    $this->add_multiple(new Clase(NULL, $gea, $carrera, $cuatrimestre, $dia, $clase_hora, $grupo), $duracion);
                }
                $clase_hora++;
            }
        }
    }

    private function add_multiple($clase, $duracion){
        $cldao = new Clase_dao();

        for ($i=0; $i < $duracion*2; $i++) { 
            $cldao->store($clase);
            $clase->add_una_hora();
        }

        unset($cldao);
    }

    private function parse_dia($dia){
        switch (strtolower($dia)) {
            case 'lunes':       return 'l';
            case 'martes':      return 'm';
            case 'miercoles':   return 'x';
            case 'jueves':      return 'j';
            case 'viernes':     return 'v';
            case 'sabado':      return 's';

            case 'l':           return 'lunes';
            case 'm':           return 'martes';
            case 'x':           return 'miercoles';
            case 'j':           return 'jueves';
            case 'v':           return 'viernes';
            case 's':           return 'sabado';
                
            default: break;
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

    public function removeByAsignatura($id_asignatura){
        $conn = Connection::connect();

        if (!($sentencia = $conn->prepare("DELETE FROM `clases` WHERE `id_asignatura` = ?;"))) {
            echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
        }

        if (!$sentencia->bind_param("i", $id_asignatura)) {
            echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
        }

        $sentencia->execute();

        $sentencia->close();
        $conn->close();
    }

    public function count(){        
        $conn = Connection::connect();
    
        if (!($sentencia = $conn->prepare("SELECT count(`id`) AS `cuenta` FROM `clases`;"))) {
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

    
    public function getListado(){
        $conn = Connection::connect();

        $adao = new Asignatura_dao();

        if (!($sentencia = $conn->prepare("SELECT `id_asignatura`, `cuatrimestre`, `grupo` FROM `clases` GROUP BY `id_asignatura` ORDER BY `id_asignatura`, `cuatrimestre`;"))) {
            echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
        }


        $sentencia->execute();

        $result = $sentencia->get_result();

        $sentencia->close();
        $conn->close();

        if($result->num_rows === 0)
            return NULL;

        while($r = $result->fetch_assoc())
        {
            $clases[] = array('id_asignatura' => $r['id_asignatura'],
                              'nombre'        => $adao->getById($r['id_asignatura'])->getNombre(),
                              'grupo'         => strtoupper($r['grupo']),
                              'cuatrimestre'  => $r['cuatrimestre']
                            );
            //$clases[] = new Clase($r["id"], $r["id_asignatura"], $r["cuatrimestre"], $r["dia"], $r["hora"], $r["grupo"]);
        }

        unset($adao);
        return $clases;
    }
}

?>