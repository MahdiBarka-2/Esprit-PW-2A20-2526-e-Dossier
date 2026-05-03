<?php
require_once(__DIR__ . "/../Config/database.php");

class Job {
    private int $id;
    private string $titre;
    private string $lieu;
    private string $type;
    private string $description;
    private string $date_limite;

    public function __construct(
        string $titre       = "",
        string $lieu        = "",
        string $type        = "",
        string $description = "",
        string $date_limite = ""
    ) {
        $this->titre       = $titre;
        $this->lieu        = $lieu;
        $this->type        = $type;
        $this->description = $description;
        $this->date_limite = $date_limite;
    }

    public function getId()          { return $this->id; }
    public function getTitre()       { return $this->titre; }
    public function getLieu()        { return $this->lieu; }
    public function getType()        { return $this->type; }
    public function getDescription() { return $this->description; }
    public function getDateLimite()  { return $this->date_limite; }
}
?>