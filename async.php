<?php


session_set_cookie_params((3600*24*30), "/", $_SERVER["SERVER_NAME"], 0, true);
session_start();

/**
 * Códigos de operaciones
 * 
 * 1 - Consultar itinerarios de una carrera
 * 2 - Comprobar si un itinerario existe
 * 
 */
require('common.php');
require('core/includer.php');

if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
{    
   //$text = var_export($_POST, true);
   $op = $_POST['op'];

   switch ($op) 
   {
      // Consultar itinerarios pertenecientes a la carrera indicada
      case 1:
         muestra_itinerarios($_POST['idcarrera']);
         break;

      // Primera comprobación (antes del envío)
      // Comprobar que el itinerario seleccionado está en la carrera seleccionada
      case 2:
         if(!existe_itinerario($_POST['idcarrera'], $_POST['iditinerario'])){
            echo json_encode("Error. Manipulación de datos detectada.");
         }
         else{
            echo json_encode("OK");
         }
      break;

      // Segunda comprobación (tras el envío)
      // Comprobar que el itinerario enviado está en la carrera enviada
      case 3:
         if(!existe_itinerario($_POST['idcarrera'], $_POST['iditinerario'])){
            echo json_encode("Error. Manipulación de datos detectada.");
         }
         else{
            $_SESSION['carrera'] = $_POST['idcarrera'];
            $_SESSION['itinerario'] = $_POST['iditinerario'];
            echo json_encode("OK");
         }
      break;

      // Consultar las asignaturas pertenecientes a la carrera y al curso indicado
      case 4:
         consultaAsignaturas($_POST['curso']);
      break;

      // Consultar si la asignatura pertenece a la carrera y al curso indicado
      case 5:
         existe_asignatura($_POST['curso'], $_POST['idasignatura']);
      break;

      // Recupera los datos de una facultad
      case 6:
         getFacultad($_POST['id']);
      break;
      
      default:
         die("Error");
         break;
   }   
}
else{
   header("Location: /");
   die("Error");
}

//INDEX

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
   die();
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
   die();
}


// ASISTENTE

function consultaAsignaturas($curso){
   $adao = new Asignatura_dao();
   $cursos = $adao->getByCarreraCurso($_SESSION['carrera'], $curso);
   echo json_encode($cursos);
   die();
}

function existe_asignatura($curso, $id_asignatura){
   if(is_numeric($curso) && is_numeric($id_asignatura)){
      $adao = new Asignatura_dao();

      $asignaturas = $adao->checkAsignatura($_SESSION['carrera'], $_SESSION['itinerario'], $curso,$id_asignatura);

      if(count($asignaturas) > 0){
         echo json_encode("OK");
      }
      else{
         echo json_encode("Error");
      }
   }
   else{
      return false;
   }
   die();
}

// GESTION

function getFacultad($id){
   $fdao = new Facultad_dao();
   echo json_encode($fdao->getById($id));
}


?>

