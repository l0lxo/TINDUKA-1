<?php
session_start();
include('config.php');

if ($_SERVER["REQUEST_METHOD"] != "POST" || !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$photo_id = $_POST['photo_id'];
$user_id = $_SESSION['user_id'];

// Verify user owns the photo before deleting
$stmt = $conn->prepare("DELETE FROM photos WHERE photo_id = ? AND user_id = ?");
$stmt->bind_param("ii", $photo_id, $user_id);

if ($stmt->execute()) {
    header("Location: profile.php?success=Photo deleted successfully");
} else {
    header("Location: profile.php?error=Error deleting photo");
}

$stmt->close();
$conn->close();
?>