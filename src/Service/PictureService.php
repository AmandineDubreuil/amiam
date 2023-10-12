<?php

namespace App\Service;

use Exception;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class PictureService
{

    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function add(
        UploadedFile $picture,
        ?string $folder = '',
        ?int $width = 250,
        ?int $height = 250
    ) {
        // nouveau nom à l'image
        $fichier = md5(uniqid(rand(), true)) . '.webp';


        //récupère les infos (largeur hauteur...) de l'image
        $pictureInfos = getimagesize($picture);

        if ($pictureInfos === false) {
            throw new Exception('Format d\'image incorrect');
        }

        // on vérifie le format de l'image
        //dd(exif_read_data($picture));
        switch ($pictureInfos['mime']) {
            case 'image/png':
                $pictureSource = imagecreatefrompng($picture);
                break;
            case 'image/jpeg':
                $pictureSource = imagecreatefromjpeg($picture);
                break;
            case 'image/webp':
                $pictureSource = imagecreatefromwebp($picture);
                break;

            default:
                throw new Exception('Format d\'image incorrect');
        }

        // on recadre l'image et on récupère les dimensions
        $imageWidth = $pictureInfos[0];
        $imageHeight = $pictureInfos[1];

        // on vérifie l'orientation de l'image
        switch ($imageWidth <=> $imageHeight) {
            case -1: // si largeur inf à hauteur : portrait
                $squareSize = $imageWidth;
                $srcX = 0;
                $srcY = ($imageHeight - $squareSize) / 2;

                break;
            case 0: // si largeur égale à hauteur : carré
                $squareSize = $imageWidth;
                $srcX = 0;
                $srcY = 0;
                break;
            case 1: // si largeur sup à hauteur : paysage
                $squareSize = $imageHeight;
                $srcY = 0;
                $srcX = ($imageWidth - $squareSize) / 2;
                break;
        }
        // oncrée une nouvelle image vierge 
        $resizedPicture = imagecreatetruecolor($width, $height);

        imagecopyresampled($resizedPicture, $pictureSource, 0, 0, $srcX, $srcY, $width, $height, $squareSize, $squareSize);

        //on crée le chemin d'accès
        $path = $this->params->get('images_directory') . $folder;

        // on crée le dossier de destination s'il n'existe pas

        if (!file_exists($path . '/mini/')) {
            mkdir($path . '/mini/', 0755, true);
        }

        // on stock l'image recadrée
        if (exif_imagetype($picture) === 18) {
            imagewebp($resizedPicture, $path . '/mini/' . $width . 'x' . $height . '-' . $fichier);
        } else {
            if (isset(exif_read_data($picture)['Orientation'])) {
                if (exif_read_data($picture)['Orientation'] === 8) {

                    $degrees = 90;
                    $rotate = imagerotate($resizedPicture, $degrees, 0);
                    //AFFICHAGE
                    imagewebp($rotate, $path . '/mini/' . $width . 'x' . $height . '-' . $fichier);
                } else if (exif_read_data($picture)['Orientation'] === 6) {

                    $degrees = 270;
                    $rotate = imagerotate($resizedPicture, $degrees, 0);
                    //AFFICHAGE
                    imagewebp($rotate, $path . '/mini/' . $width . 'x' . $height . '-' . $fichier);
                } else {
                    imagewebp($resizedPicture, $path . '/mini/' . $width . 'x' . $height . '-' . $fichier);
                }
            } else {
                imagewebp($resizedPicture, $path . '/mini/' . $width . 'x' . $height . '-' . $fichier);
            }
        }
        $miniImage = $width . 'x' . $height . '-' . $fichier;

        // on déplace 
        $picture->move($path . '/', $fichier);

        // on supprime l'originiale
        $original = $path . '/' . $fichier;
        if (file_exists($original)) {
            unlink($original);
        }

        return $miniImage;
    }

    public function delete(
        string $fichier,
        ?string $folder = '',

    ) {

        if ($fichier !== 'default.webp') {
            $success = false;
            $path = $this->params->get('images_directory') . $folder;

            // suppression de la miniature
            $mini = $path . '/mini/' . $fichier;

            if (file_exists($mini)) {
                unlink($mini);
                $success = true;
            }

            return $success;
        }
        return false;
    }
}
