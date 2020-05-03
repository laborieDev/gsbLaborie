<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EtatRepository")
 */
class Etat
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
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\FicheHorsForfait", mappedBy="idEtat")
     */
    private $ficheHorsForfaits;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $idEtat;

    public function __construct()
    {
        $this->ficheHorsForfaits = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

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
            $ficheHorsForfait->setIdEtat($this);
        }

        return $this;
    }

    public function removeFicheHorsForfait(FicheHorsForfait $ficheHorsForfait): self
    {
        if ($this->ficheHorsForfaits->contains($ficheHorsForfait)) {
            $this->ficheHorsForfaits->removeElement($ficheHorsForfait);
            // set the owning side to null (unless already changed)
            if ($ficheHorsForfait->getIdEtat() === $this) {
                $ficheHorsForfait->setIdEtat(null);
            }
        }

        return $this;
    }

    public function getIdEtat(): ?string
    {
        return $this->idEtat;
    }

    public function setIdEtat(string $idEtat): self
    {
        $this->idEtat = $idEtat;

        return $this;
    }
}
