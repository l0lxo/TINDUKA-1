<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>User Profile</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #f4f4f9, #e0e0e0);
    }

    .profile-header {
      background-color: white;
      padding: 2rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      flex-wrap: wrap;
    }

    .user-info {
      display: flex;
      align-items: center;
      gap: 1.5rem;
    }

    .user-info img {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      object-fit: cover;
    }

    .user-details {
      display: flex;
      flex-direction: column;
    }

    .user-details h2 {
      margin: 0;
    }

    .edit-btn {
      padding: 0.5rem 1rem;
      background-color: #4caf50;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-weight: bold;
      transition: background 0.3s;
    }

    .edit-btn:hover {
      background-color: #357ABD;
    }

    .posts {
      padding: 2rem;
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
      gap: 1rem;
    }

    .post {
      background-color: white;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .post img {
      width: 100%;
      display: block;
      height: 200px;
      object-fit: cover;
    }

    /* Modal */
    .modal {
      display: none;
      position: fixed;
      z-index: 99;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0,0,0,0.5);
    }

    .modal-content {
      background-color: #fff;
      margin: 10% auto;
      padding: 2rem;
      border-radius: 10px;
      width: 90%;
      max-width: 500px;
      position: relative;
    }

    .modal-content h3 {
      margin-top: 0;
    }

    .modal-content label {
      display: block;
      margin: 1rem 0 0.3rem;
      font-weight: bold;
    }

    .modal-content input,
    .modal-content textarea {
      width: 100%;
      padding: 0.5rem;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    .modal-content input[type="file"] {
      padding: 0.3rem;
    }

    .modal-close {
      position: absolute;
      top: 1rem;
      right: 1rem;
      background: none;
      border: none;
      font-size: 1.2rem;
      cursor: pointer;
    }

    .submit-btn {
      margin-top: 1.5rem;
      background-color: #4caf50;
      color: white;
      border: none;
      padding: 0.6rem 1.2rem;
      border-radius: 5px;
      font-weight: bold;
      cursor: pointer;
    }

    .submit-btn:hover {
      background-color: #3e8e41;
    }

    @media (max-width: 600px) {
      .user-info {
        flex-direction: column;
        align-items: flex-start;
      }

      .user-info img {
        width: 80px;
        height: 80px;
      }

      .modal-content {
        margin-top: 20%;
      }
    }
    .post {
  position: relative;
  background-color: white;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 2px 5px rgba(0,0,0,0.1);
  transition: transform 0.2s;
}

.post:hover {
  transform: scale(1.01);
}

.post img {
  width: 100%;
  display: block;
  height: 200px;
  object-fit: cover;
}

.delete-form {
  position: absolute;
  top: 10px;
  right: 10px;
  display: none;
}

.delete-btn {
  background-color: rgba(255, 0, 0, 0.8);
  color: white;
  border: none;
  font-size: 1.2rem;
  padding: 0.3rem 0.6rem;
  border-radius: 5px;
  cursor: pointer;
  font-weight: bold;
}

.post:hover .delete-form {
  display: block;
}

<?php
session_start();
include('config.php');

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user data
$user_id = $_SESSION['user_id'];
$user_stmt = $conn->prepare("SELECT firstname, lastname, email, profile_picture, bio FROM users WHERE user_id = ?");
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();

if ($user_result->num_rows === 0) {
    die("User not found");
}

$user = $user_result->fetch_assoc();
$fullname = $user['firstname'] . ' ' . $user['lastname'];

// Fetch user's photos
$photos_stmt = $conn->prepare("SELECT photo_id, photo_url, caption, county, location FROM photos WHERE user_id = ? ORDER BY created_at DESC");
$photos_stmt->bind_param("i", $user_id);
$photos_stmt->execute();
$photos_result = $photos_stmt->get_result();
$photos = $photos_result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?php echo htmlspecialchars($fullname); ?>'s Profile</title>
  <style>
    body {
      font-family: 'Helvetica Neue', Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f5f5f5;
      color: #333;
    }
    
    .profile-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 30px 5%;
      background-color: white;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .user-info {
      display: flex;
      align-items: center;
      gap: 20px;
    }
    
    .user-info img {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      object-fit: cover;
    }
    
    .user-details h2 {
      margin: 0 0 10px 0;
    }
    
    .edit-btn {
      padding: 10px 20px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 16px;
      transition: background-color 0.3s;
    }
    
    .edit-btn:hover {
      background-color: #45a049;
    }
    
    .posts {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 20px;
      padding: 30px 5%;
    }
    
    .post {
      position: relative;
      background: white;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .post img {
      width: 100%;
      height: 300px;
      object-fit: cover;
    }
    
    .delete-form {
      position: absolute;
      top: 10px;
      right: 10px;
    }
    
    .delete-btn {
      background: #f44336;
      color: white;
      border: none;
      width: 30px;
      height: 30px;
      border-radius: 50%;
      font-size: 18px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    
    /* Modal styles */
    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0,0,0,0.5);
    }
    
    .modal-content {
      background-color: white;
      margin: 10% auto;
      padding: 20px;
      border-radius: 8px;
      width: 80%;
      max-width: 500px;
      position: relative;
    }
    
    .modal-close {
      position: absolute;
      right: 15px;
      top: 15px;
      font-size: 24px;
      background: none;
      border: none;
      cursor: pointer;
    }
    
    form {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }
    
    label {
      font-weight: bold;
    }
    
    input, textarea {
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 4px;
    }
    
    .submit-btn {
      padding: 12px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 16px;
    }
  </style>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>
  
  <div class="profile-header">
    <div class="user-info">
      <img src="<?php echo htmlspecialchars($user['profile_picture'] ?? 'https://via.placeholder.com/100'); ?>" alt="Profile Picture" />
      <div class="user-details">
        <h2><?php echo htmlspecialchars($fullname); ?></h2>
        <p><?php echo htmlspecialchars($user['bio'] ?? 'No bio yet'); ?></p>
      </div>
    </div>
    <button class="edit-btn" onclick="openModal()">Edit Profile</button>
  </div>

  <div class="posts">
    <?php foreach ($photos as $photo): ?>
      <div class="post">
        <form action="delete_photo.php" method="POST" class="delete-form">
          <input type="hidden" name="photo_id" value="<?php echo $photo['photo_id']; ?>" />
          <button type="submit" class="delete-btn">&times;</button>
        </form>
        <img src="<?php echo htmlspecialchars($photo['photo_url']); ?>" alt="<?php echo htmlspecialchars($photo['caption']); ?>">
        <div style="padding: 15px;">
          <p><?php echo htmlspecialchars($photo['caption']); ?></p>
          <small><?php echo htmlspecialchars($photo['county'] . ', ' . $photo['location']); ?></small>
        </div>
      </div>
    <?php endforeach; ?>
    
    <?php if (empty($photos)): ?>
      <p style="grid-column: 1 / -1; text-align: center;">No photos uploaded yet. <a href="submit_photo.php">Share your first photo!</a></p>
    <?php endif; ?>
  </div>

  <!-- Edit Profile Modal -->
  <div id="editModal" class="modal">
    <div class="modal-content">
      <button class="modal-close" onclick="closeModal()">&times;</button>
      <h3>Edit Profile</h3>
      <form action="update_profile.php" method="POST" enctype="multipart/form-data">
        <label for="firstname">First Name</label>
        <input type="text" id="firstname" name="firstname" value="<?php echo htmlspecialchars($user['firstname']); ?>" required>

        <label for="lastname">Last Name</label>
        <input type="text" id="lastname" name="lastname" value="<?php echo htmlspecialchars($user['lastname']); ?>" required>

        <label for="profilePic">Profile Picture</label>
        <input type="file" id="profilePic" name="profile_picture" accept="image/*">

        <label for="bio">Bio</label>
        <textarea id="bio" name="bio" rows="3" placeholder="Tell us about yourself..."><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>

        <button type="submit" class="submit-btn">Save Changes</button>
      </form>
    </div>
  </div>
  
    <?php include 'includes/footer.html'; ?>
  
  <script>
    function openModal() {
      document.getElementById('editModal').style.display = 'block';
    }

    function closeModal() {
      document.getElementById('editModal').style.display = 'none';
    }

    window.onclick = function(event) {
      if (event.target === document.getElementById('editModal')) {
        closeModal();
      }
    }
  </script>

</body>
</html>

