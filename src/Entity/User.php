<?php

namespace App\Entity;

use DateTime;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\EquatableInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks
 * @Vich\Uploadable
 * 
 * @UniqueEntity(
 *  fields={"pseudo"},
 *  message="Un autre user possède déjà ce pseudo",
 * )
 * 
 * @UniqueEntity(
 *  fields={"slug"},
 *  message="Un autre user possède déjà ce slug",
 * )
 * 
 * @UniqueEntity(
 *  fields={"email"},
 *  message="Un autre user possède déjà ce mail",
 * )
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $filename;

    /**
     * @var File|null
     * @Assert\Image(mimeTypes="image/jpeg", mimeTypesMessage="Le format doit être en jpeg.")
     * )
     * @Vich\UploadableField(mapping="user_image", fileNameProperty="filename")
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=35)
     * @Assert\Length(min=3, max=35, minMessage="Le prénom doit faire plus de 2 caractères !", 
     *                                maxMessage="Le prénom ne peut pas faire plus de 35 caractères !")
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=35)
     * @Assert\Length(min=3, max=35, minMessage="Le nom doit faire plus de 2 caractères !", 
     *                                maxMessage="Le nom ne peut pas faire plus de 35 caractères !")
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=30)
     * @Assert\Length(min=3, max=30, minMessage="Le nom doit faire plus de 2 caractères !", 
     *                                maxMessage="Le nom ne peut pas faire plus de 30 caractères !")
     */
    private $pseudo;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\Email(
     *     message = "Le mail n'est pas valide.")
     * @Assert\Length(min=5, max=100, minMessage="Le nom doit faire plus de 2 caractères !", 
     *                                maxMessage="Le nom ne peut pas faire plus de 100 caractères !")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $hash;

    /**
     * @Assert\EqualTo(propertyPath="hash", message="Vous avez fait une erreur pendant la confirmation.")
     */
    public $passwordConfirm;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DemandeAmi", mappedBy="userAjout")
     */
    private $amiAjout;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\DemandeAmi", mappedBy="userRec")
     */
    private $amiRec;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Reponse", mappedBy="users")
     */
    private $reponses;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Sujet", mappedBy="users")
     */
    private $sujets;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Club", inversedBy="users")
     * @ORM\JoinColumn(nullable=true)
     * 
     */
    private $clubs;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Pays", inversedBy="users")
     * @ORM\JoinColumn(nullable=true)
     */
    private $payss;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateCrea;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=5, minMessage="La description doit faire au moins 5 caractères !")
     */
    private $presentation;

    /**
     * @ORM\ManyToMany(targetEntity=Role::class, mappedBy="users")
     */
    private $userRoles;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="users")
     */
    private $messages;

    /**
     * @ORM\OneToMany(targetEntity=Participant::class, mappedBy="users")
     */
    private $participants;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\OneToMany(targetEntity=Signalement::class, mappedBy="users")
     */
    private $signalements;

    public function __construct()
    {
        $this->amiAjout = new ArrayCollection();
        $this->amiRec = new ArrayCollection();
        $this->reponses = new ArrayCollection();
        $this->sujets = new ArrayCollection();
        $this->userRoles = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->participants = new ArrayCollection();
        $this->signalements = new ArrayCollection();
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
            $this->slug = $slugify->slugify($this->pseudo);
        }
        
        if(empty($this->dateCrea)){
            date_default_timezone_set('Europe/Brussels');

            $this->dateCrea = (new DateTime('now'));
        }
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
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

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

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
     * @return Collection|DemandeAmi[]
     */
    public function getAmiAjout(): Collection
    {
        return $this->amiAjout;
    }

    public function addAmiAjout(DemandeAmi $amiAjout): self
    {
        if (!$this->amiAjout->contains($amiAjout)) {
            $this->amiAjout[] = $amiAjout;
            $amiAjout->setUserAjout($this);
        }

        return $this;
    }

    public function removeAmiAjout(DemandeAmi $amiAjout): self
    {
        if ($this->amiAjout->contains($amiAjout)) {
            $this->amiAjout->removeElement($amiAjout);
            // set the owning side to null (unless already changed)
            if ($amiAjout->getUserAjout() === $this) {
                $amiAjout->setUserAjout(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|DemandeAmi[]
     */
    public function getAmiRec(): Collection
    {
        return $this->amiRec;
    }

    public function addAmiRec(DemandeAmi $amiRec): self
    {
        if (!$this->amiRec->contains($amiRec)) {
            $this->amiRec[] = $amiRec;
            $amiRec->setUserRec($this);
        }

        return $this;
    }

    public function removeAmiRec(DemandeAmi $amiRec): self
    {
        if ($this->amiRec->contains($amiRec)) {
            $this->amiRec->removeElement($amiRec);
            // set the owning side to null (unless already changed)
            if ($amiRec->getUserRec() === $this) {
                $amiRec->setUserRec(null);
            }
        }

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
            $reponse->setUsers($this);
        }

        return $this;
    }

    public function removeReponse(Reponse $reponse): self
    {
        if ($this->reponses->contains($reponse)) {
            $this->reponses->removeElement($reponse);
            // set the owning side to null (unless already changed)
            if ($reponse->getUsers() === $this) {
                $reponse->setUsers(null);
            }
        }

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
            $sujet->setUsers($this);
        }

        return $this;
    }

    public function removeSujet(Sujet $sujet): self
    {
        if ($this->sujets->contains($sujet)) {
            $this->sujets->removeElement($sujet);
            // set the owning side to null (unless already changed)
            if ($sujet->getUsers() === $this) {
                $sujet->setUsers(null);
            }
        }

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

    public function getPayss(): ?Pays
    {
        return $this->payss;
    }

    public function setPayss(?Pays $payss): self
    {
        $this->payss = $payss;

        return $this;
    }

    public function getRoles() {

        $roles = $this->userRoles->map(function($role){
            return $role->getTitle();
        })->toArray();

        $roles[] = 'ROLE_USER';

        return $roles;
    }

    public function getPassword() {
        return $this->hash;
    }

    public function getSalt() {}

    public function getUserName(){
        return $this->pseudo;
    }

    public function eraseCredentials(){}

    public function getDateCrea(): ?\DateTimeInterface
    {
        return $this->dateCrea;
    }

    public function setDateCrea(\DateTimeInterface $dateCrea): self
    {
        $this->dateCrea = $dateCrea;

        return $this;
    }

    public function getPresentation(): ?string
    {
        return $this->presentation;
    }

    public function setPresentation(?string $presentation): self
    {
        $this->presentation = $presentation;

        return $this;
    }

    /**
     * @return Collection|Role[]
     */
    public function getUserRoles(): Collection
    {
        return $this->userRoles;
    }

    public function addUserRole(Role $userRole): self
    {
        if (!$this->userRoles->contains($userRole)) {
            $this->userRoles[] = $userRole;
            $userRole->addUser($this);
        }

        return $this;
    }

    public function removeUserRole(Role $userRole): self
    {
        if ($this->userRoles->contains($userRole)) {
            $this->userRoles->removeElement($userRole);
            $userRole->removeUser($this);
        }

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setUsers($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->contains($message)) {
            $this->messages->removeElement($message);
            // set the owning side to null (unless already changed)
            if ($message->getUsers() === $this) {
                $message->setUsers(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Participant[]
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(Participant $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
            $participant->setUsers($this);
        }

        return $this;
    }

    public function removeParticipant(Participant $participant): self
    {
        if ($this->participants->contains($participant)) {
            $this->participants->removeElement($participant);
            // set the owning side to null (unless already changed)
            if ($participant->getUsers() === $this) {
                $participant->setUsers(null);
            }
        }

        return $this;
    }

    public function findHour(DateTime $date){
        return $date->format('H:i');
    }

    public function findDay(DateTime $date){
        return $date->format('d/m/Y');
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(?string $filename): User
    {
        $this->filename = $filename;
        return $this;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageFile(?File $imageFile): User
    {
        $this->imageFile = $imageFile;
        if($this->imageFile instanceof UploadedFile )
        {
            $this->updated_at = new \DateTime('now');
        }
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function serialize() {

        return serialize(array(
            $this->id,
            $this->pseudo,
            $this->hash,
        ));
    }
        
    public function unserialize($serialized) {
        list (
            $this->id,
            $this->pseudo,
            $this->hash,
        ) = unserialize($serialized);
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
            $signalement->setUsers($this);
        }

        return $this;
    }

    public function removeSignalement(Signalement $signalement): self
    {
        if ($this->signalements->contains($signalement)) {
            $this->signalements->removeElement($signalement);
            // set the owning side to null (unless already changed)
            if ($signalement->getUsers() === $this) {
                $signalement->setUsers(null);
            }
        }

        return $this;
    }

}
