<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

use App\Repository\CarRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CarRepository::class)
 */
class Car
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="Le modéle ne peut etre vide !")
     * @ORM\Column(type="string", length=255)
     */
    private $model;

    /**
     * @Assert\NotBlank(message="Le prix ne peut etre vide !")
     *
     * @Assert\LessThan(
     *     value=6000, message="Maximun 6000"
     * )
     * @Assert\GreaterThan(
     *     value=100,
     *     message="Minimum 100"
     * )
     * @ORM\Column(type="integer")
     */
    private $price;

    public function getId(): ?int
    {
        return $this->id;
    }

    /*
     * @ORM\Column(nullable=true)
     * @ORM\OneToOne(targetEntity="Image", cascade=("persist", "remove"))
     */
    private $image;

    /**
     * @ORM\OneToMany(targetEntity="Keyword", mappedBy="car", cascade={"persist","remove"})
     */
    private $keywords;

    /**
     * @ORM\ManyToMany(targetEntity=City::class, inversedBy="cars")
     */
    private $cities;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $color;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $carburent;

    /**
     * @ORM\OneToMany(targetEntity=Reservation::class, mappedBy="cars")
     */
    private $reservations;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="cars")
     */
    private $user;


    /**
     * @param mixed $id
     */
    public function __construct()
    {
        $this->keywords = new ArrayCollection();
        $this->cities = new ArrayCollection();
        $this->reservations = new ArrayCollection();

    }

    public function getModel()
    {
        return $this->model;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return mixed
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    public function addKeyword(Keyword $keyword){
        $this->keywords->add($keyword);
        $keyword->setCar($this);
    }

    public function removeKeyword(Keyword $keyword){
        $this->keywords->removeElement($keyword);
    }

    /**
     * @return Collection|City[]
     */
    public function getCities(): Collection
    {
        return $this->cities;
    }

    public function addCity(City $city): self
    {
        if (!$this->cities->contains($city)) {
            $this->cities[] = $city;
        }

        return $this;
    }

    public function removeCity(City $city): self
    {
        if ($this->cities->contains($city)) {
            $this->cities->removeElement($city);
        }

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getCarburent(): ?string
    {
        return $this->carburent;
    }

    public function setCarburent(string $carburent): self
    {
        $this->carburent = $carburent;

        return $this;
    }

    /**
     * @return Collection|Reservation[]
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations[] = $reservation;
            $reservation->setCars($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->contains($reservation)) {
            $this->reservations->removeElement($reservation);
            // set the owning side to null (unless already changed)
            if ($reservation->getCars() === $this) {
                $reservation->setCars(null);
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }


}
