<?php

namespace App\Form;

use App\Entity\Campus;

class EntiteFormulaire
{
    private ?Campus $campus = null;

    private  ?string $recherche = null;

    private ?\DateTime $dateDebut = null;

    private ?\DateTime $dateFin = null;

    private ?bool $sortiesOrganisees = null;

    private ?bool $sortiesInscrit = null;

    private ?bool $sortiesNonInscrit = null;

    private ?bool $sortiesPasses = null;


    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(Campus $campus): self
    {
        $this->campus = $campus;

        return $this;
    }
    public function getRecherche(): ?string
    {
        return $this->recherche;
    }

    public function setRecherche(?string $recherche): void
    {
        $this->recherche = $recherche;
    }

    public function getDateDebut(): ?\DateTime
    {
        return $this->dateDebut;
    }

    public function setDateDebut(?\DateTime $dateDebut): void
    {
        $this->dateDebut = $dateDebut;
    }

    public function getDateFin(): ?\DateTime
    {
        return $this->dateFin;
    }

    public function setDateFin(?\DateTime $dateFin): void
    {
        $this->dateFin = $dateFin;
    }

    public function getSortiesOrganisees(): ?bool
    {
        return $this->sortiesOrganisees;
    }

    public function setSortiesOrganisees(?bool $sortiesOrganisees): void
    {
        $this->sortiesOrganisees = $sortiesOrganisees;
    }

    public function getSortiesInscrit(): ?bool
    {
        return $this->sortiesInscrit;
    }

    public function setSortiesInscrit(?bool $sortiesInscrit): void
    {
        $this->sortiesInscrit = $sortiesInscrit;
    }

    public function getSortiesNonInscrit(): ?bool
    {
        return $this->sortiesNonInscrit;
    }

    public function setSortiesNonInscrit(?bool $sortiesNonInscrit): void
    {
        $this->sortiesNonInscrit = $sortiesNonInscrit;
    }

    public function getSortiesPasses(): ?bool
    {
        return $this->sortiesPasses;
    }

    public function setSortiesPasses(?bool $sortiesPasses): void
    {
        $this->sortiesPasses = $sortiesPasses;
    }
}
