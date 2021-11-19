<?php

namespace App\Entity;

use App\Repository\CommentaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommentaireRepository::class)
 */
class Commentaire
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $contenu;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $createAt;

    /**
     * @ORM\ManyToOne(targetEntity=Publication::class, inversedBy="commentaires")
     */
    private $publication;

   
    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="commentaires")
     */
    private $users;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="comments")
     */
    private $userss;

    /**
     * @ORM\ManyToOne(targetEntity=Etudiant::class, inversedBy="commentaires")
     */
    private $etudiants;

    /**
     * @ORM\OneToMany(targetEntity=Reponse::class, mappedBy="comments")
     */
    private $reponses;

   
    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->reponses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(?string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeImmutable
    {
        return $this->createAt;
    }

    public function setCreateAt(?\DateTimeImmutable $createAt): self
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getPublication(): ?Publication
    {
        return $this->publication;
    }

    public function setPublication(?Publication $publication): self
    {
        $this->publication = $publication;

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
            $user->setCommentaires($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getCommentaires() === $this) {
                $user->setCommentaires(null);
            }
        }

        return $this;
    }

    public function getUserss(): ?User
    {
        return $this->userss;
    }

    public function setUserss(?User $userss): self
    {
        $this->userss = $userss;

        return $this;
    }

    public function getEtudiants(): ?Etudiant
    {
        return $this->etudiants;
    }

    public function setEtudiants(?Etudiant $etudiants): self
    {
        $this->etudiants = $etudiants;

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
            $reponse->setComments($this);
        }

        return $this;
    }

    public function removeReponse(Reponse $reponse): self
    {
        if ($this->reponses->removeElement($reponse)) {
            // set the owning side to null (unless already changed)
            if ($reponse->getComments() === $this) {
                $reponse->setComments(null);
            }
        }

        return $this;
    }

    
}
