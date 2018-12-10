<?php

class Carrera_dao implements iDAO{

/*
    private $id;             // Integer 2 digitos - Obligatorio
    private $nombre;         // String 150 chars  - Obligatorio
    private $id_facultad;    // Integer 2 digitos - Obligatorio
    private $id_facultad_dg; // Integer 2 digitos - Opcional
*/

    private $carrera;

    public function __construct(){
        $this->carrera = NULL;
    }

    /**
     * Devuelve un objeto con los datos de la carrera correspondiente al $id.
     * Devuelve NULL si no hay ninguna carrera con ese $id 
     * 
     * @param $id - id de la carrera a buscar
     */
    public function getById($id){

    }

    /**
     * Guarda en la base de datos la carrera proporcionada
     * En caso de que ya exista, se actualizan los datos
     */
    public function store($carrera){

    }

    /**
     * Elimina la carrera correspondiente al $id proporcionado
     * Devuelve true si ha habido éxito en el borrado.
     * Devuelve false si no se ha podido borrar.
     */
    public function remove($id){
        
    }
}

?>