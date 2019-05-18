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
class Algoritmo_backtracking{
    private $_horario1; //arrays de 5X12
    private $_horario2;
    private $_ListadoAsignaturas; // array de asignaturas segun esta estructura
    /*$id;
    $nombre;
    $abreviatura;
    $creditos;*/
    public function __construct($disp,$listadoAsig){
        $aux = array();
        if(strcasecmp($disp,"allday") == 0){
            $aux = array_fill(1,25,true);
        }else if(strcasecmp($disp,"morning") == 0){
            $aux = array_fill(0,13,true);
            $aux2 = array_fill(0,13,false);
            $aux= array_merge($aux,$aux2);
        }else if(strcasecmp($disp,"tarde") == 0){
            $aux = array_fill(0,13,false);
            $aux = array_fill(0,13,true);
            $aux= array_merge($aux,$aux2);
        }
		
        $this->_horario1 = array("L"=>$aux,"M"=>$aux,"X"=>$aux,"J"=>$aux,"V"=>$aux);
        $this->_horario2 = array("L"=>$aux,"M"=>$aux,"X"=>$aux,"J"=>$aux,"V"=>$aux);
        //$this->_ListadoAsignaturas = $listadoAsig;

        $cDao= new Clase_dao();

        foreach($listadoAsig as $asig){
            $this->_ListadoAsignaturas[]=$cDao->getByIdAsignaturaFormat($asig["id"]);
        }
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
        
		
        //caso base si ya no quedan mas asignaturas que añadir, tengo una solucion valida
		if($it >= (count($this->_ListadoAsignaturas))){			
            $find = True;
            return $sol;
        }
        
        
      
        
			
        //2- para cada grupo de la asignatura
        foreach($this->_ListadoAsignaturas[$it] as $grupo){
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

		foreach($asignatura as $a){
            if ($a->getcuatrimestre() == 1){
                $sol = $sol && $this->_horario1[$a->getdia()][$a->gethora()]; 
            }
            if ($a->getcuatrimestre() == 2){
                $sol = $sol && $this->_horario2[$a->getdia()][$a->gethora()];
            }
            if(!$sol) break;
        }

        return $sol;
    }
    /**
     * funcion para actualizar la sol y los horarios
     */
    private function actualizar(&$sol,&$clase){
        algoritmo_backtracking::tratarsol($sol,$clase);
        
		foreach($clase as $c){
            if ($c->getcuatrimestre() == 1){
                $this->_horario1[$c->getdia()][$c->gethora()] = False; 
            }
            if ($c->getcuatrimestre() == 2){
                $this->_horario2[$c->getdia()][$c->gethora()] = False;
            }
        }

		
    }
    /**
     * funcion para revertir los horarios a antes de llamar a actualizar
     */
    private function desactualizar(&$clase){
        
        foreach($clase as $c){
            if ($c->getcuatrimestre() == 1){
                $this->_horario1[$c->getdia()][$c->gethora()] = true; 
            }
            if ($c->getcuatrimestre() == 2){
                $this->_horario2[$c->getdia()][$c->gethora()] = true;
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