<?php

/**
 *  Datos a tener en cuenta
 * 
 *  - Optativas del Itinerario de Computación
 *  --  Fundamentos de los Lenguajes Informáticos (FLI)
 *  --  Programación Concurrente (PC)
 *  --  Programación Declarativa (PD)
 *  --  Métodos Algorítmicos en Resolución de Problemas (MAR)
 *  --  Inteligencia Artificial (IA)
 *  --  Procesadores de Lenguajes (PL)
 *  --  Desarrollo de Sistemas Interactivos (detección manual) (DSI) 803280
 * 
 *  - Optativas del Itinerario de Información
 *  --  Software Corporativo (SC)
 *  --  Aplicaciones Web (AW)
 *  --  Ampliación de Bases de Datos (ABD)
 *  --  Auditoría Informática (AI)
 *  --  Redes y Seguridad (RyS)
 *  --  Desarrollo de Sistemas Interactivos (detección manual) (DSI) 803287
 *  --  Evaluación de Configuraciones (ECO)
 * 
 */

class Linea{
    private $titulacion;    // Carrera (GC, GI, GS)
    private $curso;         // Curso (1º, 2º, Optativas 3º y 4º y Optativas generales 3º y 4º (optativas, común), Optativas itinerario 3º y Optativas itinerario 4º (optativas de itinerario))
    private $gea;           // Gea
    private $asignatura;    // Nombre (abreviatura)
    private $grupo;         // A, B .. SIEMPRE es una letra
    private $departamento;  // siglas
    private $periodo;       // Cuatrimestre
    private $docencia;      // Dias y horas de clase. Ejemplo: 'Aula 9=> L:9-10:50 | M:10-10:50 | V:11-11:50'

    public function __construct($titulacion, $curso, $gea, $asignatura, $grupo, $departamento, $periodo, $docencia){
        $this->titulacion = $titulacion;
        $this->curso = $curso;
        $this->gea = $gea;
        $this->asignatura = $asignatura;
        $this->grupo = $grupo;
        $this->departamento = $departamento;
        $this->periodo = $periodo;
        $this->docencia  = $docencia;
    }

    public function getTitulacion(){
        return $this->titulacion;
    }

    public function getCurso(){
        return $this->curso;
    }

    public function getGea(){
        return $this->gea;
    }

    public function getAsignatura(){
        return $this->asignatura;
    }

    public function getGrupo(){
        return $this->grupo;
    }

    public function getDepartamento(){
        return $this->departamento;
    }

    public function getPeriodo(){
        return $this->periodo;
    }

    public function getDocencia(){
        return $this->docencia;
    }


    public function setTitulacion($titulacion){
        $this->titulacion = $titulacion;
    }

    public function setCurso($curso){
        $this->curso = $curso;
    }

    public function setGea($gea){
        $this->gea = $gea;
    }

    public function setAsignatura($asignatura){
        $this->asignatura = $asignatura;
    }

    public function setGrupo($grupo){
        $this->grupo = $grupo;
    }

    public function setDepartamento($departamento){
        $this->departamento = $departamento;
    }

    public function setPeriodo($periodo){
        $this->periodo = $periodo;
    }

    public function setDocencia($docencia){
        $this->docencia = $docencia;
    }
}

function test_informatica(){
   echo '';
}

/**
 * Devuelve el ID de una carrera a partir de sus siglas
 */
function get_id_carrera($siglas){
    $cdao = new Carrera_dao();

    switch (strtolower($siglas)) {
        case 'gi': return $cdao->getIdByName("Ingeniería Informática");
        case 'gs': return $cdao->getIdByName("Ingeniería del Software");
        case 'gc': return $cdao->getIdByName("Ingeniería de Computadores");
        case 'gdv': return $cdao->getIdByName("Desarrollo de Videojuegos");

        default: break;
    }

    unset($cdao);
}

/**
 * Hace uso de la carrera, la asignatura y el listado manual para obtener el ID del itinerario
 */
function get_itinerario($id_carrera, $id_asignatura){
    $idao = new Itinerario_dao();
    $id_itinerario = NULL;

    switch ($id_asignatura) {
        case '803274': 
        case '803275': 
        case '803276': 
        case '803277': 
        case '803278': 
        case '803279': 
        case '803280': 
            $idao->busca("Computación", $id_carrera, $id_itinerario);
            break;
        
        case '803281':  
        case '803282': 
        case '803283': 
        case '803284': 
        case '803285': 
        case '803286': 
        case '803287':
            $idao->busca("%Información", $id_carrera, $id_itinerario);
            break;
        
        default: break;
    }
    unset($idao);
    return $id_itinerario;
}

