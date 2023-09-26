console.log('hello script')

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

// utilisation de select2 dans AmiType pour le select de l'allergie

$(function () {
  $(".selectAllergene").select2({});
});

// utilisation de select2 dans AmiType pour le select du régime alimentaire

$(function () {
  $(".selectRegime").select2({});
});


