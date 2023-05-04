<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PictureService
{
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function add(UploadedFile $picture, ?string $folder = '', ?int $width = 250, ?int $height = 250)
    {
        //on donne un nouveau nom à l'image
        $fichier = md5(uniqid(rand(), true)) . '.webp';

        //on récupère les infos de l'image
        $picture_infos = getimagesize($picture);

        if ($picture_infos == false) {
            throw new Exception('Format d\'image incorrect');
        }

        // On vérifie le format de l'image
        switch($picture_infos['mine']){
            case 'image/png':
                $picture_source = imagecreatefrompng($picture);
break;
            case 'image/jpeg':
                $picture_source = imagecreatefromjpeg($picture);
break;
            case 'image/webp':
                $picture_source = imagecreatefromwebp($picture);
break;
default:
                throw new Exception('Format d\'image incorrect');
        }

        // On recadre l'image
        $imageWidth = $picture_infos[0];
        $imageHeight = $picture_infos[1];

        // On vérifie l'orientation de l'image

    }
}