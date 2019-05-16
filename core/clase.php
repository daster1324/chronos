<?php

class Clase implements JsonSerializable {

    private $id;            // Long Integer 15 digitos  - Obligatorio
    private $id_asignatura; // Integer 10 digitos       - Obligatorio
    private $id_carrera;    // Integer 2 digitos        - Obligatorio
    private $cuatrimestre;  // Integer 1 digito         - Obligatorio
    private $dia;           // String 1 char            - Obligatorio
    private $hora;          // Integer 1 digito         - Obligatorio
    private $grupo;         // String 10 chars          - Obligatorio

    public function __construct($id, $id_asignatura, $id_carrera, $cuatrimestre, $dia, $hora, $grupo){
        $this->id = $id;
        $this->id_asignatura = $id_asignatura;
        $this->id_carrera = $id_carrera;
        $this->cuatrimestre = $cuatrimestre;
        $this->dia = $dia;
        $this->hora = $hora;
        $this->grupo = $grupo;
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
    public function getId_carrera(){
        return $this->id_carrera;
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


    /**
     *  SETTERS
     */

    public function setId($id){
        $this->id = $id;
    }
    public function setId_asignatura($id_asignatura){
        $this->id_asignatura = $id_asignatura;
    }
    public function setId_carrera($id_carrera){
        $this->id_carrera = $id_carrera;
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

    public function add_una_hora(){
        $this->hora++;
    }

    /**
     *  SERIALIZE
     */
    public function jsonSerialize() {
        return [
            'id' => $this->id,
            'id_asignatura' => $this->id_asignatura,
            'id_carrera' => $this->id_carrera,
            'cuatrimestre' => $this->cuatrimestre,
            'dia' => $this->dia,
            'hora' => $this->hora,
            'grupo' => $this->grupo
        ];
    }

}

?>