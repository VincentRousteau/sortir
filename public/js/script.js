

let etats = Array.from(document.querySelectorAll(".card_etat"));

console.log(etats)
for (const etat of etats) {

    console.log(etat.innerText)
    switch (etat.innerText) {
        case 'Ouvert':
            etat.classList.add("green");
            break;
        case 'Passé':
            etat.classList.add("red");
            break;
        case 'En creation':
            etat.classList.add("blue");
            break;
        case 'En cours':
            etat.classList.add("yellow");
            break;
    }
}