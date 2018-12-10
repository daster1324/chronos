<?php

class Departamento{

    private $id;            // Integer 2 digitos - Obligatorio
    private $nombre;        // String 100 chars  - Obligatorio
    private $id_facultad;   // Integer 2 digitos - Obligatorio

    public function __construct($id, $nombre, $id_facultad){
        $this->id = $id;
        $this->nombre = $nombre;
        $this->id_facultad = $id_facultad;
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

}

?>