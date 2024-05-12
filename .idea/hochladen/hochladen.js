const fs = require("fs");
const { parse } = require("csv-parse");

function hochladen(datei) {
    let keys = [];
    let parsed = [];
    let first = true;
    let rowValid = true;
    let valid = true;
    let id = 0;

    fs.createReadStream(datei)
        .pipe(parse({ delimiter: ",", from_line: 2 }))
        .on("data", function (row, index) {
            if (first) {
                keys = ["id", "abteilung", "jahrgang", "gegenstand", "bezeichnung", "stunden", "email", "lehrer", "beschreibung", "infos"];
                first = false;
            } else {
                let obj = {};
                rowValid = true;
                row.slice(0, 9).forEach((value, i) => {
                    if (i === 0) {
                        obj[keys[i]] = id++;
                        obj[keys[i + 1]] = value;
                    } else {
                        if (value.trim() === "") {
                            rowValid = false;
                        } else {
                            if (i === 1) {
                                obj[keys[i + 1]] = formatJahrgaenge(value);
                            } else {
                                obj[keys[i + 1]] = value;
                            }
                        }
                    }
                });
                if (checkEmail(row[5]) && rowValid && checkJahrgang(row[1]) && checkAbteilung(row[0]) && checkStunden(row[4])) {
                    parsed.push(obj);
                } else {
                    valid = false;
                }
            }
        })
        .on("error", function (error) {
            console.log(error.message);
        })
        .on("end", function () {
            fs.writeFile("output.json", JSON.stringify(parsed, null, 2), function (err) {
                if (err) {
                    console.error("Error writing JSON file: ", err);
                } else {
                    console.log("JSON file created successfully.");
                }

            });
        });

    function checkEmail(email) {
        const regex = /\b[A-Za-z0-9._%+-]+@tgm.ac.at/
        return regex.test(email);
    }

    function checkJahrgang(jahrgang) {
        for (let i = 0; i < jahrgang.length(); i++) {
            if (!isNaN(jahrgang.charAt(i))) {
                const digit = parseInt(jahrgang.charAt(i), 10);
                if (digit < 1 || digit > 5) {
                    return false;
                }
            }
        }
        return !jahrgang.includes("-");
    }

    function checkAbteilung(abteilung) {
        let valid = false;
        let alle = abteilung.split(",");
        let abteilungen = ["HBG", "HET", "HEL", "HIT", "HKT", "HMB", "HWI", "Alle"];
        for (let i = 0; i < alle.length; i++) {
            if (!abteilungen.includes(alle[i].trim())) {
                return false;
            }
        }

        return true;
    }

    function formatJahrgaenge(jahrgaenge) {
        const numbers = jahrgaenge.trim().split(",").map(Number);
        if (numbers.length === 1) {
            return numbers[0].toString();
        }
        numbers.sort((a, b) => a - b);
        return `${numbers[0]}-${numbers[numbers.length - 1]}`;
    }

    function checkStunden(stunden) {
        return !isNaN(stunden) && Number.isInteger(parseFloat(stunden));

    }
}

// Exporting the function to be used in other modules
module.exports = { hochladen };
