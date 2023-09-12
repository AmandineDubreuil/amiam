# Amiam

Amiam est une application permettant à un utilisateur de choisir une recette parmi son catalogue de recettes en excluant les recettes qui contiennent des ingrédients que ses amis ne souhaitent pas manger.

## Requirements

- PHP 8.2
- Symfony 6.3.3
- MySql
- framework  <a href="https://useaxentix.com/">Axentix</a>

- JQuery :  <a href="https://select2.org/getting-started/installation">select2</a>
- CKEditor5

## Tokens

Tokens générés via JWT pour la vérification de l'adresse e-mail et la modification du mot de passe

## Bundles

- EasyAdminBundle 4

## User

### Vérification adresse e-mail user
- Services JWTService.php et SendMailService.php
- dans config/package/messenger commenter   # Symfony\Component\Mailer\Messenger\SendEmailMessage: async
- dans env.local mettre le secret JWT
- dans env.local modifier le mailer : MAILER_DSN=smtp://localhost:1025

### Modification mdp et mdp oublié user
- Services JWTService.php et SendMailService.php

### Avatars / photos des recettes
- utilisation d'un service PictureService.php

## Description d'une recette avec CKEditor5

- télécharger le dossier sur https://ckeditor.com/ckeditor-5/download/?null-addons= 
- l'installer dans public/assets/ckeditor5
- créer un fichier ckeditor.js dans assets/js
- mise en forme de la toolbar et des textes des headings dans ckeditor.js
- dans recetteType.php mettre le champs description en HiddenType
- dans recette/edit.html.twig, créer le formulaire, mettre une div avec "editor" en id
- dans recette/edit.html.twig, créer un block js avec lien vers ckeditor.js du dossier ckeditor5 et du dossier js
- pour que la mise en forme apparaisse, mettre {{ recette.description | raw  }} dans recette show 

## License

<a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/"><img alt="Creative Commons License" style="border-width:0" src="https://i.creativecommons.org/l/by-nc-sa/4.0/88x31.png" /></a><br />This work is licensed under a <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/">Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License</a>.
