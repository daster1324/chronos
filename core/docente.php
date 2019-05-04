<?php

class Docente implements JsonSerializable {
    
    private $id;            // Integer 4 digitos - Obligatorio
    private $usuario;       // String 32 chars   - Obligatorio
    private $departamento;  // Integer 3 digitos - Obligatorio
    private $preferencias;  // String 100 chars  - Opcional
    private $pass;          // String 64 chars   - Obligatorio

    public function __construct($id, $usuario, $departamento, $preferencias = "", $pass = ""){
        $this->id = $id;
        $this->usuario = $usuario;
        $this->departamento = $departamento;
        $this->preferencias = $preferencias;
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

    public function getDepartamento(){
        return $this->departamento;
    }

    public function getPreferencias(){
        return $this->preferencias;
    }

    public function getPassword(){
        return $this->pass;
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

    public function setDepartamento($departamento){
        $this->departamento = $departamento;
    }

    public function setPreferencias($preferencias){
        $this->preferencias = $preferencias;
    }

    public function setPassword($pass){
        $this->pass = $pass;
    }

    /**
     *  SERIALIZE
     */
    public function jsonSerialize() {
        return [
            'id' => $this->id,
            'usuario' => $this->usuario,
            'departamento' => $this->departamento,
            'preferencias' => $this->preferencias,
        ];
    }
}

?>