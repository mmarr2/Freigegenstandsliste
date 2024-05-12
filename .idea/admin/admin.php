<?php

// Melde alle PHP-Fehler
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);


/*
Wir unterscheiden ob das Formular mit dem Benutzernamen und dem Passwort bereits gesetzt wurde oder nicht und zeigen entsprechend das Formular an oder nicht
*/

if(isset($_POST["user"]) && isset($_POST["pw"]))
{

    //Formulardaten wurden bereits gesetzt, deshalb überprüfen wir jetzt Username und Password
    $user 	= $_POST["user"];
    $pw		= $_POST["pw"];


    /*
    Login überprüfen

    1) Aufbau eines Arrays mit Benutzername und Passwort als argon2 Hash
    2) Überprüfen des Logins gegen dieses Array (Schleife) und setzen einer Zugangsvariablen auf true wenn das Login erfolgreich war

    */


    $login_data[0][0] = "admin";
    $login_data[0][1] = '$argon2i$v=19$m=16,t=2,p=1$c29tZXNhbHQ$aXNgzetaJlm1oiGsKBzBTQ'; //user: brein pw: Ses4mOe§§n3D!ch?



    $login = false;

    //Länge der Benutzerliste
    $arr_length = count($login_data);
    //echo "Arraylänge: ".$arr_length;

    //Schleife über das $login_data Array
    for($i=0;$i<$arr_length;$i++)
    {
        /*
            $login_data[i][0]... Benutzername
            $login_data[i][1]... Passworthash

            Wenn eine Übereinstimmung in Sachen Benutzer und Passwort gefunden wird wird die Login Variable auf true gesetzt.
        */

        if($login_data[$i][0] == $user && password_verify($pw, $login_data[$i][1]))
        {
            $login = true;
        }

    }



    if($login){
        ?>
        <head>
            <meta charset="UTF-8">
            <link rel="stylesheet" href="upload.css">
        </head>
        <body>
        <div id="feld">
            <h1>Datei hochladen</h1>
            <form name="upload" method="post" action="verarbeiten.php" enctype="multipart/form-data">
                <input type="file" id="datei" accept="text/csv" name="datei"/>
                <button id="hochladen" type="submit">Hochladen</button>
            </form>
        </div>

        </body>
        <?php
    } else {
        ?>
        <script>
            alert("Passwort und User stimmen nicht überein");
        </script>
        <?php
        header("Location: admin.php");
    }
    exit();


}else{
    //Formulardaten noch nicht gesetzt, zeige das Formular an
    ?>
    <html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="login.css">
        <!--Um in JS Argon2 Hashes generieren zu können nutzen wir eine externe Library -->
        <script src="jsLibrary/argon2.js"></script>
        <script>
            //Die Boolean Variable sorgt dafür, dass das Formular beits clientseitig daran gehindert wird gesendet zu werden wenn nicht beide Felder ausgefüllt und das PW durch einen Hashwert ersetzt wurde

            var readyForSubmit = false;
            var hashedPwSet = false; //Boolean Variable, die dafür sorgt, dass das Formular erst nach dem hashen des Passwortes gesendet werden kann

            async function createHash()
            {
                //Hole dir den Inhalt des Passwortfeldes
                let clearText = document.f01.pw.value;
                //alert(clearText)

                //Erzeugen des Hashwertes mittels der externen Library
                let hashedText = await argon2.hash({
                    pass: clearText,
                    salt: 'somesalt'
                });
                console.log(hashedText);
                //Zurückschreiben des nun gehashten Textes in das Passwort Feld
                document.f01.pw.value = hashedText;


                hashedPwSet = true;

            }

            function submitForm()
            {
                //Aufruf der function createHash()
                createHash();

                let user = document.f01.user.value;
                let pw = document.f01.pw.value;

                //Hier wird überprüft ob beide Felder gesetzt wurden und ein Hash statt dem Klartextpasswort gesetzt wurde - im Erfolgsfall wird readyForSumbmit true gesetzt
                if(user !== "" && pw !== "" && hashedPwSet === true)
                {
                    //alert("Hat geklappt1");
                    readyForSubmit = true;
                    //alert("Ready?"+readyForSubmit);
                }


                if(readyForSubmit === false)
                    alert("Kurz warten")
            }

            }

        </script>

    </head>
    <body>

    <div id="anmelden">
        <h1>Login</h1>

        <form name="f01" id="f01" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

            <input type="text" name="user" placeholder="Benutzername"><br>

            <input type="password" name="pw" placeholder="Passwort"><br>


            <button name="sendForm" onclick="submitForm()">Anmelden</button>
        </form>
    </div>

    </body>
    </html>
    <?php
}
?>
