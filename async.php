<?php

require('core/includer.php');

if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
{    
   //$text = var_export($_POST, true);
   $id_carrera = $_POST['idcarrera'];

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
      echo json_encode("Error. Manipulación de datos detectada.");
   }
}
else{
   die();
}


?>