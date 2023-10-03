// *********** AMIS INVITES ***********

//quand le user coche une famille, les membres de la famille sont cochés

let famille = document.forms[0].elements["familleAmisPourRecettes[]"];
let amis = document.forms[0].elements["amisPourRecettes[]"];

document.addEventListener("click", handleClick);




function handleClick(famille) {
  // si coche famille, alors chaque ami de la famille (à l'aide de la class) a sa case cochée

  if (famille.checked === true) {
    amisFamille = document.querySelectorAll(".famille" + famille.id);
    amisFamille.forEach((amis) => {
      amis.checked = true;
    });
  } 
  
  if (famille.checked === false) {
    amisFamille = document.querySelectorAll(".famille" + famille.id);
    amisFamille.forEach((amis) => {
      amis.checked = false;
    });
  };
amisPresentsFill(amis)
}

function amisPresentsFill(amis) {
let amisId = [];
  amis.forEach((ami) => { 
     if (ami.checked === true) {
        amisId += ami.attributes.value.nodeValue + ",";
       jsonAmisId = amisId.split(",").map(Number);

     }
  });
}

// *********** RECETTES CHOISIES ***********

// quand user choisit une recette, son aspect change
let recetteId = document.forms["aperitif"].elements["submitChoixRecette"];
//let recetteChoix = document.querySelector("#choixRecette" + recette)
console.dir(document.forms[1])
