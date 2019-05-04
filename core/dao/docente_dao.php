<?php

class Docente_dao implements iDAO{
   
/*
    private $id;            // Integer 4 digitos - Obligatorio
    private $usuario;       // String 32 chars   - Obligatorio
    private $pass;          // String 64 chars   - Obligatorio
    private $departamento;  // Integer 3 digitos - Obligatorio
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

        if (!($sentencia = $conn->prepare("SELECT * FROM `docentes` WHERE id = ?;"))) {
            echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
        }

        if (!$sentencia->bind_param("i", $id)) {
            echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
        }

        $sentencia->execute();

        $result = $sentencia->get_result();

        if($result->num_rows === 0)
            return NULL;

        $r = $result->fetch_assoc();

        $docente = new Docente($r["id"], $r["nombre"], $r["campus"], $r['preferencias']);

        $sentencia->close();
        $conn->close();

        return $docente;
    }

    /**
     * Guarda en la base de datos el docente proporcionado
     * En caso de que ya exista, se actualizan los datos "departamento" y/o "preferencias"
     * 
     * @param $d - docente a guardar
     */
    public function store($d){
        $conn = Connection::connect();

        $id  = $d->getId();
        $usuario  = $d->getUsuario();
        $departamento  = $d->getDepartamento();
        $preferencias  = $d->getPreferencias();
        $pass  = $d->getPassword();

        $actualizar = ($this->getById($d->getId()) != NULL);

        if($actualizar){
            if (!($sentencia = $conn->prepare("UPDATE `docentes` SET `departamento` = ?, `preferencias` = ? WHERE `id` = ?;"))) {
                echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
            }
            if (!$sentencia->bind_param("isi", $departamento, $preferencias, $id)) {
                echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
            }
            $sentencia->execute();
            $sentencia->close();
            $conn->close();
        }
        else{
            if (!($sentencia = $conn->prepare("INSERT INTO `docentes` (`id`, `usuario`, `password`, `departamento`, `preferencias`) VALUES (NULL, ?, ?, ?, ?);"))) {
                echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
            }
            if (!$sentencia->bind_param("ssis", $usuario, $pass, $departamento, $preferencias)) {
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

        if (!($sentencia = $conn->prepare("DELETE FROM `docentes` WHERE `id` = ?;"))) {
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

        if (!($sentencia = $conn->prepare("SELECT * FROM `docentes` WHERE usuario LIKE ? AND pass LIKE ?;"))) {
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

        $_SESSION['docente-id'] = $r["id"];
        $_SESSION['docente-usuario'] = $r["usuario"];
        $_SESSION['docente-departamento'] = $r["departamento"];
        $_SESSION['docente-preferencias'] = $r["preferencias"];

        return true;
    }

    public function count(){        
        $conn = Connection::connect();
    
        if (!($sentencia = $conn->prepare("SELECT count(`id`) AS `cuenta` FROM `docentes`;"))) {
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