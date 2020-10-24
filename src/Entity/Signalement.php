<?php

namespace App\Entity;

use App\Repository\SignalementRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=SignalementRepository::class)
 * @UniqueEntity(
 *  fields={"sujets", "users"},
 *  message="Cet user a déjà signalé ce sujet.",
 * )
 * 
 * @UniqueEntity(
 *  fields={"reponses", "users"},
 *  message="Cet user a déjà signalé cette réponse.",
 * )
 */
class Signalement
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=3, max=255, minMessage="La raison doit faire plus de 2 caractères !", 
     *                                maxMessage="La raison ne peut pas faire plus de 255 caractères !")
     */
    private $raison;

    /**
     * @ORM\Column(type="boolean")
     */
    private $etat;

    /**
     * @ORM\ManyToOne(targetEntity=Sujet::class, inversedBy="signalements")
     * @ORM\JoinColumn(nullable=true)
     */
    private $sujets;

    /**
     * @ORM\ManyToOne(targetEntity=Reponse::class, inversedBy="signalements")
     * @ORM\JoinColumn(nullable=true)
     */
    private $reponses;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="signalements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $users;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRaison(): ?string
    {
        return $this->raison;
    }

    public function setRaison(?string $raison): self
    {
        $this->raison = $raison;

        return $this;
    }

    public function getEtat(): ?bool
    {
        return $this->etat;
    }

    public function setEtat(bool $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getSujets(): ?Sujet
    {
        return $this->sujets;
    }

    public function setSujets(?Sujet $sujets): self
    {
        $this->sujets = $sujets;

        return $this;
    }

    public function getReponses(): ?Reponse
    {
        return $this->reponses;
    }

    public function setReponses(?Reponse $reponses): self
    {
        $this->reponses = $reponses;

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
}
