<?php

class Evenement
{
    private int $id;
    private string $titre;
    private string $description;
    private string $date_debut;
    private string $date_fin;
    private string $lieu;
    private int $capacite_max;
    private string $statut;

     public function __construct(
    string $titre = "",
    string $date_debut = "",
    string $statut = "active",
    ?string $description = null,
    ?string $date_fin = null,
    ?string $lieu = null,
    ?int $capacite_max = null
) {
        $this->titre = $titre;
        $this->date_debut = $date_debut;
        $this->statut = $statut;
        $this->description = $description;
        $this->date_fin = $date_fin;
        $this->lieu = $lieu;
        $this->capacite_max = $capacite_max;
    }

    public function getTitre()
    {
        return $this->titre;
    }

    public function getDateDebut()
    {
        return $this->date_debut;
    }

    public function getStatut()
    {
        return $this->statut;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getDateFin()
    {
        return $this->date_fin;
    }

    public function getLieu()
    {
        return $this->lieu;
    }

    public function getCapaciteMax()
    {
        return $this->capacite_max;
    }
}