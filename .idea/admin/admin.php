<?php


error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);


if(isset($_POST["user"]) && isset($_POST["pw"]))
{


    $user = $_POST["user"];
    $pw	= $_POST["pw"];

    $username = "admin";
    $password = '$argon2i$v=19$m=16,t=2,p=1$YXNkZmdoams$o+/9aR6cgi0Zyev65CJcuw'; //pw: ABC

    $login = false;

    if($username == $user && password_verify($pw, $password)) {
        $login = true;
    }

    if($login){
        ?>
        <head>
            <title>Datei hochladen</title>
            <link rel="icon" type="image/x-icon" href="../global/favicon.ico?v=1.0">
            <meta charset="UTF-8">
            <link rel="stylesheet" href="upload.css?v=1.0">
        </head>
        <body>
        <header class="header">
            <div id="alles">
                <div class="headerElement links">
                    <a href="../index/index.php">
                        <img src="../global/SVlogo.png" alt="SV-Logo" class="logo"/>
                    </a>
                </div>
            </div>
        </header>
        <div id="page">
            <div id="feld">
                <h1>Datei hochladen</h1>
                <form name="upload" method="post" action="verarbeiten.php" enctype="multipart/form-data">
                    <input type="file" id="datei" accept="text/csv" name="datei"/>
                    <button id="hochladen" type="submit">Hochladen</button>
                </form>
            </div>
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
    ?>
    <html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="login.css?v=1.0.2">
        <!--Um in JS Argon2 Hashes generieren zu können nutzen wir eine externe Library -->
        <script src="jsLibrary/argon2.js"></script>
        <link rel="icon" type="image/x-icon" href="../global/favicon.ico?v=1.0">
        <title>Login</title>
        <script>


            var readyForSubmit = false;
            var hashedPwSet = false;

            async function createHash()
            {

                let clearText = document.f01.pw.value;



                let hashedText = await argon2.hash({
                    pass: clearText,
                    salt: 'somesalt'
                });

                document.f01.pw.value = hashedText;
                hashedPwSet = true;

            }

            function submitForm() {
                createHash();

                let user = document.f01.user.value;
                let pw = document.f01.pw.value;

                if(user !== "" && pw !== "" && hashedPwSet === true)
                {
                    readyForSubmit = true;
                } else {
                    alert("Passwort und Username stimmen nicht überein");
                }

            }
            }
        </script>

    </head>
    <body>
    <header class="header">
        <div id="alles">
            <div class="headerElement links">
                <a href="../index/index.php">
                    <img src="../global/SVlogo.png" alt="SV-Logo" class="logo"/>
                </a>
            </div>
        </div>
    </header>
    <div id="page">
        <div id="anmelden">
            <h1>Login</h1>

            <form name="f01" id="f01" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

                <input type="text" name="user" placeholder="Benutzername"><br>

                <input type="password" name="pw" placeholder="Passwort"><br>


                <button name="sendForm" onclick="submitForm()">Anmelden</button>
            </form>
        </div>
    </div>

    </body>
    </html>
    <?php
}
?>
