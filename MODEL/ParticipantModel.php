<?php

class Participant
{
    private int    $id;
    private int    $event_id;
    private string $user_id;
    private string $joined_at;
    private string $nom;
    private string $prenom;
    private int    $age;

    public function __construct(
        int    $event_id  = 0,
        string $user_id   = "",
        ?string $joined_at = null
    ) {
        $this->event_id  = $event_id;
        $this->user_id   = $user_id;
        $this->joined_at = $joined_at;
    }

    public function getEventId()  { return $this->event_id;  }
    public function getUserId()   { return $this->user_id;   }
    public function getJoinedAt() { return $this->joined_at; }
}
