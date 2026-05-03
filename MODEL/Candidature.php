<?php
class Candidature {
    private int $id;
    private string $nom;
    private string $email;
    private int $job_id;
    private string $reference;
    private ?string $message;

    public function __construct(
        string $nom = "",
        string $email = "",
        int $job_id = 0,
        string $reference = "",
        ?string $message = null
    ) {
        $this->nom       = $nom;
        $this->email     = $email;
        $this->job_id    = $job_id;
        $this->reference = $reference;
        $this->message   = $message;
    }

    public function getNom() { return $this->nom; }
    public function getEmail() { return $this->email; }
    public function getJobId() { return $this->job_id; }
    public function getReference() { return $this->reference; }
    public function getMessage() { return $this->message; }
}
?>