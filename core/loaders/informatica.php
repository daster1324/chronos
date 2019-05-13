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
 *  --  Desarrollo de Sistemas Interactivos (detección manual) (DSI)
 * 
 *  - Optativas del Itinerario de Información
 *  --  Software Corporativo (SC)
 *  --  Aplicaciones Web (AW)
 *  --  Ampliación de Bases de Datos (ABD)
 *  --  Auditoría Informática (AI)
 *  --  Redes y Seguridad (RyS)
 *  --  Desarrollo de Sistemas Interactivos (detección manual) (DSI)
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

//TODO: Terminar
function importar_horario_informatica($fichero){
        fgetcsv($fichero, 400, $delimiter=";"); //Lee las cabeceras

    while (!feof($fichero) ) {
        // Comenzar el bucle aquí
        //datos en crudo
        $linea = fgetcsv($fichero, 400, $delimiter=";");

        //Datos para crear la asignatura
        // Carrera
        $titulacion = parse_titulacion($linea[0]);

        // Itinerario (opcional) -> sacar de la tabla a mano -> hacer un switch con la relación


        $re = '/^(.+)\((.*)\)$/m';
        $asignatura = $linea[3];
        preg_match_all($re, $asignatura, $matches, PREG_SET_ORDER, 0);
        // Nombre de la asignatura
        $asignatura_nombre = $matches[0][1];

        // Abreviatura de la asignatura
        $asignatura_abreviatura = $matches[0][2];

        // Curso
        $curso = parse_curso($linea[1]);

        $re = '/^(.*)\/*(.*)*$/m';
        $departamentos = $linea[5];
        preg_match_all($re, $departamentos, $matches, PREG_SET_ORDER, 0);
        // Departamento 1
        $departamento_uno = $matches[0][1];

        // Departamento 2
        $departamento_dos = (isset($matches[0][2])) ? $matches[0][2] : null;      

        // Código Gea
        $gea = $linea[2];

        // Creditos -> Hay relación entre las horas de clase y los créditos 
        // (3h/semana = 4,5 creditos/cuatrimestre) (4h/semana = 6 creditos/cuatrimestre) 




        // Datos para crear la clase
        $grupo = $linea[4];
        
        $cuatrimestre = $linea[6];

        $docencia = parse_docencia($linea[7]);

        //Borrar
        $lineas[] = $linea;
    }
    echo '';
}

function parse_titulacion($input){
    switch (strtolower($input)) {
        case 'gc':  return 'Ingeniería de Computadores';
        case 'gi':  return 'Ingeniería Informática';
        case 'gc':  return 'Ingeniería del Software';
        case 'gdv': return 'Desarrollo de Videojuegos';
        
        default: return '';
    }
}

//TODO: Procesar para limpiar 'º' o para marcar como optativa (general o itinerario)
function parse_curso($input){

    return $input;
}

//TODO: Procesar para extraer los días y horas de clase
function parse_docencia($input){

    return $input;
}

//TODO: Ver si se puede hacer algo similar, pero para los profesores
function importar_docencia_informatica($fichero){
     
}

?>