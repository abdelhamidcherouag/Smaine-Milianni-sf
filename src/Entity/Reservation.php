<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ReservationRepository::class)
 */
class Reservation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\DateTime()
     * @Assert\GreaterThan("today")
     * @ORM\Column(type="datetime")
     */
    private $startAt;

    /**
     * @Assert\DateTime()
     * @Assert\GreaterThan(propertyPath="startAt")
     *@Assert\NotBlank(message="author.name.not_blank")
     * @ORM\Column(type="datetime")
     */
    private $endAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $validad;

    /**
     * @ORM\ManyToOne(targetEntity=Car::class, inversedBy="reservations")
     */
    private $cars;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reservations")
     */
    private $users;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reservationR")
     */
    private $userR;




    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartAt(): ?\DateTimeInterface
    {
        return $this->startAt;
    }

    public function setStartAt(\DateTimeInterface $startAt): self
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getEndAt(): ?\DateTimeInterface
    {
        return $this->endAt;
    }

    public function setEndAt(\DateTimeInterface $endAt): self
    {
        $this->endAt = $endAt;

        return $this;
    }

    public function getValidad(): ?bool
    {
        return $this->validad;
    }

    public function setValidad(bool $validad): self
    {
        $this->validad = $validad;

        return $this;
    }

    public function getCars(): ?Car
    {
        return $this->cars;
    }

    public function setCars(?Car $cars): self
    {
        $this->cars = $cars;

        return $this;
    }

    public function getUsers(): ?User
    {
        return $this->users;
    }

    public function setUsers(?User $users): self
    {
        $this->users = $users;

        return $this;
    }

    public function getUserR(): ?User
    {
        return $this->userR;
    }

    public function setUserR(?User $userR): self
    {
        $this->userR = $userR;

        return $this;
    }


}
