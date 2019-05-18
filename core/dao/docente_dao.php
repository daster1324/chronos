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
                    
        $docente = new Docente($r['id'], $r['nombre'], $r['departamento'], $r['email'], $r['preferencias'], $r['orden'], $r['usuario'], $r['pass']);

        $sentencia->close();
        $conn->close();

        return $docente;
    }

    public function getAllDataById($id){
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
                    
        $docente = new Docente($r['id'], $r['nombre'], $r['departamento'], $r['email'], $r['preferencias'], $r['orden'], $r['usuario'], $r['pass']);

        $sentencia->close();
        $conn->close();

        return $docente;
    }

    // Recupera todo los datos del docente salvo 'preferencias' y 'password'
    public function getDocente($id){
        $conn = Connection::connect();

        $ddao = new Departamento_dao();

        $stmt = "SELECT id, nombre, departamento, usuario, orden, email FROM `docentes` WHERE `id` = ?;";       

        if (!($sentencia = $conn->prepare($stmt))) {
            echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
        }

        if (!$sentencia->bind_param("i", $id)) {
            echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
        }

        $sentencia->execute();

        $result = $sentencia->get_result();

        $sentencia->close();
        $conn->close();

        $docentes = array();

        while($r = $result->fetch_assoc())
        {
            $facultad = $ddao->getById($r['departamento'])->getId_facultad();
            $docentes = array('id' => $r['id'], 'nombre' => $r['nombre'], 'facultad' => $facultad, 'email' => $r['email'], 'departamento' => $r['departamento'], 'usuario' => $r['usuario'], 'orden' => $r['orden']);
        }

        unset($ddao);
        unset($fdao);

        return $docentes;
    }

    public function getByUsuario($usuario){
        $conn = Connection::connect();

        if (!($sentencia = $conn->prepare("SELECT * FROM `docentes` WHERE usuario = ?;"))) {
            echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
        }

        if (!$sentencia->bind_param("s", $usuario)) {
            echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
        }

        $sentencia->execute();

        $result = $sentencia->get_result();
        
        $sentencia->close();
        $conn->close();

        if($result->num_rows === 0)
            return NULL;

        $r = $result->fetch_assoc();

        $docente = new Docente($r['id'], $r['nombre'], $r['departamento'], $r['email']);

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
        $docente = $this->getAllDataById($id);
        $actualizar = $docente != NULL;
        
        $nombre = $d->getNombre();
        $departamento  = $d->getDepartamento();
        $preferencias  = $d->getPreferencias();
        $orden = $d->getOrden();
        $usuario  = $d->getUsuario();
        $pass  = $d->getPassword();
        $email = $d->getEmail();

        if($actualizar){
            $pass = ($docente->getPassword() == $pass || $pass == "")  ? $docente->getPassword() : $pass;

            $nombre = ($docente->getNombre() == $nombre) ? $docente->getNombre() : $nombre;

            $departamento = ($docente->getDepartamento() == $departamento) ? $docente->getDepartamento() : $departamento;

            $preferencias = ($docente->getPreferencias() == $preferencias) ? $docente->getPreferencias() : $preferencias;

            $orden = ($docente->getOrden() == $orden) ? $docente->getOrden() : $orden;

            if (!($sentencia = $conn->prepare("UPDATE `docentes` SET `pass` = ?, `nombre` = ?, `departamento` = ?, `preferencias` = ?, `orden` = ? WHERE `id` = ?;"))) {
                echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
            }
            if (!$sentencia->bind_param("ssisii", $pass, $nombre, $departamento, $preferencias, $orden, $id)) {
                echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
            }
            $sentencia->execute();
            $sentencia->close();
            $conn->close();
        }
        else{
            $usuario  = $d->getUsuario();
            $pass  = $d->getPassword();
            $nombre = $d->getNombre();
            $departamento  = $d->getDepartamento();
            $preferencias  = $d->getPreferencias();
            $orden = $d->getOrden();            

            if (!($sentencia = $conn->prepare("INSERT INTO `docentes` (`id`, `usuario`, `pass`, `nombre`, `email`, `departamento`, `preferencias`, `orden`) VALUES (?, ?, ?, ?, ?, ?, ?, ?);"))) {
                echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
            }
            if (!$sentencia->bind_param("issssisi", $id, $usuario, $pass, $nombre, $email, $departamento, $preferencias, $orden)) {
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
    

    public function login($usuario, $pass, $change_password = false){
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

        if($change_password == false){
            $r = $result->fetch_assoc();

            $_SESSION['docente-id'] = $r["id"];
            $_SESSION['docente-usuario'] = $r["usuario"];
            $_SESSION['docente-departamento'] = $r["departamento"];
            $_SESSION['docente-preferencias'] = $r["preferencias"];
            
            header("HTTP/1.1 301 Moved Permanently"); 
            header("Location: /docentes");
        }

        return true;
    }

    public function change_password($id, $pass){
        $conn = Connection::connect();

        if (!($sentencia = $conn->prepare("UPDATE `docentes` SET `pass`= ? WHERE `id` = ?;"))) {
            echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
        }

        if (!$sentencia->bind_param("si", $pass, $id)) {
            echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
        }

        $sentencia->execute();

        $sentencia->close();
        $conn->close();

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

    public function getListado(){
        $conn = Connection::connect();

        $stmt = "SELECT * FROM `docentes` ORDER BY `nombre`;";       

        if (!($sentencia = $conn->prepare($stmt))) {
            echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
        }

        $sentencia->execute();

        $result = $sentencia->get_result();

        $sentencia->close();
        $conn->close();

        $docentes = array();

        while($r = $result->fetch_assoc())
        {
            $docentes[] = new Docente($r['id'], $r['nombre'], $r['departamento'], $r['preferencias'], $r['orden'], $r['usuario']);
        }

        return $docentes;
    }

    public function getListadoPublico(){
        $conn = Connection::connect();

        $stmt = "SELECT id, nombre, departamento FROM `docentes` ORDER BY `nombre`;";       

        if (!($sentencia = $conn->prepare($stmt))) {
            echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
        }

        $sentencia->execute();

        $result = $sentencia->get_result();

        $sentencia->close();
        $conn->close();

        $docentes = array();

        while($r = $result->fetch_assoc())
        {
            $docentes[] = array('id' => $r['id'], 'nombre' => $r['nombre'], 'departamento' => $r['departamento']);
        }

        return $docentes;
    }

    public function store_preferencias($preferencias, $id_docente){
        $conn = Connection::connect();

        if (!($sentencia = $conn->prepare("UPDATE `docentes` SET `preferencias`= ? WHERE `id` = ?;"))) {
            echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
        }

        if (!$sentencia->bind_param("si", $preferencias, $id_docente)) {
            echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
        }

        $sentencia->execute();

        $sentencia->close();
        $conn->close();

        return true;
    }

    public function get_preferencias($id_docente){
        $conn = Connection::connect();

        if (!($sentencia = $conn->prepare("SELECT `preferencias` FROM `docentes` WHERE `id` = ?;"))) {
            echo "Falló la preparación: (" . $conn->errno . ") " . $conn->error;
        }

        if (!$sentencia->bind_param("i", $id_docente)) {
            echo "Falló la vinculación de parámetros: (" . $sentencia->errno . ") " . $sentencia->error;
        }

        $sentencia->execute();

        $result = $sentencia->get_result();
        
        $sentencia->close();
        $conn->close();

        if($result->num_rows === 0)
            return NULL;

        $r = $result->fetch_assoc();

        return $r['preferencias'];
    }
}




?>