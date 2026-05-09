<?php
require_once 'MODEL/Database.php';
$db = (new Database())->getConnection();
$stmt = $db->query("DESCRIBE users");
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
?>
