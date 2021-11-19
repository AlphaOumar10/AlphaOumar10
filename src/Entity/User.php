<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"users"})
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     * @Groups({"users"})
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity=Etudiant::class, mappedBy="user")
     * @Groups({"get"})
     */
    private $etudiants;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"users","id"})
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"users","id"})
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"users","id"})
     */
    private $pays;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $birthdayAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $photo;

    /**
     * @ORM\OneToMany(targetEntity=Publication::class, mappedBy="user")
     */
    private $publications;

    /**
     * @ORM\ManyToOne(targetEntity=Commentaire::class, inversedBy="users")
    */
    private $commentaires;

    /**
     * @ORM\OneToMany(targetEntity=Commentaire::class, mappedBy="userss")
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity=Messages::class, mappedBy="senderU", orphanRemoval=true)
     */
    private $sentUsers;

    /**
     * @ORM\OneToMany(targetEntity=Messages::class, mappedBy="recipientU", orphanRemoval=true)
     */
    private $receiveUsers;

    /**
     * @ORM\OneToMany(targetEntity=Groupe::class, mappedBy="user")
     */
    private $groupes;

    /**
     * @ORM\ManyToOne(targetEntity=Reponse::class, inversedBy="users")
     */
    private $reponse;

    /**
     * @ORM\OneToMany(targetEntity=Reponse::class, mappedBy="reponseU")
     */
    private $reponses;

    public function __construct()
    {
        $this->etudiants = new ArrayCollection();
        $this->birthdayAt = new DateTimeImmutable();
        $this->publications = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->sentUsers = new ArrayCollection();
        $this->receiveUsers = new ArrayCollection();
        $this->groupes = new ArrayCollection();
        $this->reponses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = '';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection|Etudiant[]
     */
    public function getEtudiants(): Collection
    {
        return $this->etudiants;
    }

    public function addEtudiant(Etudiant $etudiant): self
    {
        if (!$this->etudiants->contains($etudiant)) {
            $this->etudiants[] = $etudiant;
            $etudiant->setUser($this);
        }

        return $this;
    }

    public function removeEtudiant(Etudiant $etudiant): self
    {
        if ($this->etudiants->removeElement($etudiant)) {
            // set the owning side to null (unless already changed)
            if ($etudiant->getUser() === $this) {
                $etudiant->setUser(null);
            }
        }

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(?string $pays): self
    {
        $this->pays = $pays;

        return $this;
    }

    public function getBirthdayAt(): ?\DateTimeImmutable
    {
        return $this->birthdayAt;
    }

    public function setBirthdayAt(\DateTimeImmutable $birthdayAt): self
    {
        $this->birthdayAt = $birthdayAt;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * @return Collection|Publication[]
     */
    public function getPublications(): Collection
    {
        return $this->publications;
    }

    public function addPublication(Publication $publication): self
    {
        if (!$this->publications->contains($publication)) {
            $this->publications[] = $publication;
            $publication->setUser($this);
        }

        return $this;
    }

    public function removePublication(Publication $publication): self
    {
        if ($this->publications->removeElement($publication)) {
            // set the owning side to null (unless already changed)
            if ($publication->getUser() === $this) {
                $publication->setUser(null);
            }
        }

        return $this;
    }

    public function getCommentaires(): ?Commentaire
    {
        return $this->commentaires;
    }

    public function setCommentaires(?Commentaire $commentaires): self
    {
        $this->commentaires = $commentaires;

        return $this;
    }

    /**
     * @return Collection|Commentaire[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Commentaire $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setUserss($this);
        }

        return $this;
    }

    public function removeComment(Commentaire $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getUserss() === $this) {
                $comment->setUserss(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Messages[]
     */
    public function getSentUsers(): Collection
    {
        return $this->sentUsers;
    }

    public function addSentUser(Messages $sentUser): self
    {
        if (!$this->sentUsers->contains($sentUser)) {
            $this->sentUsers[] = $sentUser;
            $sentUser->setSenderU($this);
        }

        return $this;
    }

    public function removeSentUser(Messages $sentUser): self
    {
        if ($this->sentUsers->removeElement($sentUser)) {
            // set the owning side to null (unless already changed)
            if ($sentUser->getSenderU() === $this) {
                $sentUser->setSenderU(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Messages[]
     */
    public function getReceiveUsers(): Collection
    {
        return $this->receiveUsers;
    }

    public function addReceiveUser(Messages $receiveUser): self
    {
        if (!$this->receiveUsers->contains($receiveUser)) {
            $this->receiveUsers[] = $receiveUser;
            $receiveUser->setRecipientU($this);
        }

        return $this;
    }

    public function removeReceiveUser(Messages $receiveUser): self
    {
        if ($this->receiveUsers->removeElement($receiveUser)) {
            // set the owning side to null (unless already changed)
            if ($receiveUser->getRecipientU() === $this) {
                $receiveUser->setRecipientU(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Groupe[]
     */
    public function getGroupes(): Collection
    {
        return $this->groupes;
    }

    public function addGroupe(Groupe $groupe): self
    {
        if (!$this->groupes->contains($groupe)) {
            $this->groupes[] = $groupe;
            $groupe->setUser($this);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        if ($this->groupes->removeElement($groupe)) {
            // set the owning side to null (unless already changed)
            if ($groupe->getUser() === $this) {
                $groupe->setUser(null);
            }
        }

        return $this;
    }

    public function getReponse(): ?Reponse
    {
        return $this->reponse;
    }

    public function setReponse(?Reponse $reponse): self
    {
        $this->reponse = $reponse;

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
            $reponse->setReponseU($this);
        }

        return $this;
    }

    public function removeReponse(Reponse $reponse): self
    {
        if ($this->reponses->removeElement($reponse)) {
            // set the owning side to null (unless already changed)
            if ($reponse->getReponseU() === $this) {
                $reponse->setReponseU(null);
            }
        }

        return $this;
    }
}
