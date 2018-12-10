<?php

class Departamento_dao implements iDAO{
    
/*
    private $id;            // Integer 2 digitos - Obligatorio
    private $nombre;        // String 100 chars  - Obligatorio
    private $id_facultad;   // Integer 2 digitos - Obligatorio
*/

    private $departamento;

    public function __construct(){
        $this->departamento = NULL;
    }

    /**
     * Devuelve un objeto con los datos de la departamento correspondiente al $id.
     * Devuelve NULL si no hay ninguna departamento con ese $id 
     * 
     * @param $id - id del departamento a buscar
     */
    public function getById($id){

    }

    /**
     * Guarda en la base de datos la departamento proporcionada
     * En caso de que ya exista, se actualizan los datos
     */
    public function store($departamento){

    }

    /**
     * Elimina la departamento correspondiente al $id proporcionado
     * Devuelve true si ha habido éxito en el borrado.
     * Devuelve false si no se ha podido borrar.
     */
    public function remove($id){
        
    }
}

?>