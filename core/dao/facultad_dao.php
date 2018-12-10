<?php

class Facultad_dao implements iDAO{
   
/*
    private $id;        // Integer 2 digitos - Obligatorio
    private $nombre;    // String 100 chars  - Obligatorio
    private $campus;    // String 100 chars  - Optional
*/

    private $facultad;

    public function __construct(){
        $this->facultad = NULL;
    }

    /**
     * Devuelve un objeto con los datos de la facultad correspondiente al $id.
     * Devuelve NULL si no hay ninguna facultad con ese $id 
     * 
     * @param $id - id de la facultad a buscar
     */
    public function getById($id){

    }

    /**
     * Guarda en la base de datos la facultad proporcionada
     * En caso de que ya exista, se actualizan los datos
     */
    public function store($facultad){

    }

    /**
     * Elimina la facultad correspondiente al $id proporcionado
     * Devuelve true si ha habido éxito en el borrado.
     * Devuelve false si no se ha podido borrar.
     */
    public function remove($id){
        
    }
    
}




?>