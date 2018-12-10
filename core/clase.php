<?php

class Clase{

    private $id;            // Long Integer 15 digitos  - Obligatorio
    private $id_asignatura; // Integer 10 digitos       - Obligatorio
    private $cuatrimestre;  // Integer 1 digito         - Obligatorio
    private $dia;           // String 1 char            - Obligatorio
    private $hora;          // Integer 1 digito         - Obligatorio
    private $grupo;         // String 10 chars          - Obligatorio
    private $edificio;      // Integer 1 digito         - Obligatorio

    public function __construct($id, $id_asignatura, $cuatrimestre, $dia, $hora, $grupo, $edificio){
        $this->id = $id;
        $this->id_asignatura = $id_asignatura;
        $this->cuatrimestre = $cuatrimestre;
        $this->dia = $dia;
        $this->hora = $hora;
        $this->grupo = $grupo;
        $this->edificio = $edificio;
    }


    /**
     *  GETTERS
     */

    public function getId(){
        return $this->id;
    }
    public function getId_asignatura(){
        return $this->id_asignatura;
    }
    public function getCuatrimestre(){
        return $this->cuatrimestre;
    }
    public function getDia(){
        return $this->dia;
    }
    public function getHora(){
        return $this->hora;
    }
    public function getGrupo(){
        return $this->grupo;
    }
    public function getEdificio(){
        return $this->edificio;
    }


    /**
     *  SETTERS
     */

    public function setId($id){
        $this->id = $id;
    }
    public function setId_asignatura($id_asignatura){
        $this->id_asignatura = $id_asignatura;
    }
    public function setCuatrimestre($cuatrimestre){
        $this->cuatrimestre = $cuatrimestre;
    }
    public function setDia($dia){
        $this->dia = $dia;
    }
    public function setHora($hora){
        $this->hora = $hora;
    }
    public function setGrupo    ($grupo){
        $this->grupo = $grupo;
    }
    public function setEdificio($edificio){
        $this->edificio = $edificio;
    }

}

?>