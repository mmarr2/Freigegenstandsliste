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
        error = "Bitte alle Felder ausfüllen \n";
        valid = false;
    }
    if(!checkEmail(email) && email.trim() != ""){
        error += "Bitte deine Schüleradresse verwenden!";
        valid = false;
    }

    if(jahrgang < 1 || jahrgang > 5){
        error += "Bitte validen Jahrgang angeben";
        valid = false;
    }

    document.getElementById("error").innerText = error;

    if (valid) {
        getFach(id).then(gegenstand => alert("Danke für deine Anmeldung bei dem Fach: " + gegenstand));
        getFach(id).then(gegenstand => alert(vorname + " " + nachname + " aus der Klasse " + jahrgang + "x" + abteilung + " hat sich für den Freigegenstand \"" +
            " " + gegenstand + "\" angemeldet"))
        //getFach(id).then(gegenstand => sendEmail(email, gegenstand, vorname, nachname, abteilung, jahrgang));
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
var nodemailer = require('nodemailer');

function sendEmail(email, fach, vname, nname, abteilung, jahrgang){
    var transporter = nodemailer.createTransport({
        service: 'outlook',
        auth: {
            user: 'youremail@gmail.com',
            pass: 'yourpassword'
        }
    });

    var mailOptions = {
        from: 'email@tgm.ac.at',
        to: email,
        subject: 'Anmeldung ' + fach,
        text: vname + " " + nname + " aus der Klasse " + jahrgang + "x" + abteilung + " hat sich für den Freigegenstand " +
            " " + fach + " angemeldet"
    };

    transporter.sendMail(mailOptions, function(error, info){
        if (error) {
            console.log(error);
        } else {
            console.log('Email sent: ' + info.response);
        }
    });
}