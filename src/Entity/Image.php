<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass=ImageRepository::class)
 * @ORM\HasLifecycleCallbacks()
 *
 */
class Image
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;


    private $file;

    private $path;


    public function getPath()
    {
        return $this->path;
    }


    public function setPath($path): void
    {
        $this->path = $path;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setFile(UploadedFile $file): self
    {
        $this->file = $file;

        return $this;
    }

    /*
     * @ORM\PreFlush()
     */
    public function handle(){

        if ($this->file === null){
            return;
        }

        if ($this->id){
            unlink($this->path.'/'.$this->name);
        }
        //recupere le file soumis
        $name = $this->createName();
        $this->setName($name);
        $this->file->move($this->path,$name);
    }

    private function createName():string{
        return md5(uniqid()).$this->file->getClientOriginalName();
    }
}
