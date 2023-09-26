const aperitifH2 = document.querySelector("#aperitifH2");
const aperitif = document.querySelector("#aperitif");

const entreeH2 = document.querySelector("#entreeH2");
const entree = document.querySelector("#entree");

const platH2 = document.querySelector("#platH2");
const plat = document.querySelector("#plat");

const dessertH2 = document.querySelector("#dessertH2");
const dessert = document.querySelector("#dessert");


function showHide(divId) {
  if (divId.className === "dnone") {
    divId.className = "dblock";
  } else {
    divId.className = "dnone";
  }
}
