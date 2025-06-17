<?php
require 'config.php';

$result = $conn->query("SELECT photo_id, photo_url FROM photos");
while ($row = $result->fetch_assoc()) {
    $fixed_url = 'uploads/photos/' . basename($row['photo_url']);
    
    $stmt = $conn->prepare("UPDATE photos SET photo_url = ? WHERE photo_id = ?");
    $stmt->bind_param("si", $fixed_url, $row['photo_id']);
    $stmt->execute();
}

echo "Fixed " . $conn->affected_rows . " photo URLs";
?>