<?php
    include("include/config.php");
    include("include/conn.php");
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Mitarbeiter</title>
</head>
<body>
    <h1>Mitarbeiter</h1>
    
    <form method="post">
        <fieldset>
            <legend>Mitarbeiterfilter</legend>
            <label>
                Vorname:
                <input type="text" name="VN_MA" placeholder="Vorname">
            </label>
            <label>
                Nachname:
                <input type="text" name="NN_MA" placeholder="Nachname">
            </label>
            <label>
                <input type="submit" value="filter"> 
            </label>
        </fieldset>
        <fieldset>
            <legend>Kundenfilter</legend>
            <label>
                Vorname:
                <input type="text" name="VN_K" placeholder="Vorname">
            </label>
            <label>
                Nachname:
                <input type="text" name="NN_K" placeholder="Nachname">
            </label>
            <label>
                <input type="submit" value="filter"> 
            </label>
        </fieldset>
    </form>

    <?php

        $arr = [];
        $where = "";

        /*** Filter für die Mitarbeiter. Falls etwas mit dem Formular bageschickt wird, wird der Filter gesetzt ***/
        if (count($_POST)>0){
            if (strlen($_POST["VN_MA"]) > 0){
                $arr[] = "VorN_Mitarbeiter='" . $_POST["VN_MA"] . "'";
            }
            if (strlen($_POST["NN_MA"]) > 0){
                $arr[] = "NachN_Mitarbeiter='" . $_POST["NN_MA"] . "'";
            }
        }

        if (count($arr)>0){
            $where = " WHERE (" . implode(" AND ",$arr) . ")";
        }

        /*** Erste SQL-Abfrage mit dem Filter ***/
        $sql = "SELECT * FROM tbl_mitarbeiter " . $where .  "ORDER BY NachN_Mitarbeiter ASC, VorN_Mitarbeiter ASC";

        $mitarbeiterliste = $conn->query($sql) or die("Fehler in der Query: " . $conn->error . "<br>" . $sql);

        /*** Hier wird alles ausgegeben was ausgegeben werden soll 
             Die erste While-Schleife gibt alle gefilterten Infos über die Mitarbeiter aus ***/
        while ($mitarbeiter = $mitarbeiterliste->fetch_object()){
            echo("<ul>" . 
                "<li>
                Vorname: " . $mitarbeiter->NachN_Mitarbeiter . 
                " | Nachname: " . $mitarbeiter->VorN_Mitarbeiter . 
                " | SVNR: " . $mitarbeiter->SVNR . 
                " | Geb.Datum: " . $mitarbeiter->Geburtsdatum . 
                " | Email: " . $mitarbeiter->Email .
                "</li>"
            );

            $arr = ["ID_Mitarbeiter = " . $mitarbeiter->ID ];

            /*** Neuer Filter für die Kunden ***/
            if (count($_POST)>0){
                if (strlen($_POST["VN_K"])>0){
                    $arr[] = "tbl_kunde.VorN_Kunde = '" . $_POST["VN_K"] . "'";
                }
                if (strlen($_POST["NN_K"])>0){
                    $arr[] = "tbl_kunde.NachN_Kunde = '" . $_POST["NN_K"] . "'";
                }
            }

            $where = " WHERE(" . implode(" AND ",$arr) . ") ";

            /*** Zweite SQL-Abfrage mit dem Filter der Kunden ***/
            $sql = "SELECT * FROM tbl_auftragsliste 
                    LEFT JOIN tbl_kunde ON tbl_kunde.ID = tbl_auftragsliste.ID_Kunde 
                    " . $where . "
                    ORDER BY tbl_auftragsliste.Arbeitsbeginn ASC, tbl_auftragsliste.Arbeitsende ASC";

            $auftragsliste = $conn->query($sql) or die("Fehler in der Query: " . $conn->error . "<br>" . $sql);
            echo("<ul>");
            while ($auftrag = $auftragsliste->fetch_object()){
                echo("<li>
                    Arbeitsbeginn: " . $auftrag->Arbeitsbeginn .
                    " Arbeitsende: " . $auftrag->Arbeitsende .
                    " Vorname: " . $auftrag->VorN_Kunde . 
                    " Nachname: " . $auftrag->NachN_Kunde .
                "</li>");
            }
            echo("</ul>");

            echo("</ul>");
        }
    ?>
    
</body>
</html>