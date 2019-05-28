<?php

class Algoritmo_backtracking{
    private $_horario1; //arrays de 5X12
    private $_horario2;
    private $_ListadoAsignaturas; // array de asignaturas segun esta estructura
    
    public function __construct($disp,$listadoAsig,$carrera){
        $aux = array();
        if(strcasecmp($disp,"allday") == 0){
            $aux = array_fill(1,25,true);
        }else if(strcasecmp($disp,"morning") == 0){
            $aux = array_fill(0,13,true);
            $aux2 = array_fill(0,13,false);
            $aux= array_merge($aux,$aux2);
        }else if(strcasecmp($disp,"afternoon") == 0){
            $aux = array_fill(0,13,false);
            $aux2 = array_fill(0,13,true);
            $aux= array_merge($aux,$aux2);
        }
		
        $this->_horario1 = array("L"=>$aux,"M"=>$aux,"X"=>$aux,"J"=>$aux,"V"=>$aux);
        $this->_horario2 = array("L"=>$aux,"M"=>$aux,"X"=>$aux,"J"=>$aux,"V"=>$aux);

        $cDao= new Clase_dao();

        foreach($listadoAsig as $asig){
            $this->_ListadoAsignaturas[]=$cDao->getByIdAsignaturaFormat($asig["id"],$carrera);
        }

        unset($cDao);
    }
    
    /**
     * metodo que ejecuta el algoritmo de backtracking para hayar la solucion al problema de horarios
     */
    public function ejecuta(){
        $find = false;
        $it = 0;
        $sol = array();
        $toReturn = $this->alg($it,$sol,$find);
        return $toReturn;
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
            if ($this->check($grupo)){
                $solparcial = $sol;
                //actualizar la solucion parcial               
                $this->actualizar($solparcial,$grupo);
                //llamada recursiva con la sol parcial
                $sol=$this->alg($it+1,$solparcial,$find);
                
                //devuelvo la solucion y actualizo los horarios
                if($find){                    
                    return $sol;
                }
                $this->desactualizar($grupo);
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
        $this->tratarsol($sol,$clase);
        
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
