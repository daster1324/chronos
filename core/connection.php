<?php

class Connection{

    public static function connect(){
        $host = "localhost";
        $user = "chronos";
        $pass = "chronos";
        $dbname = "chronos";

        $conn = mysqli_connect($host, $user, $pass, $dbname);
        $conn->set_charset("utf8");
        if (mysqli_connect_errno()){
            echo "Error al conectar a la MySQL: " . mysqli_connect_error();
        }

        $charset = $conn->character_set_name();
        printf ("El juego de caracteres en uso es %s\n", $charset);

        return $conn;
    }

    public static function disconnect($conn){
        mysqli_close($conn);
    }

}

?>