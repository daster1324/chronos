<?php

class Facultad{
    
    private $id;        // Integer 2 digitos - Obligatorio
    private $nombre;    // String 100 chars  - Obligatorio
    private $campus;    // String 100 chars  - Optional

    public function __construct($id, $nombre, $campus = NULL){
        $this->id = $id;
        $this->nombre = $nombre;
        $this->campus = $campus;
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

    public function getCampus(){
        return $this->campus;
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

    public function setCampus($campus){
        $this->campus = $campus;
    }

    /**
     *  SERIALIZE
     */
    public function jsonSerialize() {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'campus' => $this->campus
        ];
    }
}

?>