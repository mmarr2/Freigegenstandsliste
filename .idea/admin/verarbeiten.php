<?php


function parseCSVFile($file_temp_path) {
    $result = [];
    $id = 0;
    $valid = true;
    $keys = ["id", "abteilung", "jahrgang", "gegenstand", "bezeichnung", "stunden", "email", "lehrer", "beschreibung", "infos"];

    if (($handle = fopen($file_temp_path, "r")) !== FALSE) {
        fgetcsv($handle, 0, ",");
        fgetcsv($handle, 0, ",");
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $obj = [];
            $rowValid = true;

            for ($j = 0; $j < count($data); $j++) {
                if ($j == 0) {
                    $obj[$keys[$j]] = $id++;
                    $obj[$keys[$j + 1]] = $data[$j];

                } else if($j == 1){
                    $rowValid = true;
                    if (!isset($data[$j]) || trim($data[$j]) == "") {
                        $rowValid = false;
                    } else {
                        if(strlen(trim($data[$j])) == 1){
                            $obj[$keys[$j + 1]] = $data[$j] . ".";
                        } else {
                            $obj[$keys[$j + 1]] = $data[$j];
                        }
                    }
                } else{
                    $rowValid = true;
                    if (!isset($data[$j]) || trim($data[$j]) == "") {
                        $rowValid = false;
                    } else {
                        $obj[$keys[$j + 1]] = $data[$j];
                    }
                }
            }

            if ($rowValid && checkEmail($data[5]) && checkJahrgang($data[1]) && checkAbteilung($data[0]) && checkStunden($data[4])) {
                $result[] = $obj;
            } else {
                $valid = false;
            }
        }

        fclose($handle);
    }

    return $result;
}

function checkEmail($email) {
    $regex = '/\b[A-Za-z0-9._%+-]+@tgm.ac.at/';
    return preg_match($regex, $email);
}


function checkJahrgang($jahrgang) {


    if ($jahrgang == null || $jahrgang == "") return false;

    if (strlen($jahrgang) == 1 && intval(substr($jahrgang, 0, 1)) <= 5 && intval(substr($jahrgang, 0, 1))) {
        return true;
    }
    $grenzen = explode("-", $jahrgang);
    $unten = intval($grenzen[0]);
    $oben = intval($grenzen[1]);
    if ($unten < $oben && $unten >= 1 && $unten < 5 && $oben > 1 && $unten <= 5) {
        return true;
    }
    return false;
}

function checkAbteilung($abteilung) {
    if ($abteilung != null && $abteilung != "") {
        $alle = explode(";", $abteilung);
        $abteilungen = ["HBG", "HET", "HEL", "HIT", "HKT", "HMB", "HWI", "Alle"];
        foreach ($alle as $teil) {
            if (!in_array(trim($teil), $abteilungen)) {

                return false;
            }
        }

        return true;
    }
    return false;
}


function checkStunden($stunden) {
    return is_numeric($stunden);
}



if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_FILES["datei"])){
        $result = parseCSVFile($_FILES["datei"]["tmp_name"]);
        $myfile = fopen("../global/output.json", "w") or die("Unable to open file!");
        fwrite($myfile, json_encode(mb_convert_encoding($result, 'UTF-8', 'UTF-8'), JSON_PRETTY_PRINT));
        fclose($myfile);
        ?>
        <html>
        <head>
            <title>Erfolgreich!</title>
            <link rel="icon" type="image/x-icon" href="../global/favicon.ico?v=1.0">
        </head>
        <body>

        <h1>Die Datei wurde erfolgreich hochgeladen!</h1>
        <a href="../index/index.php">Startseite</a>
        </body>
        <style>
            body{
                background-color: #54b7e6;
                color: #F8F7F9;
                font-family: "Arial";
                font-style: normal;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
            }

            h1{
                margin-top: 20%;
                font-size: 60px;
            }

            a{
                text-decoration: underline;
                color: white;
                text-underline: white;
                font-size: 20px;
            }

            a:hover{
                color: #eeeeee;
            }
        </style>
        </html>


        <?php
    } else {
        ?>
        <html>
        <head>
            <title>Fehler</title>
            <link rel="icon" type="image/x-icon" href="../global/favicon.ico?v=1.0">
        </head>
        <body>
        <h1>Beim Upload gab es einen Fehler</h1>
        <a href="../index/index.php">Startseite</a>
        </body>
        <style>
            body{
                background-color: #54b7e6;
                color: #F8F7F9;
                font-family: "Arial";
                font-style: normal;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
            }

            h1{
                margin-top: 20%;
                font-size: 60px;
            }

            a{
                text-decoration: underline;
                color: white;
                text-underline: white;
                font-size: 20px;
            }

            a:hover{
                color: #eeeeee;
            }
        </style>
        </html>


        <?php
    }
}
?>



