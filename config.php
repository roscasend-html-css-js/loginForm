<?php
    date_default_timezone_set("Europe/Bucharest");
    try {
         $con = new PDO("mysql:dbname=dbNAme;host=localhost;port=3306","db_user","password");
         $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); 
    } catch(PDOException $e) {
        exit("Connection failed: " . $e->getMessage());
    }
?>