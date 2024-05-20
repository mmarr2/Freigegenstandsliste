<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Freifächer</title>
    <script src="index.js?v=<?php echo time(); ?>"></script>
    <link rel="stylesheet" href="index.css?v=1.0.8">
    <link rel="stylesheet" href="header.css?v=1.0.6">
    <link rel="icon" type="image/x-icon" href="../global/favicon.ico?v=1.0">

</head>
<body>
<header class="header">
    <div id="alles">
        <div class="headerElement links">
            <img src="../global/SVlogo.png" alt="SV-Logo" class="logo"/>
        </div>

        <div class="headerElement filter">
            <div>
                <label htmlFor="abteilung">Abteilung </label>
                <select id="abteilung" class="select">
                    <option value=""></option>
                    <option value="HIT">HIT</option>
                    <option value="HWI">HWI</option>
                    <option value="HMB">HMB</option>
                    <option value="HKT">HKT</option>
                    <option value="HBG">HBG</option>
                    <option value="HEL">HEL</option>
                    <option value="HET">HET</option>
                </select>
            </div>
            <div>
                <label id="jahr">Jahrgang </label>
                <input id="jahrgang" type="number" min="1" max="5" class="input"/>
            </div>
            <div>
                <input id= "suche" type="text" placeholder="Suche"/>
            </div>
        </div>
    </div>
</header>
<h1 class="oben">Freigegenst&auml;nde</h1>
<div id="dataContainer">

</div>
</body>
</html>

<script >

</script>
