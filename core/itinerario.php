<?php

class Itinerario implements JsonSerializable {
    
    private $id;                // Integer 2 digitos - Obligatorio
    private $id_carrera;        // Integer 2 digitos - Obligatorio
    private $nombre;            // String 150 chars  - Obligatorio

    public function __construct($id, $id_carrera, $nombre){
        $this->id = $id;
        $this->id_carrera = $id_carrera;
        $this->nombre = $nombre;
    }

    
    /**
     *  GETTERS
     */
    public function getId(){
        return $this->id;
    }

    public function getIdCarrera(){
        return $this->id_carrera;
    }

    public function getNombre(){
        return $this->nombre;
    }
    

    /**
     *  SETTERS
     */
    public function setId($id){
        $this->id = $id;
    }

    public function setIdCarrera($id_carrera){
        $this->id_carrera = $id_carrera;
    }

    public function setNombre($nombre){
        $this->nombre = $nombre;
    }

    /**
     *  SERIALIZE
     */
    public function jsonSerialize() {
        return [
            'id' => $this->id,
            'id_carrera' => $this->id_carrera,
            'nombre' => $this->nombre
        ];
    }
}

?>