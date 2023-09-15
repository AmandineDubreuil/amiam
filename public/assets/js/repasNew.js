//quand le user coche une famille, les membres de la famille sont cochés

// définition famille et amis
let $famille = document.forms[0].elements["familleAmisPourRecettes[]"];
let $amis = document.forms[0].elements["amisPourRecettes[]"];

// écoute au clic
document.addEventListener("click", handleClick);

// fonction appelée au clic
function handleClick($famille) {

    // si coche famille, alors chaque ami de la famille (à l'aide de la class) a sa case cochée
    
    if ($famille.checked === true) {
    $amisFamille = document.querySelectorAll(".famille" + $famille.id);
    $amisFamille.forEach(($amis) => {
      $amis.checked = true;
    });

    console.dir($amis);
  }
}
