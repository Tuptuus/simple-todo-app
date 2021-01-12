<?php

    $host = "localhost";
    $dbuser = "root";
    $dbpass = "";
    $dbname = "todo_app";

    $conn = mysqli_connect($host, $dbuser, $dbpass, $dbname) or die("Conn err");
    mysqli_set_charset($conn, "utf8");

?>