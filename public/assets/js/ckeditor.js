// initialise ckeditor (en fonction version https://ckeditor.com/ckeditor-5/download/?null-addons=)
console.log("ckeditor.js");
console.dir(document.querySelector(".recetteNewEdit form button"));



InlineEditor
.create( document.querySelector( '#editor' ), {

  // toolbar
  toolbar: [ 'undo', 'redo', 
  '|', 'heading',
  '|', 'bold', 'italic', 'subscript',
  '|', 'link', 'blockQuote',
  '|', 'bulletedList', 'numberedList', 'outdent', 'indent' ],
  shouldNotGroupWhenFull: true,

  //heading
  heading: {
    options: [
        { model: 'paragraph', title: 'Paragraphe', class: 'ck-heading_paragraph' },
        { model: 'heading1', view: 'h1', title: 'Titre 1', class: 'ck-heading_heading1' },
        { model: 'heading2', view: 'h2', title: 'Titre 2', class: 'ck-heading_heading2' },
        { model: 'heading3', view: 'h3', title: 'Titre 3', class: 'ck-heading_heading3' }

    ]
}
} )
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

