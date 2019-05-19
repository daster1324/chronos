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
         $itinerario = (isset($_POST['iditinerario'])) ? $_POST['iditinerario'] : NULL;

         if(!existe_itinerario($_POST['idcarrera'], $itinerario)){
            echo json_encode("Error. Manipulación de datos detectada.");
         }
         else{
            echo json_encode("OK");
         }
      break;

      // Segunda comprobación (tras el envío)
      // Comprobar que el itinerario enviado está en la carrera enviada
      case 3:
         $itinerario = (isset($_POST['iditinerario'])) ? $_POST['iditinerario'] : NULL;

         if(!existe_itinerario($_POST['idcarrera'], $itinerario)){
            echo json_encode("Error. Manipulación de datos detectada.");
         }
         else{
            $_SESSION['carrera'] = $_POST['idcarrera'];
            $_SESSION['itinerario'] = $itinerario;
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

      // Recupera todas las facultades menos la indicada
      case 7:
         listarFacultadesDG($_POST['id']);
      break;

      // Recupera los datos de una carrera
      case 8:
         getCarrera($_POST['id']);
      break;

      // Recupera los datos de un itinerario
      case 9:
         getItinerario($_POST['id']);
      break;
      
      // Recupera los datos de un itinerario
      case 10:
         getDepartamento($_POST['id']);
      break;

      // Recupera todos los departamentos menos el indicado
      case 11:
         getDepartamentoDos($_POST['id']);
      break;

      // Recupera los datos de una asignatura
      case 12:
         getAsignatura($_POST['id']);
      break;

      // Recupera las asignaturas de una carrera
      case 13:
         getAsignaturasCarrera($_POST['carrera']);
      break;

      // Recupera los departamentos de una facultad
      case 14:
         getDepartamentosFacultad($_POST['idfacultad']);
      break;

      // Recupera todos los datos del docente para mostrarlos
      case 15:
         getDocente($_POST['id']);
      break;

      // Filtra las asignaturas según facultad, carrera (opcional) e itinerario (opcional)
      case 16:

         $facultad   = (isset($_POST['facultad']))    ? $_POST['facultad']    : -1;
         $carrera    = (isset($_POST['carrera']))     ? $_POST['carrera']     : -1;
         $itinerario = (isset($_POST['itinerario']))  ? $_POST['itinerario']  : -1;
         
         getAsignaturasFiltradas($facultad, $carrera, $itinerario);
      break;

      // Recupera todas las carreras que están asociadas a una facultad
      case 17:
         getCarrerasByFacultad($_POST['facultad']);
      break;

      // Recupera el listado de asignaturas excluyendo las seleccionadas
      case 18:
         getListadoAsignaturasExcepto(json_decode($_POST['seleccion']));
      break;

      // Procesar horario del asistente
      case 19:
         procesarHorarioAsistente($_POST['asignaturas'], $_POST['disponibilidad']);
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

die();


//INDEX

function muestra_itinerarios($id_carrera){
   if(is_numeric($id_carrera)){
      $idao = new Itinerario_dao();

      $itinerarios = $idao->getByIdCarrera($id_carrera);

      echo json_encode($itinerarios);
   }
   else{
      // Esto solo saltará si el usuario cambia los datos del formulario
      echo json_encode("Error. Manipulacion de datos detectada.");
   }
}

function existe_itinerario($id_carrera, $id_itinerario){
   if($id_itinerario == NULL)
      return true;

   if(is_numeric($id_carrera) && (is_numeric($id_itinerario))){
      $idao = new Itinerario_dao();

      $itinerarios = $idao->checkItinerario($id_carrera, $id_itinerario);

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


// ASISTENTE

function consultaAsignaturas($curso){
   $adao = new Asignatura_dao();
   $cursos = $adao->getByCarreraCursoItinerario($_SESSION['carrera'], $curso, $_SESSION['itinerario']);
   echo json_encode($cursos);
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
}

// GESTION

function getFacultad($id){
   $fdao = new Facultad_dao();
   echo json_encode($fdao->getById($id));
   unset($fdao);
}

function listarFacultadesDG($id){
   $fdao = new Facultad_dao();
   echo json_encode($fdao->getListadoDG($id));
   unset($fdao);
}

function getCarrera($id){
   $cdao = new Carrera_dao();
   echo json_encode($cdao->getById($id));
   unset($cdao);
}

function getItinerario($id){
   $idao = new Itinerario_dao();
   echo json_encode($idao->getById($id));
   unset($idao);
}

function getDepartamento($id){
   $ddao = new Departamento_dao();
   echo json_encode($ddao->getById($id));
   unset($ddao);
}

function getDepartamentoDos($id){
   $ddao = new Departamento_dao();
   echo json_encode($ddao->getListadoSin($id));
   unset($ddao);
}

function getAsignatura($id){
   $adao = new Asignatura_dao();
   echo json_encode($adao->getById($id));
   unset($adao);
}

function getAsignaturasCarrera($carrera){
   $adao = new Asignatura_dao();
   echo json_encode($adao->getListadoFiltrado($carrera));
   unset($adao);
}

function getDepartamentosFacultad($idfacultad){
   $ddao = new Departamento_dao();
   echo json_encode($ddao->getDepartamentosFacultad($idfacultad));
   unset($ddao);
}

function getDocente($id){
   $dodao = new Docente_dao();
   echo json_encode($dodao->getDocente($id));
   unset($dodao);
}

function getAsignaturasFiltradas($facultad, $carrera, $itinerario){
   $adao = new Asignatura_dao();
   echo json_encode($adao->filtrarAsignaturas($facultad, $carrera, $itinerario));
   unset($adao);
}

function getCarrerasByFacultad($facultad){
   $cdao = new Carrera_dao();
   echo json_encode($cdao->getListadoByFacultad($facultad));
   unset($cdao);
}

function getListadoAsignaturasExcepto($seleccion){
   $adao = new Asignatura_dao();
   echo json_encode($adao->listadoExcepto($seleccion));
   unset($adao);
}

function procesarHorarioAsistente($asignaturas, $disponibilidad){
   $listado = json_decode($asignaturas, true);
   
   foreach ($listado as $asignatura) {
      $id = $asignatura['id'];
      $nombre = $asignatura['nombre'];
      $abreviatura = $asignatura['abreviatura'];
      $creditos = $asignatura['creditos'];
   }

   $disp=json_decode($disponibilidad);
   
   $alg=new algoritmo_backtracking($disponibilidad,$listado,$_SESSION['carrera']);
   $sol=$alg->ejecuta();
   // la solucion es una array en la que cada posicion es un array de tipo clase,
   // es decir, cada posicion es una asignatura,en teoria ordenado por la preferencia del usuario
   //, y dentro de cada posicion hay una array con todas las filas de la base de datos de un grupo de esa asignatura,
   // es decir, si se coge una asignatura y esta tiene 4 horas de clase, saldra una array de una posicion
   // que sera un array de 4 posiciones.

   echo json_encode($sol);
}

?>
