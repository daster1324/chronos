<?php

class Connection{

    public static function connect(){
        $host = "localhost";
        $user = "chronos";
        $pass = "chronos";
        $dbname = "chronos";

        $conn = mysqli_connect($host, $user, $pass, $dbname);

        if (mysqli_connect_errno()){
            echo "Error al conectar a la MySQL: " . mysqli_connect_error();
        }

        return $conn;
    }

    public static function disconnect($conn){
        mysqli_close($conn);
    }

}

?>