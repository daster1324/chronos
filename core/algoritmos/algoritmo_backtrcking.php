<?php

/*  atributos clase asignatura
    private $id;                    // Integer 10 digitos   - Obligatorio
    private $id_carrera;            // Integer 2 digitos    - Obligatorio
    private $itinerario;            // String 5 chars       - Opcional
    private $nombre;                // String 150 chars     - Obligatorio
    private $abreviatura;           // String 10 chars      - Opcional
    private $curso;                 // Integer 1 digito     - Obligatorio ¿esto es el grupo?
    private $id_departamento;       // Integer 2 digitos    - Obligatorio
    private $id_departamento_dos;   // Integer 2 digitos    - Opcional
    private $creditos;              // Integer 2 digitos    - Obligatorio
*/
/*  atributos clase clases
    private $id;            // Long Integer 15 digitos  - Obligatorio
    private $id_asignatura; // Integer 10 digitos       - Obligatorio
    private $cuatrimestre;  // Integer 1 digito         - Obligatorio
    private $dia;           // String 1 char            - Obligatorio
    private $hora;          // Integer 1 digito         - Obligatorio
    private $grupo;         // String 10 chars          - Obligatorio
    private $edificio;      // Integer 1 digito         - Obligatorio
*/


class algoritmo_backtracking implements iAlgoritmo{

    private $_horario1; //arrays de 5X12
    private $_horario2;
    private $_ListadoAsignaturas; // array de asignaturas segun esta estructura
    /*$id;
    $nombre;
    $abreviatura;
    $creditos;*/
    public function __construct($disp,$listadoAsig){
       
        //inicializo los horarios
        if(strcasecmp($disp,"completa") == 0){
            $aux=array(9=>true,10=>true,11=>true,12=>true,13=>true,14=>true,15=>true,16=>true,17=>true,18=>true,19=>true,20=>true);
        }else if(strcasecmp($disp,"manana") == 0){
            $aux=array(9=>true,10=>true,11=>true,12=>true,13=>true,14=>true,15=>false,16=>false,17=>false,18=>false,19=>false,20=>false);
        }else if(strcasecmp($disp,"tarde") == 0){
            $aux=array(9=>false,10=>false,11=>false,12=>false,13=>false,14=>false,15=>true,16=>true,17=>true,18=>true,19=>true,20=>true);
        }
		var_dump($aux);
        $this->_horario1 = array("L"=>$aux,"M"=>$aux,"X"=>$aux,"J"=>$aux,"V"=>$aux);
        $this->_horario2 = array("L"=>$aux,"M"=>$aux,"X"=>$aux,"J"=>$aux,"V"=>$aux);
        $this->_ListadoAsignaturas = $listadoAsig;


    }

    //WARNING: SE PASA POR REFERENCIA POR EFICIENCIA(evitar la copia de arrays).NO EDITAR LAS VARIABLES
    //POSIBLE SOLUCION: ya que la idea es que se llama ejecuta, que este tenga los datos por copia pero a partir de aqui por referencia
    //asi se referencia a la copia.
    /**
     * metodo que ejecuta el algoritmo de backtracking para hayar la solucion al problema de horarios
     */
    public function ejecuta(){
        /*
            con &$asignaturas llamar a la base de datos para acabar con un array
             que cada elemento sea de tipo asignatura.
            TODO: obtener los datos de las asignaturas
            nos dan este paso y echo
        */
        $find = false;
        $it = 0;
        $sol = array();
        return algoritmo_backtracking::alg($it,$sol,$find);

    }

    /**
     * funcion principal del programa; funcion recursiva que haya el horario sin solapamiento
     * o devuelvo false.
     * return: false si no hay solucion, o una solucion valida
     */
    private function alg($it,&$sol,&$find){
        
		var_dump((count($this->_ListadoAsignaturas)));
        //caso base si ya no quedan mas asignaturas que añadir, tengo una solucion valida
		if($it >= (count($this->_ListadoAsignaturas))){			
            $find = True;
            return $sol;
        }
        
        
        /*1- obtener los datos de esta asignatura
        se coge la asignatura = $listAsignaturas[$it], con la informacion del id, 
        se llama a la base de datos, y se va creando una array de la forma
        [0=>[grupoXhora0,grupoXhora1,...],1=>[grupoZhora0,grupoZhora1,...],...]
        siendo grupoXhoraY se tipo clases, cada una con su grupo y hora
        $listclases = 
        */
        //$listclases = array();
        //$listclases = getByIdAsignatura($this->_ListadoAsignaturas[$it]);

        //prueba
        $listclases = array();
        $listclases= $this->_ListadoAsignaturas[$it];

			
        //2- para cada grupo de la asignatura
        foreach($listclases as $grupo){
            //intentar meter otro grupo
            //ver si se puede coger sin que haya solapamiento
            if (algoritmo_backtracking::check($grupo)){
                $solparcial = $sol;
                //actualizar la solucion parcial               
                algoritmo_backtracking::actualizar($solparcial,$grupo);
                //llamada recursiva con la sol parcial
                $sol=algoritmo_backtracking::alg($it+1,$solparcial,$find);
                
                //devuelvo la solucion y actualizo los horarios
                if($find){                    
                    return $sol;
                }
                algoritmo_backtracking::desactualizar($grupo);
            }
        }
        $find=False;//no hay solucion.
        return $find;

    }

    /**
     * funcion para chekear si se puede escoger este grupo de la asignatura,
     * es decir, no genera solapamiento.
     */
    private function check($asignatura){
        $sol = True;
		$i=0;
		
        while($i<count($asignatura) && $sol){ // se va viendo cada hora y se comprueba si esa hora esta disponible
			
            if ($asignatura[$i]->getcuatrimestre() == 1){
                $sol = $sol && $this->_horario1[$asignatura[$i]->getdia()][$asignatura[$i]->gethora()]; 
            }
            if ($asignatura[$i]->getcuatrimestre() == 2){
                $sol = $sol && $this->_horario2[$asignatura[$i]->getdia()][$asignatura[$i]->gethora()];
            }
            $i++;
        }
        return $sol;

    }

    /**
     * funcion para actualizar la sol y los horarios
     */
    private function actualizar(&$sol,&$clase){
        algoritmo_backtracking::tratarsol($sol,$clase);
        
		
        for($i = 0; $i<count($clase);$i++){
            if ($clase[$i]->getcuatrimestre() == 1){
                $this->_horario1[$clase[$i]->getdia()][$clase[$i]->gethora()] = False; 
            }
            if ($clase[$i]->getcuatrimestre() == 2){
                $this->_horario2[$clase[$i]->getdia()][$clase[$i]->gethora()] = False;
            }
        }
		
    }

    /**
     * funcion para revertir los horarios a antes de llamar a actualizar
     */
    private function desactualizar(&$clase){
        
        for($i = 0; $i<count($clase);$i++){
			
            if ($clase[$i]->getcuatrimestre() == 1){
                $this->_horario1[$clase[$i]->getdia()][$clase[$i]->gethora()] = true; 
            }
            if ($clase[$i]->getcuatrimestre() == 2){
                $this->_horario2[$clase[$i]->getdia()][$clase[$i]->gethora()] = true;
            }
        }
		
    }
    /**
     * funcion auxiliar para que la variable solucion tenga un formato agradable de usar.
     */
    private function tratarsol(&$sol,&$clase){
        $sol[]=$clase;
        return $sol;
    }



}
?>