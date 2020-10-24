<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PaysRepository")
 * @UniqueEntity(
 *  fields={"code"},
 *  message="Un autre user possède déjà ce slug",
 * )
 * @UniqueEntity(
 *  fields={"alpha2"},
 *  message="Un autre user possède déjà ce slug",
 * )
 * @UniqueEntity(
 *  fields={"alpha3"},
 *  message="Un autre user possède déjà ce slug",
 * )
 */
class Pays
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="payss")
     */
    private $users;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Championnat", mappedBy="payss", cascade={"persist", "remove"})
     */
    private $championnats;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\Length(min=3, max=100, minMessage="Le nom doit faire plus de 2 caractères !", 
     *                                maxMessage="Le nom ne peut pas faire plus de 100 caractères !")
     */
    private $nomFr;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\Length(min=3, max=100, minMessage="Le nom doit faire plus de 2 caractères !", 
     *                                maxMessage="Le nom ne peut pas faire plus de 100 caractères !")
     */
    private $nomEn;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *      min = 0,
     *      max = 9999999,
     *      invalidMessage="L'id doit être entre 0 et 9999999")
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=5)
     */
    private $alpha2;

    /**
     * @ORM\Column(type="string", length=5)
     * @Assert\Length(min=3, max=5, minMessage="Le code alpha3 doit faire plus de 2 caractères !", 
     *                                maxMessage="Le code alpha3 ne peut pas faire plus de 5 caractères !")
     */
    private $alpha3;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
            $user->setPayss($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getPayss() === $this) {
                $user->setPayss(null);
            }
        }

        return $this;
    }

    public function getChampionnats(): ?Championnat
    {
        return $this->championnats;
    }

    public function setChampionnats(Championnat $championnats): self
    {
        $this->championnats = $championnats;

        // set the owning side of the relation if necessary
        if ($championnats->getPayss() !== $this) {
            $championnats->setPayss($this);
        }

        return $this;
    }

    public function getNomFr(): ?string
    {
        return $this->nomFr;
    }

    public function setNomFr(string $nomFr): self
    {
        $this->nomFr = $nomFr;

        return $this;
    }

    public function getNomEn(): ?string
    {
        return $this->nomEn;
    }

    public function setNomEn(string $nomEn): self
    {
        $this->nomEn = $nomEn;

        return $this;
    }

    public function getCode(): ?int
    {
        return $this->code;
    }

    public function setCode(int $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getAlpha2(): ?string
    {
        return $this->alpha2;
    }

    public function setAlpha2(string $alpha2): self
    {
        $this->alpha2 = $alpha2;

        return $this;
    }

    public function getAlpha3(): ?string
    {
        return $this->alpha3;
    }

    public function setAlpha3(string $alpha3): self
    {
        $this->alpha3 = $alpha3;

        return $this;
    }
}
