fetch('../global/output.json')
    .then(response => response.json())
    .then(data => {
        const dataContainer = document.getElementById('dataContainer');

        function createDivElements(data) {
            const abteilungSelect = document.getElementById("abteilung");
            const jahrgangInput = document.getElementById("jahrgang");
            const keyword = document.getElementById("suche");
            const abteilung = abteilungSelect.value;
            const jahrgang = jahrgangInput.value;

            const createGegenstandDiv = (item) => {
                const divElement = document.createElement('div');
                divElement.classList.add('gegenstand');

                const innerDiv = document.createElement('div');
                innerDiv.classList.add('subject-card');

                const link = document.createElement('a');
                link.addEventListener("click", function(){
                    window.location.href = '../anmelden/anmelden.html?id=' + item.id;
                });
                const img = document.createElement('img');
                img.src = './img/placeholder.png';
                img.alt = 'Placeholder';
                img.classList.add('subject-image');
                link.appendChild(img);
                innerDiv.appendChild(link);

                const infoDiv = document.createElement('div');
                infoDiv.classList.add('info');

                const fachLink = document.createElement('a');
                fachLink.addEventListener("click", function(){
                    window.location.href = '../anmelden/anmelden.html?id=' + item.id;
                });
                fachLink.href = '../anmelden/anmelden.html';
                fachLink.style.textDecoration = 'none';
                fachLink.style.color = 'black';
                fachLink.style.fontWeight = 'bolder';
                const h1Title = document.createElement('h2');
                h1Title.classList.add("titel")
                h1Title.textContent = item.gegenstand;
                fachLink.appendChild(h1Title);
                infoDiv.appendChild(fachLink);

                const textDiv = document.createElement('div');
                textDiv.classList.add('text');
                const p1 = document.createElement('p');
                p1.textContent = `${item.jahrgang}Jhg`;
                const p2 = document.createElement('p');
                p2.textContent = item.abteilung;
                textDiv.appendChild(p1);
                textDiv.appendChild(p2);
                infoDiv.appendChild(textDiv);

                innerDiv.appendChild(infoDiv);

                const hr = document.createElement('hr');
                innerDiv.appendChild(hr);

                const beschreibungDiv = document.createElement('div');
                beschreibungDiv.classList.add('beschreibung');
                const pKurz = document.createElement('p');
                pKurz.classList.add('kurz');
                pKurz.textContent = item.beschreibung;
                beschreibungDiv.appendChild(pKurz);

                innerDiv.appendChild(beschreibungDiv);

                divElement.appendChild(innerDiv);

                return divElement;
            };

            dataContainer.innerHTML = '';

            data.forEach(item => {
                if (((abteilung === "" || isAbteilung(item.abteilung, abteilung)) &&
                        (jahrgang === "" || isInRange(item.jahrgang, jahrgang))) &&
                    (keyword.value.trim() === "" || searchAll(keyword, item))
                ) {
                    const gegenstandDiv = createGegenstandDiv(item);
                    dataContainer.appendChild(gegenstandDiv);
                }
            });

        }

        document.getElementById("abteilung").addEventListener("change", createDivElements.bind(null, data));
        document.getElementById("jahrgang").addEventListener("input", createDivElements.bind(null, data));
        document.getElementById("suche").addEventListener("input", createDivElements.bind(null, data));
        createDivElements(data);
    })
    .catch(error => {
        console.error('Fehler beim Laden der JSON-Datei:', error);
    });

function isInRange(itemJahrgang, filterJahrgang) {
    if (itemJahrgang.includes("-")) {
        const grenzen = itemJahrgang.split("-");
        const untereGrenze = parseInt(grenzen[0], 10);
        const obereGrenze = parseInt(grenzen[1], 10);
        const jahrgang = parseInt(filterJahrgang, 10);
        console.log(untereGrenze + obereGrenze + jahrgang);
        return jahrgang >= untereGrenze && jahrgang <= obereGrenze;
    } else {
        return parseInt(itemJahrgang, 10) === parseInt(filterJahrgang, 10);
    }
}

function isAbteilung(itemAbteilung, filterAbteilung){
    if (itemAbteilung.includes("Alle")) return true;
    const abteilungen = itemAbteilung.split(",");
    console.log(abteilungen.includes(filterAbteilung));
    return abteilungen.includes(filterAbteilung);
}

function searchAll(keyword, item){
    const searchTerm = keyword.value.toLowerCase();
    const abteilung = item.abteilung.toLowerCase();
    const gegenstand = item.gegenstand.toLowerCase();
    const beschreibung = item.beschreibung.toLowerCase();
    const lehrer = item.lehrer.toLowerCase();
    const infos = item.infos.toLowerCase();


    return (
        abteilung.includes(searchTerm) ||
        gegenstand.includes(searchTerm) ||
        beschreibung.includes(searchTerm) ||
        lehrer.includes(searchTerm) ||
        infos.includes(searchTerm)
    );
}
