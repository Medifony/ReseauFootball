<?php

namespace App\Entity;

use DateTime;
use Ramsey\Uuid\Uuid;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SujetRepository")
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(
 *  fields={"slug"},
 *  message="Un autre championnat possède déjà ce pays.",
 * )
 */
class Sujet
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\Length(min=3, max=100, minMessage="Le titre doit faire plus de 2 caractères !", 
     *                                maxMessage="Le titre ne peut pas faire plus de 100 caractères !")
     */
    private $titre;

    /**
     * @ORM\Column(type="text")
     *  @Assert\Length(min=1, minMessage="La description doit faire au moins 1 caractère !")
     */
    private $details;

    /**
     * @ORM\Column(type="datetime")
     */
    private $sujetDate;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Reponse", mappedBy="sujets")
     */
    private $reponses;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="sujets")
     * @ORM\JoinColumn(nullable=true)
     */
    private $users;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Championnat", inversedBy="sujets")
     * @ORM\JoinColumn(nullable=true)
     */
    private $championnats;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Club", inversedBy="sujets")
     * @ORM\JoinColumn(nullable=true))
     */
    private $clubs;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateModif;

    /**
     * @ORM\OneToMany(targetEntity=Signalement::class, mappedBy="sujets")
     */
    private $signalements;
    

    public function __construct()
    {
        $this->reponses = new ArrayCollection();
        $this->signalements = new ArrayCollection();
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     * @return void
     */
    public function initializeSlugDate()
    {
        if(empty($this->slug)){
            $uuid = Uuid::uuid4();
            $this->slug = $uuid->toString();
        }

        if(empty($this->sujetDate)){

            $this->sujetDate = (new DateTime('now'));
        }
    }

    /**
     * @ORM\PrePersist
     *
     * @return integer|null
     */
    public function initializeDateModif()
    {
        if(empty($this->dateModif)){
            $this->dateModif = (new DateTime('now'));
        }
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(string $details): self
    {
        $this->details = $details;

        return $this;
    }

    public function getSujetDate(): ?\DateTimeInterface
    {
        return $this->sujetDate;
    }

    public function setSujetDate(\DateTimeInterface $sujetDate): self
    {
        $this->sujetDate = $sujetDate;

        return $this;
    }

    /**
     * @return Collection|Reponse[]
     */
    public function getReponses(): Collection
    {
        return $this->reponses;
    }

    public function addReponse(Reponse $reponse): self
    {
        if (!$this->reponses->contains($reponse)) {
            $this->reponses[] = $reponse;
            $reponse->setSujets($this);
        }

        return $this;
    }

    public function removeReponse(Reponse $reponse): self
    {
        if ($this->reponses->contains($reponse)) {
            $this->reponses->removeElement($reponse);
            // set the owning side to null (unless already changed)
            if ($reponse->getSujets() === $this) {
                $reponse->setSujets(null);
            }
        }

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

    public function getChampionnats(): ?Championnat
    {
        return $this->championnats;
    }

    public function setChampionnats(?Championnat $championnats): self
    {
        $this->championnats = $championnats;

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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function findHour(DateTime $date){
        return $date->format('H:i');
    }

    public function findDay(DateTime $date){
        return $date->format('d/m/Y');
    }

    public function getDateModif(): ?\DateTimeInterface
    {
        return $this->dateModif;
    }

    public function setDateModif(\DateTimeInterface $dateModif): self
    {
        $this->dateModif = $dateModif;

        return $this;
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
            $signalement->setSujets($this);
        }

        return $this;
    }

    public function removeSignalement(Signalement $signalement): self
    {
        if ($this->signalements->contains($signalement)) {
            $this->signalements->removeElement($signalement);
            // set the owning side to null (unless already changed)
            if ($signalement->getSujets() === $this) {
                $signalement->setSujets(null);
            }
        }

        return $this;
    }

}
