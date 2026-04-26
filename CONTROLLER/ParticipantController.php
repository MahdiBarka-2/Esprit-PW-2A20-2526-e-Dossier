<?php
require_once '../../MODEL/Database.php';
require_once '../../MODEL/ParticipantModel.php';

class ParticipantC
{
     private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }
    public function addParticipant($data)
    {
        
        try {
            $req = $this->db->prepare('INSERT INTO participations (event_id, user_id, nom, prenom, age)
                     VALUES (:event_id, :user_id, :nom, :prenom, :age)');
$req->execute([
    'event_id' => $data['event_id'],
    'user_id'  => $data['user_id'],
    'nom'      => $data['nom'],
    'prenom'   => $data['prenom'],
    'age'      => $data['age'],
            ]);
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    public function deleteParticipant($event_id, $user_id)
    {
        
        try {
            $req = $this->db->prepare('DELETE FROM participations
                                 WHERE event_id = :event_id AND user_id = :user_id');
            $req->execute([
                'event_id' => $event_id,
                'user_id'  => $user_id,
            ]);
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    public function findByEvent($event_id)
    {
        
        try {
            $req = $this->db->prepare('SELECT * FROM participations WHERE event_id = :event_id');
            $req->execute(['event_id' => $event_id]);
            return $req->fetchAll();
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    public function countByEvent($event_id)
    {
        
        try {
            $req = $this->db->prepare('SELECT COUNT(*)
FROM participations p
JOIN evenements e ON p.event_id = e.id
WHERE p.event_id = :event_id;');
            $req->execute(['event_id' => $event_id]);
            return (int) $req->fetchColumn();
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    public function isJoined($event_id, $user_id)
    {
        
        try {
            $req = $this->db->prepare('SELECT p.id
FROM participations p
JOIN evenements e ON p.event_id = e.id
WHERE p.event_id = :event_id
AND p.user_id = :user_id;');
            $req->execute([
                'event_id' => $event_id,
                'user_id'  => $user_id,
            ]);
            return (bool) $req->fetch();
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }
     public function getAllCounts()
    {
        
        try {
            $rows = $this->db->query('
                SELECT event_id, COUNT(id) AS cnt
                FROM participations
                GROUP BY event_id
            ')->fetchAll();
            $map = [];
            foreach ($rows as $r) {
                $map[$r['event_id']] = (int) $r['cnt'];
            }
            return $map;
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }
}