/**
 *  Convierte el texto en el dato correspondiente
 */
function parse_curso($input){
    switch ($input) {
        case '1º':
        case '2º':
        case '3º':
        case '4º':
        case '5º':
        case '6º':
            return substr($input, 0, 1);
        
        case 'Optativas itinerario 3º':
            return '3';

        case 'Optativas itinerario 4º':
            return '4';
            
        default:
            return '0';
    }
}

/**
 * Obtiene el ID de un departamento a partir de sus siglas
 */
function get_departamento($input){
    $ddao = new Departamento_dao();
    
    $toReturn = $ddao->getIdBySiglas($input);

    unset($ddao);

    return $toReturn;
}

//TODO: Terminar
function importar_horario_informatica($fichero){
    fgetcsv($fichero, 400, $delimiter=";"); //Lee las cabeceras

    $clases = array();

    $adao  = new Asignatura_dao();
    $cldao = new Clase_dao();
    while (($linea = fgetcsv($fichero, 400, $delimiter=";")) !== FALSE) {
          ///////////               /////////
         // Datos para crear la asignatura /
        /////////               ///////////
        $id_asignatura = $linea[2];
        $id_carrera = get_id_carrera($linea[0]);
        $id_itinerario = get_itinerario($id_carrera, $id_asignatura);

        // Separa el nombre de la asignatura de su abreviatura
        $re = '/^(.+) \((.*)\)$/m';
        $asignatura = $linea[3];
        preg_match_all($re, $asignatura, $matches, PREG_SET_ORDER, 0);

        $asignatura_nombre = $matches[0][1];
        $asignatura_abreviatura = $matches[0][2];

        $curso = parse_curso($linea[1]);

        // Separa los nombres de los departamentos
        $re = '/^([a-zA-Z\.]+)|([a-zA-Z\.]+)([a-zA-Z\.]*)$/m';
        $departamentos = $linea[5];
        preg_match_all($re, $departamentos, $matches, PREG_SET_ORDER, 0);

        $departamento_uno = get_departamento($matches[0][0]);
        $departamento_dos = (isset($matches[1][0])) ? get_departamento($matches[1][0]) : null;      
        
        if($adao->getById($id_asignatura) == NULL){
            $adao->store(new Asignatura($id_asignatura, $id_carrera, $id_itinerario, $asignatura_nombre, $asignatura_abreviatura, $curso, $departamento_uno, $departamento_dos, 0, 0));
        }
        
          ///////////          /////////
         // Datos para crear la clase /
        /////////          ///////////
        $grupo = strtolower($linea[4]);
        $cuatrimestre = $linea[6];
       
        // dh -> Dias-Horas -- Separa los días de las horas
        $dh = '/((\w*):[0-2]*[0-9]-[0-2]*[0-9]:[0-5][0-9])/m';
        preg_match_all($dh, $linea[7], $clases, PREG_SET_ORDER, 0);

        foreach ($clases as $clase) {
            // hs -> Hour-Splitter -- Separa las horas para poder procesarlas
            $hs = '/\w*:([0-2]*[0-9])-([0-2]*[0-9]):([0-5][0-9])/m';
            preg_match_all($hs, $clase[0], $horas, PREG_SET_ORDER, 0);
            $duracion = ($horas[0][3] == 50) ? $horas[0][2] - $horas[0][1] + 1 : $horas[0][2] - $horas[0][1];

            // ls -> Letter-Splitter -- Separa las letras
            $ls = '/([a-zA-Z])/m';
            $dias = $clase[2];
            preg_match_all($ls, $dias, $dias_ls, PREG_SET_ORDER, 0);

            foreach ($dias_ls as $dia) {
                for ($i=0; $i < $duracion*2; $i++) { 
                    $cldao->store(new Clase('', $id_asignatura, $cuatrimestre, $dia[0], $horas[0][1]-8+$i, $grupo));
                }
            }
        }
    }
    $adao->calcula_creditos();

    unset($adao);
    unset($cldao);
}


//TODO: Ver si se puede hacer algo similar, pero para los profesores
function importar_docencia_informatica($fichero){
     
}

?>