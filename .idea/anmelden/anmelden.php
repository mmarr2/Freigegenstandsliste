<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Anmelden</title>
    <link rel="stylesheet" href="anmelden.css?v=1.2">
    <script src="anmelden.js"></script>
    <link rel="icon" type="image/x-icon" href="../global/favicon.ico?v=1.0">

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
<div id="körper">


    <div id="info">
        <h1 id="name"></h1>
        <hr>
        <div id="detail">
            <div id="lehrer"></div>
            <div id="stunden"></div>
            <div id="abt"></div>
            <div id="jahr"></div>
        </div>
        <hr>
        <div id="infos"></div>
    </div>
    <div class="form">
        <h1>Anmelden</h1>
        <div className="namen">
            <input type="text" id="vorname" placeholder="Vorname"/>
            <input type="text" id="nachname" placeholder="Nachname"/>
        </div>
        <div className="auswahl">
            <input type="number" min="1" max="5" id="jahrgang" placeholder="Jahrgang"/>
            <select id="abteilung">
                <option value="" disabled selected>Abteilung</option>
                <option value="HIT">HIT</option>
                <option value="HWI">HWI</option>
                <option value="HMB">HMB</option>
                <option value="HKT">HKT</option>
                <option value="HBG">HBG</option>
                <option value="HEL">HEL</option>
                <option value="HET">HET</option>
            </select>
        </div>
        <div id="error"></div>
        <button id="anmelden" onclick="anmelden()">Anmelden!</button>
    </div>
</div>

</body>
</html>

<script>
    const urlParams = new URLSearchParams(window.location.search);
    const id = urlParams.get('id');

    getFach(id).then(fach => document.getElementById("name").innerText = fach);
    getLehrer(id).then(lehrer => document.getElementById("lehrer").innerText = "Lehrer: " + lehrer);
    getStunden(id).then(stunden => document.getElementById("stunden").innerText = "Stunden pro Woche: " + stunden);
    getAbt(id).then(abteilung => document.getElementById("abt").innerText = "Abteilung: " + abteilung);
    getJahr(id).then(jahr => document.getElementById("jahr").innerText = jahr + " Jahrgang");
    getInfos(id).then(infos => document.getElementById("infos").innerText = infos);

    function anmelden(){
        let valid = true;
        let vorname = document.getElementById("vorname").value;
        let nachname = document.getElementById("nachname").value;
        let jahrgang = document.getElementById("jahrgang").value;
        let e = document.getElementById("abteilung");
        let abteilung = e.options[e.selectedIndex].text;
        let error = "";
        console.log(abteilung);
        if(vorname.trim() == "" || nachname.trim() == "" || jahrgang == ""
            || abteilung == "Abteilung"){
            error += "Bitte alle Felder ausfüllen \n";
            valid = false;
        }

        if(jahrgang < 1 || jahrgang > 5){
            error += "Bitte validen Jahrgang angeben\n";
            valid = false;
        }

        if(abteilung == "Abteilung"){
            error += "Bitte Abteilung auswählen\n";
            valid = false;

        }

        document.getElementById("error").innerText = error;

        if (valid) {
            getFach(id).then(gegenstand => alert("Danke für deine Anmeldung bei dem Fach: " + gegenstand));
            getFach(id).then(gegenstand => alert(vorname + " " + nachname + " aus der Klasse " + jahrgang + "x" + abteilung + " hat sich für den Freigegenstand \"" +
                " " + gegenstand + "\" angemeldet"));
            open("https://owa.tgm.ac.at/owa/#path=/mail", "_blank", "width=900,height=700");
            window.location.href = '../anmelden/anmeldetext.html?id=' + id + '&vorname=' + vorname + '&nachname=' + nachname+
            '&jahrgang=' + jahrgang + '&abteilung=' + abteilung;

        }

    }


    function checkEmail(email) {
        const regex = /\b[A-Za-z0-9._%+-]+@student.tgm.ac.at/
        return regex.test(email);
    }

    async function getFach(id) {
        try {
            const response = await fetch('../global/output.json');
            const data = await response.json();

            let gegenstand = null;

            data.forEach(item => {
                if (item.id == id) {
                    gegenstand = item.gegenstand;
                }
            });

            return gegenstand;
        } catch (error) {
            console.error('Fehler beim Abrufen des Fachs:', error);
            throw error;
        }
    }

    async function getMail(id) {
        try {
            const response = await fetch('../global/output.json');
            const data = await response.json();

            let email = null;

            data.forEach(item => {
                if (item.id == id) {
                    email = item.email;
                }
            });

            return email;
        } catch (error) {
            console.error('Fehler beim Abrufen des Fachs:', error);
            throw error;
        }
    }

    async function getLehrer(id){
        try {
            const response = await fetch('../global/output.json');
            const data = await response.json();

            let lehrer = null;

            data.forEach(item => {
                if (item.id == id) {
                    lehrer = item.lehrer;
                }
            });

            return lehrer;
        } catch (error) {
            console.error('Fehler beim Abrufen des Fachs:', error);
            throw error;
        }

    }

    async function getStunden(id){
        try {
            const response = await fetch('../global/output.json');
            const data = await response.json();

            let stunden = null;

            data.forEach(item => {
                if (item.id == id) {
                    stunden = item.stunden;
                }
            });

            return stunden;
        } catch (error) {
            console.error('Fehler beim Abrufen des Fachs:', error);
            throw error;
        }

    }

    async function getAbt(id){
        try {
            const response = await fetch('../global/output.json');
            const data = await response.json();

            let abt = null;

            data.forEach(item => {
                if (item.id == id) {
                    abt = item.abteilung;
                }
            });

            return abt;
        } catch (error) {
            console.error('Fehler beim Abrufen des Fachs:', error);
            throw error;
        }

    }

    async function getJahr(id){
        try {
            const response = await fetch('../global/output.json');
            const data = await response.json();

            let jahr = null;

            data.forEach(item => {
                if (item.id == id) {
                    jahr = item.jahrgang;
                }
            });

            return jahr;
        } catch (error) {
            console.error('Fehler beim Abrufen des Fachs:', error);
            throw error;
        }

    }

    async function getInfos(id){
        try {
            const response = await fetch('../global/output.json');
            const data = await response.json();

            let infos = null;

            data.forEach(item => {
                if (item.id == id) {
                    infos = item.infos;
                }
            });

            return infos;
        } catch (error) {
            console.error('Fehler beim Abrufen des Fachs:', error);
            throw error;
        }

    }
</script>