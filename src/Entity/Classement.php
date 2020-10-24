<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * @ORM\Entity(repositoryClass="App\Repository\ClassementRepository")
 * @UniqueEntity(
 *  fields={"clubs"},
 *  message="Un autre classement possède déjà ce club.",
 * )
 */
class Classement
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *      min = 0,
     *      max = 30,
     *      invalidMessage="L'id doit être entre 0 et 30")
     */
    private $place;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *      min = 0,
     *      max = 150,
     *      invalidMessage="L'id doit être entre 0 et 150")
     */
    private $total;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *      min = 0,
     *      max = 150,
     *      invalidMessage="L'id doit être entre 0 et 150")
     */
    private $victoires;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *      min = 0,
     *      max = 150,
     *      invalidMessage="L'id doit être entre 0 et 150")
     */
    private $nuls;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *      min = 0,
     *      max = 150,
     *      invalidMessage="L'id doit être entre 0 et 150")
     */
    private $defaites;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *      min = 0,
     *      max = 200,
     *      invalidMessage="L'id doit être entre 0 et 200")
     */
    private $points;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *      min = 0,
     *      max = 1000,
     *      invalidMessage="L'id doit être entre 0 et 1000")
     */
    private $buts;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *      min = 0,
     *      max = 1000,
     *      invalidMessage="L'id doit être entre 0 et 150")
     */
    private $encaisse;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Club", inversedBy="classements", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $clubs;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlace(): ?int
    {
        return $this->place;
    }

    public function setPlace(int $place): self
    {
        $this->place = $place;

        return $this;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(int $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getVictoires(): ?int
    {
        return $this->victoires;
    }

    public function setVictoires(int $victoires): self
    {
        $this->victoires = $victoires;

        return $this;
    }

    public function getNuls(): ?int
    {
        return $this->nuls;
    }

    public function setNuls(int $nuls): self
    {
        $this->nuls = $nuls;

        return $this;
    }

    public function getDefaites(): ?int
    {
        return $this->defaites;
    }

    public function setDefaites(int $defaites): self
    {
        $this->defaites = $defaites;

        return $this;
    }

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(int $points): self
    {
        $this->points = $points;

        return $this;
    }

    public function getButs(): ?int
    {
        return $this->buts;
    }

    public function setButs(int $buts): self
    {
        $this->buts = $buts;

        return $this;
    }

    public function getEncaisse(): ?int
    {
        return $this->encaisse;
    }

    public function setEncaisse(int $encaisse): self
    {
        $this->encaisse = $encaisse;

        return $this;
    }

    public function getClubs(): ?Club
    {
        return $this->clubs;
    }

    public function setClubs(?Club $clubs): self
    {
        $this->clubs = $clubs;

        return $this;
    }
}
