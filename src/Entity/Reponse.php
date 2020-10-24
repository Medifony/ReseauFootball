<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReponseRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Reponse
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(min=1, minMessage="La description doit faire au moins 1 caractère !")
     */
    private $commentaire;

    /**
     * @ORM\Column(type="datetime")
     */
    private $repDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="reponses")
     * @ORM\JoinColumn(nullable=null)
     */
    private $users;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Sujet", inversedBy="reponses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $sujets;

    /**
     * @ORM\OneToMany(targetEntity=Signalement::class, mappedBy="reponses")
     */
    private $signalements;

    public function __construct()
    {
        $this->signalements = new ArrayCollection();
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     * @return void
     */
    public function initializeDate()
    {
        if(empty($this->repDate)){
            date_default_timezone_set('Europe/Brussels');

            $this->repDate = (new DateTime('now'));
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(string $commentaire): self
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getRepDate(): ?\DateTimeInterface
    {
        return $this->repDate;
    }

    public function setRepDate(\DateTimeInterface $repDate): self
    {
        $this->repDate = $repDate;

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

    public function getSujets(): ?Sujet
    {
        return $this->sujets;
    }

    public function setSujets(?Sujet $sujets): self
    {
        $this->sujets = $sujets;

        return $this;
    }

    public function findHour(DateTime $date){
        return $date->format('H:i');
    }

    public function findDay(DateTime $date){
        return $date->format('d/m/Y');
    }

    /**
     * @return Collection|Signalement[]
     */
    public function getSignalements(): Collection
    {
        return $this->signalements;
    }

    public function addSignalement(Signalement $signalement): self
    {
        if (!$this->signalements->contains($signalement)) {
            $this->signalements[] = $signalement;
            $signalement->setReponses($this);
        }

        return $this;
    }

    public function removeSignalement(Signalement $signalement): self
    {
        if ($this->signalements->contains($signalement)) {
            $this->signalements->removeElement($signalement);
            // set the owning side to null (unless already changed)
            if ($signalement->getReponses() === $this) {
                $signalement->setReponses(null);
            }
        }

        return $this;
    }

}
