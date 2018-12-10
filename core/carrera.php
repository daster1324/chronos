<?php

class Carrera{
    private $id;             // Integer 2 digitos - Obligatorio
    private $nombre;         // String 150 chars  - Obligatorio
    private $id_facultad;    // Integer 2 digitos - Obligatorio
    private $id_facultad_dg; // Integer 2 digitos - Opcional

    public function __construct($id, $nombre, $id_facultad, $id_facultad_dg){
        $this->id = $id;
        $this->nombre = $nombre;
        $this->id_facultad = $id_facultad;
        $this->id_facultad_dg = $id_facultad_dg;
    }


    /**
     *  GETTERS
     */

    public function getId(){
        return $this->id;
    }

    public function getNombre(){
        return $this->nombre;
    }

    public function getId_facultad(){
        return $this->id_facultad;
    }

    public function getId_facultad_dg(){
        return $this->id_facultad_dg;
    }
    

    /**
     *  SETTERS
     */
    
    public function setId($id){
        $this->id = $id;
    }

    public function setNombre($nombre){
        $this->nombre = $nombre;
    }

    public function setId_facultad($id_facultad){
        $this->id_facultad = $id_facultad;
    }

    public function setId_facultad_dg($id_facultad_dg){
        $this->id_facultad_dg = $id_facultad_dg;
    }

}

?>