<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ChampionnatRepository")
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(
 *  fields={"slug"},
 *  message="Un autre championnat possède déjà ce slug.",
 * )
 * 
 * @UniqueEntity(
 *  fields={"payss"},
 *  message="Un autre championnat possède déjà ce pays.",
 * )
 * 
 * @UniqueEntity(
 *  fields={"apiId"},
 *  message="Un autre championnat possède déjà cet api.",
 * )
 * 
 */
class Championnat
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     * @Assert\Length(min=3, max=30, minMessage="Le nom doit faire plus de 2 caractères !", 
     *                                maxMessage="Le nom ne peut pas faire plus de 30 caractères !")
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\Length(min=10, max=100, minMessage="Le lien vers le logo doit faire plus de 9 caractères !", 
     *                                maxMessage="Le lien vers le logo ne peut pas faire plus de 100 caractères !")
     */
    private $logo;

    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     * @Assert\Length(min=3, max=15, minMessage="La navbar doit faire plus de 2 caractères !", 
     *                                maxMessage="La navbar ne peut pas faire plus de 15 caractères !")
     */
    private $navbar;

    /**
     * @ORM\Column(type="string", length=30)
     * @Assert\Length(min=3, max=30, minMessage="Le slug doit faire plus de 2 caractères !", 
     *                                maxMessage="Le slug ne peut pas faire plus de 30 caractères !")
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Sujet", mappedBy="championnats")
     */
    private $sujets;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Club", mappedBy="championnats")
     */
    private $clubs;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Pays", inversedBy="championnats", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $payss;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Rencontre", mappedBy="championnats")
     */
    private $rencontres;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Range(
     *      min = 0,
     *      max = 9999999,
     *      invalidMessage="L'id doit être entre 0 et 9999999")
     */
    private $apiId;

    public function __construct()
    {
        $this->sujets = new ArrayCollection();
        $this->clubs = new ArrayCollection();
        $this->rencontres = new ArrayCollection();
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     * @return void
     */
    public function initializeSlug()
    {
        if(empty($this->slug)){
            $slugify = new Slugify();
            $this->slug = $slugify->slugify($this->nom);
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getNavbar(): ?string
    {
        return $this->navbar;
    }

    public function setNavbar(?string $navbar): self
    {
        $this->navbar = $navbar;

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

    /**
     * @return Collection|Sujet[]
     */
    public function getSujets(): Collection
    {
        return $this->sujets;
    }

    public function addSujet(Sujet $sujet): self
    {
        if (!$this->sujets->contains($sujet)) {
            $this->sujets[] = $sujet;
            $sujet->setChampionnats($this);
        }

        return $this;
    }

    public function removeSujet(Sujet $sujet): self
    {
        if ($this->sujets->contains($sujet)) {
            $this->sujets->removeElement($sujet);
            // set the owning side to null (unless already changed)
            if ($sujet->getChampionnats() === $this) {
                $sujet->setChampionnats(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Club[]
     */
    public function getClubs(): Collection
    {
        return $this->clubs;
    }

    public function addClub(Club $club): self
    {
        if (!$this->clubs->contains($club)) {
            $this->clubs[] = $club;
            $club->setChampionnats($this);
        }

        return $this;
    }

    public function removeClub(Club $club): self
    {
        if ($this->clubs->contains($club)) {
            $this->clubs->removeElement($club);
            // set the owning side to null (unless already changed)
            if ($club->getChampionnats() === $this) {
                $club->setChampionnats(null);
            }
        }

        return $this;
    }

    public function getPayss(): ?Pays
    {
        return $this->payss;
    }

    public function setPayss(Pays $payss): self
    {
        $this->payss = $payss;

        return $this;
    }

    /**
     * @return Collection|Rencontre[]
     */
    public function getRencontres(): Collection
    {
        return $this->rencontres;
    }

    public function addRencontre(Rencontre $rencontre): self
    {
        if (!$this->rencontres->contains($rencontre)) {
            $this->rencontres[] = $rencontre;
            $rencontre->setChampionnats($this);
        }

        return $this;
    }

    public function removeRencontre(Rencontre $rencontre): self
    {
        if ($this->rencontres->contains($rencontre)) {
            $this->rencontres->removeElement($rencontre);
            // set the owning side to null (unless already changed)
            if ($rencontre->getChampionnats() === $this) {
                $rencontre->setChampionnats(null);
            }
        }

        return $this;
    }

    public function getApiId(): ?int
    {
        return $this->apiId;
    }

    public function setApiId(int $apiId): self
    {
        $this->apiId = $apiId;

        return $this;
    }
}
