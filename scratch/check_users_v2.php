<?php
require_once 'MODEL/Database.php';
$db = (new Database())->getConnection();
$stmt = $db->query("DESCRIBE users");
$cols = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach($cols as $c) {
    echo $c['Field'] . " (" . $c['Type'] . ")\n";
}
?>
