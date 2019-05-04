<?php

class Gestor_dao implements iDAO{
   
/*
    private $id;            // Integer 4 digitos - Obligatorio
    private $usuario;       // String 32 chars   - Obligatorio
    private $pass;          // String 64 chars   - Obligatorio
*/
    public function __construct(){}

    /**
     * Devuelve un objeto con los datos del docente correspondiente al $id.
     * Devuelve NULL si no hay ningún docente con ese $id 
     * 
     * Por seguridad, no se extrae la contraseña.
     * 
     * @param $id - id del docente a buscar
     */
    public function getById($id){
        $conn = Connection::connect();

        if (!($sentencia = $conn->prepare("SELECT * FROM `gestores` WHERE id = ?;"))) {
            echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
        }

        if (!$sentencia->bind_param("i", $id)) {
            echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
        }

        $sentencia->execute();

        $result = $sentencia->get_result();

        $sentencia->close();
        $conn->close();

        if($result->num_rows === 0)
            return NULL;

        $r = $result->fetch_assoc();

        $gestor = new Gestor($r["id"], $r["nombre"]);

        return $gestor;
    }

    /**
     * Guarda en la base de datos el docente proporcionado
     * En caso de que ya exista, no hace nada
     * 
     * @param $g - gestor a guardar
     */
    public function store($g){
        $conn = Connection::connect();

        $id  = $g->getId();
        $usuario  = $g->getUsuario();
        $departamento  = $g->getDepartamento();
        $preferencias  = $g->getPreferencias();
        $pass  = $g->getPassword();

        $actualizar = ($this->getById($g->getId()) != NULL);

        if(!$actualizar){
            if (!($sentencia = $conn->prepare("INSERT INTO `gestores` (`id`, `usuario`, `password`) VALUES (NULL, ?, ?);"))) {
                echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
            }
            if (!$sentencia->bind_param("ss", $usuario, $pass)) {
                echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
            }
            $sentencia->execute();
            $sentencia->close();
            $conn->close();
        }
    }

    /**
     * Elimina el docente correspondiente al $id proporcionado
     * Devuelve true si ha habido éxito en el borrado.
     * Devuelve false si no se ha podido borrar.
     * 
     * @param $id - id del docente a borrar
     */
    public function remove($id){
        $conn = Connection::connect();

        if (!($sentencia = $conn->prepare("DELETE FROM `gestores` WHERE `id` = ?;"))) {
            echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
        }

        if (!$sentencia->bind_param("i", $id)) {
            echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
        }

        $sentencia->execute();

        $sentencia->close();
        $conn->close();
    }
    

    public function login($usuario, $pass){
        $conn = Connection::connect();

        if (!($sentencia = $conn->prepare("SELECT * FROM `gestores` WHERE usuario LIKE ? AND pass LIKE ?;"))) {
            echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
        }

        if (!$sentencia->bind_param("ss", $usuario, $pass)) {
            echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
        }

        $sentencia->execute();

        $result = $sentencia->get_result();

        $sentencia->close();
        $conn->close();

        if($result->num_rows === 0)
            return false;

        $r = $result->fetch_assoc();

        $_SESSION['gestor-id'] = $r["id"];
        $_SESSION['gestor-usuario'] = $r["usuario"];

        return true;
    }

    public function change_password($id, $pass){
        $conn = Connection::connect();

        if (!($sentencia = $conn->prepare("UPDATE `gestores` SET `pass`= ? WHERE `id` = ?;"))) {
            echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
        }

        if (!$sentencia->bind_param("ss", $usuario, $pass)) {
            echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
        }

        $sentencia->execute();

        $result = $sentencia->get_result();

        $sentencia->close();
        $conn->close();

        if($result->num_rows === 0)
            return false;

        $r = $result->fetch_assoc();

        $_SESSION['gestor-id'] = $r["id"];
        $_SESSION['gestor-usuario'] = $r["usuario"];

        return true;
    }

    public function count(){        
        $conn = Connection::connect();
    
        if (!($sentencia = $conn->prepare("SELECT count(`id`) AS `cuenta` FROM `gestores`;"))) {
            echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
        }

        $sentencia->execute();

        $result = $sentencia->get_result();

        $sentencia->close();
        $conn->close();

        if($result->num_rows === 0)
            return 0;

        $r = $result->fetch_assoc();

        return $r['cuenta'];
    }
}




?>