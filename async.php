<?php

/**
 * Códigos de operaciones
 * 
 * 1 - Consultar itinerarios de una carrera
 * 2 - Comprobar si un itinerario existe
 * 
 */

require('core/includer.php');

if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
{    
   //$text = var_export($_POST, true);
   $op = $_POST['op'];

   switch ($op) 
   {
      case 1:
         muestra_itinerarios($_POST['idcarrera']);
         break;

      case 2:
         if(!existe_itinerario($_POST['idcarrera'], $_POST['iditinerario'])){
            echo json_encode("Error. Manipulación de datos detectada.");
         }
         else{
            echo json_encode("OK");
         }
      break;
      
      default:
         
         break;
   }   
}
else{
   die();
}

function muestra_itinerarios($id_carrera){
   if(is_numeric($id_carrera)){
      $i_dao = new Itinerario_dao();

      $itinerarios = $i_dao->getByIdCarrera($id_carrera);

      if(count($itinerarios) > 0){
         echo json_encode($itinerarios);
      }
      else{
         echo json_encode("Error. No se han encontrado itinerarios para la carrera seleccionada.");
      }

   }
   else{
      // Esto solo saltará si el usuario cambia los datos del formulario
      echo json_encode("Error. Manipulacion de datos detectada.");
   }
}

function existe_itinerario($id_carrera, $id_itinerario){
   if(is_numeric($id_carrera) && is_numeric($id_itinerario)){
      $i_dao = new Itinerario_dao();

      $itinerarios = $i_dao->checkItinerario($id_carrera, $id_itinerario);

      if(count($itinerarios) > 0){
         return true;
      }
      else{
         return false;
      }

   }
   else{
      return false;
   }
}



?>