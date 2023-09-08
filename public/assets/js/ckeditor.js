// initialise ckeditor (en fonction version https://ckeditor.com/ckeditor-5/download/?null-addons=)
console.log("ckeditor.js");
console.dir(document.querySelector(".recetteNewEdit form button"));

InlineEditor
.create( document.querySelector( '#editor' ) )
.catch( error => {
    console.error( error );
} ).then((editor) => {
    document
      .querySelector(".recetteNewEdit form")
      .addEventListener("submit", function (e) {
        e.preventDefault();
        this.querySelector("#recette_description").value = editor.getData(); 
        this.submit();
      });
  });

