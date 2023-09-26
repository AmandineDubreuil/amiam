const aperitifH2 = document.querySelector("#aperitifH2");
const aperitif = document.querySelector("#aperitif");

const entreeH2 = document.querySelector("#entreeH2");
const entree = document.querySelector("#entree");

const platH2 = document.querySelector("#platH2");
const plat = document.querySelector("#plat");

const dessertH2 = document.querySelector("#dessertH2");
const dessert = document.querySelector("#dessert");
//
const aperitifH2AmisPresents = document.querySelector("#aperitifH2AmisPresents");
const aperitifAmisPresents = document.querySelector("#aperitifAmisPresents");

const entreeH2AmisPresents = document.querySelector("#entreeH2AmisPresents");
const entreeAmisPresents = document.querySelector("#entreeAmisPresents");

const platH2AmisPresents = document.querySelector("#platH2AmisPresents");
const platAmisPresents = document.querySelector("#platAmisPresents");

const dessertH2AmisPresents = document.querySelector("#dessertH2AmisPresents");
const dessertAmisPresents = document.querySelector("#dessertAmisPresents");

const questionI = document.querySelector("#questionI");
const question = document.querySelector("#question");


function showHide(divId) {
  if (divId.className === "dnone") {
    divId.className = "dblock";
    
  } else {
    divId.className = "dnone";
  }
}
