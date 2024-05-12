<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Anmelden</title>
    <link rel="stylesheet" href="anmelden.css">
    <script src="anmelden.js"></script>
</head>
<body>
<div>

    <div className="form">


        <h1 id="header">Anmelden</h1>
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
        <input type="email" id="email" placeholder="E-Mail"/>
        <div id="error"></div>
        <button id="anmelden" onclick="anmelden()">Anmelden!</button>
    </div>

</div>
</body>
</html>

<script>
    const urlParams = new URLSearchParams(window.location.search);
    const id = urlParams.get('id');


    function anmelden(){
        let valid = true;
        let vorname = document.getElementById("vorname").value;
        let nachname = document.getElementById("nachname").value;
        let jahrgang = document.getElementById("jahrgang").value;
        let e = document.getElementById("abteilung");
        let abteilung = e.options[e.selectedIndex].text;
        let email = document.getElementById("email").value;
        let error = "";
        if(vorname.trim() == "" || nachname.trim() == "" || jahrgang == ""
            || abteilung == "Abteilung" || email.trim() == ""){
            error += "Bitte alle Felder ausfüllen \n";
            valid = false;
        }
        if(!checkEmail(email) && email.trim() != ""){
            error += "Bitte deine Schüleradresse verwenden\n";
            valid = false;
        }

        if(jahrgang < 1 || jahrgang > 5){
            error += "Bitte validen Jahrgang angeben\n";
            valid = false;
        }

        document.getElementById("error").innerText = error;

        if (valid) {
            getFach(id).then(gegenstand => alert("Danke für deine Anmeldung bei dem Fach: " + gegenstand));
            getFach(id).then(gegenstand => alert(vorname + " " + nachname + " aus der Klasse " + jahrgang + "x" + abteilung + " hat sich für den Freigegenstand \"" +
                " " + gegenstand + "\" angemeldet"))
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
</script>