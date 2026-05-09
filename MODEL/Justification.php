<?php
class Justification {
    private int $id;
    private int $demande_id;
    private string $document;
    private string $description;

    public function __construct($demande_id, $document, $description) {
        $this->demande_id  = $demande_id;
        $this->document    = $document;
        $this->description = $description;
    }

    // Getters
    public function getDemandeId()   { return $this->demande_id; }
    public function getDocument()    { return $this->document; }
    public function getDescription() { return $this->description; }

    // Setters
    public function setId(int $id)            { $this->id = $id; }
    public function setDocument(string $d)    { $this->document = $d; }
    public function setDescription(string $d) { $this->description = $d; }
}
