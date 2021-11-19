<?php

namespace App\Entity;

use App\Repository\ReponseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReponseRepository::class)
 */
class Reponse
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
    private $contenu;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reponses")
     */
    private $reponseU;

    /**
     * @ORM\ManyToOne(targetEntity=Etudiant::class, inversedBy="reponses")
     */
    private $reponseE;

    /**
     * @ORM\ManyToOne(targetEntity=Commentaire::class, inversedBy="reponses")
     */
    private $comments;

   

    public function __construct()
    {
        
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

    public function getCreateAt(): ?\DateTimeImmutable
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeImmutable $createAt): self
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getReponseU(): ?User
    {
        return $this->reponseU;
    }

    public function setReponseU(?User $reponseU): self
    {
        $this->reponseU = $reponseU;

        return $this;
    }

    public function getReponseE(): ?Etudiant
    {
        return $this->reponseE;
    }

    public function setReponseE(?Etudiant $reponseE): self
    {
        $this->reponseE = $reponseE;

        return $this;
    }

    public function getComments(): ?Commentaire
    {
        return $this->comments;
    }

    public function setComments(?Commentaire $comments): self
    {
        $this->comments = $comments;

        return $this;
    }

   

   

   
}
