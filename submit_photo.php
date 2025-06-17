<?php
session_start();
include('config.php');

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $caption = trim($_POST['caption']);
    $county = trim($_POST['county']);
    $location = trim($_POST['location']);

    // Validate inputs
    if (empty($caption) || empty($county) || empty($location)) {
        $error = "All fields are required";
    } elseif (!isset($_FILES['photo']) || $_FILES['photo']['error'] != UPLOAD_ERR_OK) {
        $error = "Please select a valid photo to upload";
    } else {
        // Create uploads directory if it doesn't exist
        $upload_dir = __DIR__ . '/uploads/photos/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        // Generate unique filename
        $file_ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $filename = 'photo_' . time() . '.' . $file_ext;
        $target_path = 'uploads/photos/' . $filename;
        // Move uploaded file
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_path)) {
            // Store relative path in database
            $photo_url = $target_path;
            
            $stmt = $conn->prepare("INSERT INTO photos (user_id, photo_url, caption, county, location) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("issss", $user_id, $photo_url, $caption, $county, $location);

            if ($stmt->execute()) {
                $success = "Photo uploaded successfully!";
                $_POST = array(); // Clear form
            } else {
                $error = "Database error: " . $conn->error;
                unlink($target_path); // Delete uploaded file if DB insert fails
            }
            $stmt->close();
        } else {
            $error = "Error moving uploaded file";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Upload Photo - TINDUKA</title>
  <style>
    body {
      font-family: 'Helvetica Neue', Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f5f5f5;
      color: #333;
    }
    
    .upload-container {
      max-width: 600px;
      margin: 30px auto;
      padding: 30px;
      background-color: white;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    h2 {
      margin-top: 0;
      color: #4CAF50;
    }
    
    .form-group {
      margin-bottom: 20px;
    }
    
    label {
      display: block;
      margin-bottom: 8px;
      font-weight: bold;
    }
    
    input[type="text"],
    input[type="file"],
    select,
    textarea {
      width: 100%;
      padding: 12px;
      border: 1px solid #ddd;
      border-radius: 4px;
      font-size: 16px;
    }
    
    textarea {
      height: 100px;
      resize: vertical;
    }
    
    .preview-container {
      margin: 20px 0;
      text-align: center;
    }
    
    .preview-image {
      max-width: 100%;
      max-height: 300px;
      border-radius: 4px;
      display: none;
    }
    
    .submit-btn {
      background-color: #4CAF50;
      color: white;
      border: none;
      padding: 12px 20px;
      font-size: 16px;
      border-radius: 4px;
      cursor: pointer;
      transition: background-color 0.3s;
    }
    
    .submit-btn:hover {
      background-color: #45a049;
    }
    
    .error {
      color: #f44336;
      margin-bottom: 20px;
    }
    
    .success {
      color: #4CAF50;
      margin-bottom: 20px;
    }
  </style>
</head>
<body>
  <?php include 'includes/navbar.php'; ?>
  
  <div class="upload-container">
    <h2>Upload a New Photo</h2>
    
    <?php if ($error): ?>
      <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
    <?php if ($success): ?>
      <div class="success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>
    
    <form action="submit_photo.php" method="POST" enctype="multipart/form-data">
      <div class="form-group">
        <label for="photo">Photo</label>
        <input type="file" id="photo" name="photo" accept="image/*" required>
        <div class="preview-container">
          <img id="previewImage" class="preview-image" src="#" alt="Preview">
        </div>
      </div>
      
      <div class="form-group">
        <label for="caption">Caption</label>
        <input type="text" id="caption" name="caption" value="<?php echo htmlspecialchars($_POST['caption'] ?? ''); ?>" required>
      </div>
      
      <div class="form-group">
        <label for="county">County</label>
        <select id="county" name="county" required>
          <option value="">Select County</option>
          <option value="Nairobi" <?php echo (isset($_POST['county']) && $_POST['county'] == 'Nairobi') ? 'selected' : ''; ?>>Nairobi</option>
          <option value="Mombasa" <?php echo (isset($_POST['county']) && $_POST['county'] == 'Mombasa') ? 'selected' : ''; ?>>Mombasa</option>
          <option value="Kisumu" <?php echo (isset($_POST['county']) && $_POST['county'] == 'Kisumu') ? 'selected' : ''; ?>>Kisumu</option>
          <option value="Kisi" <?php echo (isset($_POST['county']) && $_POST['county'] == 'Kisi') ? 'selected' : ''; ?>>Kisi</option>
        </select>
      </div>
      
      <div class="form-group">
        <label for="location">Location</label>
        <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($_POST['location'] ?? ''); ?>" required>
      </div>
      
      <button type="submit" class="submit-btn">Upload Photo</button>
      <a href="gallery.php">go back to gallery?</a>
    </form>
  </div>
  
  <?php include 'includes/footer.php'; ?>
  
  <script>
    // Image preview functionality
    document.getElementById('photo').addEventListener('change', function(e) {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(event) {
          const preview = document.getElementById('previewImage');
          preview.src = event.target.result;
          preview.style.display = 'block';
        }
        reader.readAsDataURL(file);
      }
    });
  </script>
</body>
</html>