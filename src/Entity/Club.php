<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClubRepository")
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(
 *  fields={"slug"},
 *  message="Un autre club possède déjà ce slug",
 * )
 * 
 * @UniqueEntity(
 *  fields={"apiId"},
 *  message="Un autre club possède déjà cet api.",
 * )
 */
class Club
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150)
     * @Assert\Length(min=3, max=150, minMessage="Le nom doit faire plus de 2 caractères !", 
     *                                maxMessage="Le nom ne peut pas faire plus de 150 caractères !")
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\Length(min=10, max=100, minMessage="Le lien vers le logo doit faire plus de 9 caractères !", 
     *                                maxMessage="Le lien vers le logo ne peut pas faire plus de 100 caractères !")
     */
    private $logo;

    /**
     * @ORM\Column(type="string", length=80, nullable=true)
     * @Assert\Length(min=3, max=80, minMessage="La navbar doit faire plus de 2 caractères !", 
     *                                maxMessage="La navbar ne peut pas faire plus de 80 caractères !")
     */
    private $navbar;

    /**
     * @ORM\Column(type="string", length=80)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Sujet", mappedBy="clubs")
     */
    private $sujets;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="clubs")
     */
    private $users;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Championnat", inversedBy="clubs")
     * @ORM\JoinColumn(nullable=true)
     */
    private $championnats;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Classement", mappedBy="clubs", cascade={"persist", "remove"})
     */
    private $classements;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Rencontre", mappedBy="clubDom")
     */
    private $rencontresDom;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Rencontre", mappedBy="clubExt")
     */
    private $rencontresExt;

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
        $this->users = new ArrayCollection();
        $this->rencontresDom = new ArrayCollection();
        $this->rencontresExt = new ArrayCollection();
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
            $sujet->setClubs($this);
        }

        return $this;
    }

    public function removeSujet(Sujet $sujet): self
    {
        if ($this->sujets->contains($sujet)) {
            $this->sujets->removeElement($sujet);
            // set the owning side to null (unless already changed)
            if ($sujet->getClubs() === $this) {
                $sujet->setClubs(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setClubs($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getClubs() === $this) {
                $user->setClubs(null);
            }
        }

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

    public function getClassements(): ?Classement
    {
        return $this->classements;
    }

    public function setClassements(?Classement $classements): self
    {
        $this->classements = $classements;

        // set (or unset) the owning side of the relation if necessary
        $newClubs = null === $classements ? null : $this;
        if ($classements->getClubs() !== $newClubs) {
            $classements->setClubs($newClubs);
        }

        return $this;
    }

    /**
     * @return Collection|Rencontre[]
     */
    public function getRencontresDom(): Collection
    {
        return $this->rencontresDom;
    }

    public function addRencontresDom(Rencontre $rencontresDom): self
    {
        if (!$this->rencontresDom->contains($rencontresDom)) {
            $this->rencontresDom[] = $rencontresDom;
            $rencontresDom->setClubDom($this);
        }

        return $this;
    }

    public function removeRencontresDom(Rencontre $rencontresDom): self
    {
        if ($this->rencontresDom->contains($rencontresDom)) {
            $this->rencontresDom->removeElement($rencontresDom);
            // set the owning side to null (unless already changed)
            if ($rencontresDom->getClubDom() === $this) {
                $rencontresDom->setClubDom(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Rencontre[]
     */
    public function getRencontresExt(): Collection
    {
        return $this->rencontresExt;
    }

    public function addRencontresExt(Rencontre $rencontresExt): self
    {
        if (!$this->rencontresExt->contains($rencontresExt)) {
            $this->rencontresExt[] = $rencontresExt;
            $rencontresExt->setClubExt($this);
        }

        return $this;
    }

    public function removeRencontresExt(Rencontre $rencontresExt): self
    {
        if ($this->rencontresExt->contains($rencontresExt)) {
            $this->rencontresExt->removeElement($rencontresExt);
            // set the owning side to null (unless already changed)
            if ($rencontresExt->getClubExt() === $this) {
                $rencontresExt->setClubExt(null);
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
