<?php
/**
 * Created by PhpStorm.
 * User: hamid
 * Date: 07/07/2020
 * Time: 22:12
 */

namespace App\Services;


use App\Entity\Image;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageHandler{
    private $path;

    public function __construct( $path){
        $this->path = $path.'/oublic/images';
    }


    public function handle(Image $image){
        //recupere le file soumis
        $file = $image->getFile();
        $name = $this->createName($file);

        $image->setName($name);
        $file->move($this->path,$name);
    }

    private function createName(UploadedFile $file):string{
        return md5(uniqid(). $file->getClientOriginalName().'.'.$file->guessExtension());
    }

}