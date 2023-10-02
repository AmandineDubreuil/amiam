// console.log('hello select')

/*****************************
 *  UTILISATION DES SELECT2
 * 
 *****************************/

// utilisation de select2 dans RecetteIngredientType pour le select de l'ingredient et dans AmiType pour le select de l'aliment dégout

$(function () {
  $(".selectIngredient").select2({});
});

// utilisation de select2 dans RecetteIngredientType pour le select de l'unité de mesure

$(function () {
  $(".selectMesure").select2({});
});

// utilisation de select2 dans AmiType et AlimentType pour le select de l'allergie

$(function () {
  $(".selectAllergene").select2({});
});

// utilisation de select2 dans AmiType et AlimentType pour le select du régime alimentaire

$(function () {
  $(".selectRegime").select2({});
});

// utilisation de select2 dans AlimentType pour le select du sous-groupe d'aliment

$(function () {
  $(".selectSousGroupe").select2({});
});

// utilisation de select2 dans AlimentType pour le select des mois Saison

$(function () {
  $(".selectMoisL").select2({});
});
