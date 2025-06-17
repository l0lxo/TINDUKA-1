<?php
session_start();
include('config.php');

if ($_SERVER["REQUEST_METHOD"] != "POST" || !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$firstname = trim($_POST['firstname']);
$lastname = trim($_POST['lastname']);
$bio = trim($_POST['bio']);

// Initialize profile picture URL
$profile_picture = null;

// Handle file upload if present
if (!empty($_FILES['profile_picture']['name']) && $_FILES['profile_picture']['error'] == UPLOAD_ERR_OK) {
    $upload_dir = 'uploads/profiles/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    $file_ext = pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
    $filename = 'profile_' . $user_id . '.' . $file_ext;
    $target_path = $upload_dir . $filename;
    
    if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_path)) {
        $profile_picture = $target_path;
    }
}

// Build update query
$sql = "UPDATE users SET firstname = ?, lastname = ?, bio = ?";
$params = [$firstname, $lastname, $bio];
$types = "sss";

if ($profile_picture) {
    $sql .= ", profile_picture = ?";
    $params[] = $profile_picture;
    $types .= "s";
}

$sql .= " WHERE user_id = ?";
$params[] = $user_id;
$types .= "i";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    // Update session variables
    $_SESSION['firstname'] = $firstname;
    $_SESSION['lastname'] = $lastname;
    if ($profile_picture) {
        $_SESSION['profile_picture'] = $profile_picture;
    }
    header("Location: profile.php?success=Profile updated successfully");
} else {
    header("Location: profile.php?error=Error updating profile");
}

$stmt->close();
$conn->close();
?>