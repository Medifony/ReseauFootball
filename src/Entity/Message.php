<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MessageRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Message
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(min=1, max=255, minMessage="Le message ne doit pas être vide !", 
     *                                maxMessage="Le message ne peut pas faire plus de 255 caractères !")
     */
    private $contenu;

    /**
     * @ORM\Column(type="datetime")
     */
    private $contDate;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="messages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $users;

    /**
     * @ORM\ManyToOne(targetEntity=Conversation::class, inversedBy="messages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $conversations;

    private $mine;

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     * @return void
     */

    public function initializeDate()
    {
        if(empty($this->contDate)){
            date_default_timezone_set('Europe/Brussels');

            $this->contDate = (new DateTime('now'));
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getContDate(): ?\DateTimeInterface
    {
        return $this->contDate;
    }

    public function setContDate(\DateTimeInterface $contDate): self
    {
        $this->contDate = $contDate;

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

    public function getConversations(): ?Conversation
    {
        return $this->conversations;
    }

    public function setConversations(?Conversation $conversations): self
    {
        $this->conversations = $conversations;

        return $this;
    }

    public function getMine(): ?string
    {
        return $this->mine;
    }

    public function setMine(string $mine): self
    {
        $this->mine = $mine;

        return $this;
    }

    public function findHour(DateTime $date){
        return $date->format('H:i');
    }

    public function findDay(DateTime $date){
        return $date->format('d/m/Y');
    }

}
