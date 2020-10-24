<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DemandeAmiRepository")
 */
class DemandeAmi
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $amiStatut = 0;

    /**
     * @ORM\Column(type="datetime")
     */
    private $envoiDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $validationDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="amiAjout")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotEqualTo(propertyPath="userRec", message="Les deux users ne peuvent pas être identiques !")
     */
    private $userAjout;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="amiRec")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotEqualTo(propertyPath="userAjout", message="Les deux users ne peuvent pas être identiques !")
     */
    private $userRec;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmiStatut(): ?bool
    {
        return $this->amiStatut;
    }

    public function setAmiStatut(bool $amiStatut): self
    {
        $this->amiStatut = $amiStatut;

        return $this;
    }

    public function getEnvoiDate(): ?\DateTimeInterface
    {
        return $this->envoiDate;
    }

    public function setEnvoiDate(\DateTimeInterface $envoiDate): self
    {
        $this->envoiDate = $envoiDate;

        return $this;
    }

    public function getValidationDate(): ?\DateTimeInterface
    {
        return $this->validationDate;
    }

    public function setValidationDate(\DateTimeInterface $validationDate): self
    {
        $this->validationDate = $validationDate;

        return $this;
    }

    public function getUserAjout(): ?User
    {
        return $this->userAjout;
    }

    public function setUserAjout(?User $userAjout): self
    {
        $this->userAjout = $userAjout;

        return $this;
    }

    public function getUserRec(): ?User
    {
        return $this->userRec;
    }

    public function setUserRec(?User $userRec): self
    {
        $this->userRec = $userRec;

        return $this;
    }

    public function findHour(DateTime $date){
        return $date->format('H:i');
    }

    public function findDay(DateTime $date){
        return $date->format('d/m/Y');
    }
}
