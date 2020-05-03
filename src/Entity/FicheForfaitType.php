<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FicheForfaitTypeRepository")
 */
class FicheForfaitType
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
     * @ORM\Column(type="float")
     */
    private $montant;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\FicheForfait", mappedBy="idType")
     */
    private $ficheForfaits;

    /**
     * @ORM\Column(type="string", length=3)
     */
    private $idForfaitType;

    public function __construct()
    {
        $this->ficheForfaits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): self
    {
        $this->montant = $montant;

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
            $ficheForfait->setIdType($this);
        }

        return $this;
    }

    public function removeFicheForfait(FicheForfait $ficheForfait): self
    {
        if ($this->ficheForfaits->contains($ficheForfait)) {
            $this->ficheForfaits->removeElement($ficheForfait);
            // set the owning side to null (unless already changed)
            if ($ficheForfait->getIdType() === $this) {
                $ficheForfait->setIdType(null);
            }
        }

        return $this;
    }

    public function getIdForfaitType(): ?string
    {
        return $this->idForfaitType;
    }

    public function setIdForfaitType(string $idForfaitType): self
    {
        $this->idForfaitType = $idForfaitType;

        return $this;
    }
}
