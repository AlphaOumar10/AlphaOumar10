<?php

namespace App\Entity;

use App\Repository\MessagesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MessagesRepository::class)
 */
class Messages
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $message;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $create_At;

    /**
     * @ORM\Column(type="boolean")
     */
    private $is_read;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="sentUsers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $senderU;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="receiveUsers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $recipientU;

    /**
     * @ORM\ManyToOne(targetEntity=Etudiant::class, inversedBy="senderEtudiant")
     * @ORM\JoinColumn(nullable=false)
     */
    private $senderE;

    /**
     * @ORM\ManyToOne(targetEntity=Etudiant::class, inversedBy="receiveEtudiants")
     * @ORM\JoinColumn(nullable=false)
     */
    private $recipientE;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeImmutable
    {
        return $this->create_At;
    }

    public function setCreateAt(\DateTimeImmutable $create_At): self
    {
        $this->create_At = $create_At;

        return $this;
    }

    public function getIsRead(): ?bool
    {
        return $this->is_read;
    }

    public function setIsRead(bool $is_read): self
    {
        $this->is_read = $is_read;

        return $this;
    }

    public function getSenderU(): ?User
    {
        return $this->senderU;
    }

    public function setSenderU(?User $senderU): self
    {
        $this->senderU = $senderU;

        return $this;
    }

    public function getRecipientU(): ?User
    {
        return $this->recipientU;
    }

    public function setRecipientU(?User $recipientU): self
    {
        $this->recipientU = $recipientU;

        return $this;
    }

    public function getSenderE(): ?Etudiant
    {
        return $this->senderE;
    }

    public function setSenderE(?Etudiant $senderE): self
    {
        $this->senderE = $senderE;

        return $this;
    }

    public function getRecipientE(): ?Etudiant
    {
        return $this->recipientE;
    }

    public function setRecipientE(?Etudiant $recipientE): self
    {
        $this->recipientE = $recipientE;

        return $this;
    }
}
