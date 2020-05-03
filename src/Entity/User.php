<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(
 *      fields = {"login"},
 *      message = "Login déjà utilisé"
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $login;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adresse;

    /**
     * @ORM\Column(type="date")
     */
    private $dateEmbauche;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isComptable;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\FicheForfait", mappedBy="idVisiteur", orphanRemoval=true)
     */
    private $ficheForfaits;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\FicheHorsForfait", mappedBy="idVisiteur")
     */
    private $ficheHorsForfaits;

    public function __construct()
    {
        $this->ficheForfaits = new ArrayCollection();
        $this->ficheHorsForfaits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getDateEmbauche(): ?\DateTimeInterface
    {
        return $this->dateEmbauche;
    }

    public function setDateEmbauche(\DateTimeInterface $dateEmbauche): self
    {
        $this->dateEmbauche = $dateEmbauche;

        return $this;
    }

    public function getIsComptable(): ?bool
    {
        return $this->isComptable;
    }

    public function setIsComptable(bool $isComptable): self
    {
        $this->isComptable = $isComptable;

        return $this;
    }

    public function eraseCredentials(){}

    public function getSalt(){}

    public function getRoles(): array{
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @return Collection|FicheForfait[]
     */
    public function getFicheForfaits(): Collection
    {
        return $this->ficheForfaits;
    }

    public function addFicheForfait(FicheForfait $ficheForfait): self
    {
        if (!$this->ficheForfaits->contains($ficheForfait)) {
            $this->ficheForfaits[] = $ficheForfait;
            $ficheForfait->setIdVisiteur($this);
        }

        return $this;
    }

    public function removeFicheForfait(FicheForfait $ficheForfait): self
    {
        if ($this->ficheForfaits->contains($ficheForfait)) {
            $this->ficheForfaits->removeElement($ficheForfait);
            // set the owning side to null (unless already changed)
            if ($ficheForfait->getIdVisiteur() === $this) {
                $ficheForfait->setIdVisiteur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|FicheHorsForfait[]
     */
    public function getFicheHorsForfaits(): Collection
    {
        return $this->ficheHorsForfaits;
    }

    public function addFicheHorsForfait(FicheHorsForfait $ficheHorsForfait): self
    {
        if (!$this->ficheHorsForfaits->contains($ficheHorsForfait)) {
            $this->ficheHorsForfaits[] = $ficheHorsForfait;
            $ficheHorsForfait->setIdVisiteur($this);
        }

        return $this;
    }

    public function removeFicheHorsForfait(FicheHorsForfait $ficheHorsForfait): self
    {
        if ($this->ficheHorsForfaits->contains($ficheHorsForfait)) {
            $this->ficheHorsForfaits->removeElement($ficheHorsForfait);
            // set the owning side to null (unless already changed)
            if ($ficheHorsForfait->getIdVisiteur() === $this) {
                $ficheHorsForfait->setIdVisiteur(null);
            }
        }

        return $this;
    }
}
