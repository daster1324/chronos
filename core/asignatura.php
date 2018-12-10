<?php

class Asignatura{

    /* Nota: el id de la asignatura no es un autoincremento,
             es el código que le asigna la facultad.
        Ejemplo:
            TFG => 803310 en GII
                   803363 en GIS
                   803244 en GIC
                   805333 en GDV
    */
    
    private $id;                    // Integer 10 digitos   - Obligatorio
    private $id_carrera;            // Integer 2 digitos    - Obligatorio
    private $itinerario;            // String 5 chars       - Opcional
    private $nombre;                // String 150 chars     - Obligatorio
    private $abreviatura;           // String 10 chars      - Opcional
    private $curso;                 // String 1 char        - Obligatorio
    private $id_departamento;       // Integer 2 digitos    - Obligatorio
    private $id_departamento_dos;   // Integer 2 digitos    - Opcional
    private $creditos;              // Integer 2 digitos    - Obligatorio

    public function __construct($id, $id_carrera, $itinerario = NULL, $nombre,
                                 $abreviatura = NULL, $curso, $id_departamento,
                                 $id_departamento_dos = NULL, $creditos){
        $this->id = $id;
        $this->id_carrera = $id_carrera;
        $this->itinerario = $itinerario;
        $this->nombre = $nombre;
        $this->abreviatura = $abreviatura;
        $this->curso = $curso;
        $this->id_departamento = $id_departamento;
        $this->id_departamento_dos = $id_departamento_dos;
        $this->creditos = $creditos;
    }

    /**
     *  GETTERS
     */

    public function getId(){
        return $this->id;
    }

    public function getId_carrera(){
        return $this->id_carrera;
    }

    public function getItinerario(){
        return $this->itinerario;
    }

    public function getNombre(){
        return $this->nombre;
    }

    public function getAbreviatura(){
        return $this->abreviatura;
    }

    public function getCurso(){
        return $this->curso;
    }

    public function getId_departamento(){
        return $this->id_departamento;
    }

    public function getId_departamento_dos(){
        return $this->id_departamento_dos;
    }

    public function getCreditos(){
        return $this->creditos;
    }


    /**
     *  SETTERS
     */
    public function setId($id){
        $this->id = $id;
    }

    public function setId_carrera($id_carrera){
        $this->id_carrera = $id_carrera;
    }

    public function setItinerario($itinerario){
        $this->itinerario = $itinerario;
    }

    public function setNombre($nombre){
        $this->nombre = $nombre;
    }

    public function setAbreviatura($abreviatura){
        $this->abreviatura = $abreviatura;
    }

    public function setCurso($curso){
        $this->curso = $curso;
    }

    public function setId_departamento($id_departamento)    {
        $this->id_departamento = $id_departamento;
    }

    public function setId_departamento_dos($id_departamento_dos){
        $this->id_departamento_dos = $id_departamento_dos;
    }

    public function setCreditos($creditos){
        $this->creditos = $creditos;
    }
}

?>