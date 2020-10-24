<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RencontreRepository")
 */
class Rencontre
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $matchDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Championnat", inversedBy="rencontres")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\EqualTo(propertyPath="clubDom.championnats", message="Le club domicile doit faire partie du championnat sélectionné !")
     * @Assert\EqualTo(propertyPath="clubExt.championnats", message="Le club extérieur doit faire partie du championnat sélectionné !")
     */
    private $championnats;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Club", inversedBy="rencontresDom")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotEqualTo(propertyPath="clubExt", message="Le club domicile doit être différent du club extérieur !")
     */
    private $clubDom;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Club", inversedBy="rencontresExt")
     * @ORM\JoinColumn(nullable=false)
     */
    private $clubExt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatchStatut(): ?string
    {
        return $this->matchStatut;
    }

    public function setMatchStatut(string $matchStatut): self
    {
        $this->matchStatut = $matchStatut;

        return $this;
    }

    public function getEntraineurD(): ?string
    {
        return $this->entraineurD;
    }

    public function setEntraineurD(string $entraineurD): self
    {
        $this->entraineurD = $entraineurD;

        return $this;
    }

    public function getEntraineurE(): ?string
    {
        return $this->entraineurE;
    }

    public function setEntraineurE(string $entraineurE): self
    {
        $this->entraineurE = $entraineurE;

        return $this;
    }

    public function getMatchDate(): ?\DateTimeInterface
    {
        return $this->matchDate;
    }

    public function setMatchDate(\DateTimeInterface $matchDate): self
    {
        $this->matchDate = $matchDate;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getChampionnats(): ?Championnat
    {
        return $this->championnats;
    }

    public function setChampionnats(?Championnat $championnats): self
    {
        $this->championnats = $championnats;

        return $this;
    }

    public function getClubDom(): ?Club
    {
        return $this->clubDom;
    }

    public function setClubDom(?Club $clubDom): self
    {
        $this->clubDom = $clubDom;

        return $this;
    }

    public function getClubExt(): ?Club
    {
        return $this->clubExt;
    }

    public function setClubExt(?Club $clubExt): self
    {
        $this->clubExt = $clubExt;

        return $this;
    }

    public function findHour(DateTime $date){
        return $date->format('H:i');
    }

    public function findDay(DateTime $date){
        return $date->format('d/m/Y');
    }
}
