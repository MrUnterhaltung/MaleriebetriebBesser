<?php
    include("config.php");

    $conn = new mysqli($hostname, $username, $password, $db);

    if ($connectiontest) {

      if ($conn->connect_error) {
        die("Verbindung zur Datenbank fehlgeschalgen: " . $conn->connect_error);

      } else {
        echo("Verbindung zur Datenbank hergestellt");
      }

    }
?>