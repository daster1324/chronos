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

        return $conn;
    }

}

?>