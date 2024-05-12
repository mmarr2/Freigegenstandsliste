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
                echo $data[$j];
                if ($j == 0) {
                    $obj[$keys[$j]] = $id++;
                    $obj[$keys[$j + 1]] = $data[$j];

                } else {
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
                echo $result;
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

    if (strlen($jahrgang) == 1 && intval($jahrgang) <= 5 && intval($jahrgang) >= 1) return true;
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

function formatJahrgaenge($jahrgaenge) {
    $numbers = array_map('intval', explode(",", trim($jahrgaenge)));
    if (count($numbers) === 1) {
        return strval($numbers[0]);
    }
    sort($numbers);
    return $numbers[0] . "-" . $numbers[count($numbers) - 1];
}

function checkStunden($stunden) {
    return is_numeric($stunden);
}



if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_FILES["datei"])){
        $result = parseCSVFile($_FILES["datei"]["tmp_name"]);
        $myfile = fopen("../global/output.json", "w") or die("Unable to open file!");
        fwrite($myfile, json_encode($result, JSON_PRETTY_PRINT));
        fclose($myfile);

    } else {
        echo "Der Upload hat nicht funktioniert!";
    }
}
?>

<html>
<body>
    <h1>Die Datei wurde erfolgreich hochgeladen!</h1>
</body>
</html>
<style>
    body{
        background-color: #1C1F33;
        color: #F8F7F9;
        font-family: "Arial";
        font-style: normal;
    }

    h1{
        font-size: 100px;
        margin: 20%;
    }
</style>
