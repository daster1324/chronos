<?php

class Docente implements JsonSerializable {
    
    private $id;            // Integer 4 digitos - Obligatorio
    private $usuario;       // String 32 chars   - Obligatorio
    private $departamento;  // Integer 3 digitos - Obligatorio
    private $nombre;        // String 64 chars   - Obligatorio
    private $preferencias;  // String 100 chars  - Opcional
    private $orden;         // Integer 2 digitos - Opcional
    private $pass;          // String 64 chars   - Obligatorio

    public function __construct($id, $nombre, $departamento, $preferencias = "", $orden = 0, $usuario = "", $pass = ""){
        $this->id = $id;
        $this->usuario = $usuario;
        $this->nombre = $nombre;
        $this->departamento = $departamento;
        $this->preferencias = $preferencias;
        $this->orden = $orden;
        $this->pass = $pass;
    }
    
    /**
     *  GETTERS
     */
    public function getId(){
        return $this->id;
    }

    public function getUsuario(){
        return $this->usuario;
    }

    public function getNombre(){
        return $this->nombre;
    }

    public function getDepartamento(){
        return $this->departamento;
    }

    public function getPreferencias(){
        return $this->preferencias;
    }

    public function getPassword(){
        return $this->pass;
    }

    public function getOrden(){
        return $this->orden;
    }

    /**
     *  SETTERS
     */
    public function setId($id){
        $this->id = $id;
    }

    public function setUsuario($usuario){
        $this->usuario = $usuario;
    }

    public function setNombre($nombre){
        $this->nombre = $nombre;
    }

    public function setDepartamento($departamento){
        $this->departamento = $departamento;
    }

    public function setPreferencias($preferencias){
        $this->preferencias = $preferencias;
    }

    public function setPassword($pass){
        $this->pass = $pass;
    }

    public function setOrden($orden){
        $this->orden = orden;
    }

    /**
     *  SERIALIZE
     */
    public function jsonSerialize() {
        return [
            'id' => $this->id,
            'usuario' => $this->usuario,
            'nombre' => $this->nombre,
            'departamento' => $this->departamento,
            'preferencias' => $this->preferencias,
            'orden' => $this->orden
        ];
    }
}

?>