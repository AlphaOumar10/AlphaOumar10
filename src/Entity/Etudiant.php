<?php

namespace App\Entity;

use App\Repository\EtudiantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=EtudiantRepository::class)
 */
class Etudiant implements UserInterface,PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"list"})
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"list"})
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Groups({"list,groupe"})
     */
    private $codeE;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"list,groupe"})
     */
    private $ville;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"list"})
     */
    private $pays;

     /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Groups({"list,groupe"})
     */
    private $password;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="etudiants")
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Groups({"list,groupe"})
     */
    private $email;

    /**
     * @ORM\Column(type="json")
    */
    private $roles = [];

    /**
     * @ORM\ManyToOne(targetEntity=Groupe::class, inversedBy="etudiants")
     */
    private $groupe;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $birthdayAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $photo;

    /**
     * @ORM\OneToMany(targetEntity=Publication::class, mappedBy="etudiant")
     */
    private $publications;

    /**
     * @ORM\OneToMany(targetEntity=Commentaire::class, mappedBy="etudiants")
     */
    private $commentaires;

    /**
     * @ORM\OneToMany(targetEntity=Messages::class, mappedBy="senderE", orphanRemoval=true)
     */
    private $senderEtudiant;

    /**
     * @ORM\OneToMany(targetEntity=Messages::class, mappedBy="recipientE", orphanRemoval=true)
     */
    private $receiveEtudiants;

    /**
     * @ORM\OneToMany(targetEntity=Reponse::class, mappedBy="reponseE")
     */
    private $reponses;

   
    public function __construct()
    {
        $this->publications = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
        $this->senderEtudiant = new ArrayCollection();
        $this->receiveEtudiants = new ArrayCollection();
        $this->reponses = new ArrayCollection();
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getCodeE(): ?string
    {
        return $this->codeE;
    }

    public function setCodeE(string $codeE): self
    {
        $this->codeE = $codeE;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(string $pays): self
    {
        $this->pays = $pays;

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
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_ETUDIANT';

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
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
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


    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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

    public function getGroupe(): ?Groupe
    {
        return $this->groupe;
    }

    public function setGroupe(?Groupe $groupe): self
    {
        $this->groupe = $groupe;

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
            $publication->setEtudiant($this);
        }

        return $this;
    }

    public function removePublication(Publication $publication): self
    {
        if ($this->publications->removeElement($publication)) {
            // set the owning side to null (unless already changed)
            if ($publication->getEtudiant() === $this) {
                $publication->setEtudiant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Commentaire[]
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setEtudiants($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getEtudiants() === $this) {
                $commentaire->setEtudiants(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Messages[]
     */
    public function getSenderEtudiant(): Collection
    {
        return $this->senderEtudiant;
    }

    public function addSenderEtudiant(Messages $senderEtudiant): self
    {
        if (!$this->senderEtudiant->contains($senderEtudiant)) {
            $this->senderEtudiant[] = $senderEtudiant;
            $senderEtudiant->setSenderE($this);
        }

        return $this;
    }

    public function removeSenderEtudiant(Messages $senderEtudiant): self
    {
        if ($this->senderEtudiant->removeElement($senderEtudiant)) {
            // set the owning side to null (unless already changed)
            if ($senderEtudiant->getSenderE() === $this) {
                $senderEtudiant->setSenderE(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Messages[]
     */
    public function getReceiveEtudiants(): Collection
    {
        return $this->receiveEtudiants;
    }

    public function addReceiveEtudiant(Messages $receiveEtudiant): self
    {
        if (!$this->receiveEtudiants->contains($receiveEtudiant)) {
            $this->receiveEtudiants[] = $receiveEtudiant;
            $receiveEtudiant->setRecipientE($this);
        }

        return $this;
    }

    public function removeReceiveEtudiant(Messages $receiveEtudiant): self
    {
        if ($this->receiveEtudiants->removeElement($receiveEtudiant)) {
            // set the owning side to null (unless already changed)
            if ($receiveEtudiant->getRecipientE() === $this) {
                $receiveEtudiant->setRecipientE(null);
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
            $reponse->setReponseE($this);
        }

        return $this;
    }

    public function removeReponse(Reponse $reponse): self
    {
        if ($this->reponses->removeElement($reponse)) {
            // set the owning side to null (unless already changed)
            if ($reponse->getReponseE() === $this) {
                $reponse->setReponseE(null);
            }
        }

        return $this;
    }

   
}
