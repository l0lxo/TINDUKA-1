<?php
session_start();
include('config.php');

// If user is already logged in, redirect to gallery
if (isset($_SESSION['user_id'])) {
    header("Location: gallery.php");
    exit();
}

$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Validate credentials
    $stmt = $conn->prepare("SELECT user_id, firstname, lastname, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Authentication successful - set session variables
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['email'] = $email;
            $_SESSION['firstname'] = $user['firstname'];
            $_SESSION['lastname'] = $user['lastname'];
            
            // Redirect to gallery
            header("Location: gallery.php");
            exit();
        }
    }
    
    // If we get here, login failed
    $error = "Invalid email or password";
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login to TINDUKA</title>
  <link rel="stylesheet" href="registration.css" /> <!-- Reusing your registration styles -->
</head>
<body>
  <form class="form" method="POST" action="login.php">
    <p class="title">Login</p>
    <p class="message">Welcome back to TINDUKA photo sharing.</p>

    <?php if (!empty($error)): ?>
      <div style="color: red; text-align: center; margin-bottom: 15px;">
        <?php echo htmlspecialchars($error); ?>
      </div>
    <?php endif; ?>

    <label class="full-width">
      <input required placeholder="" type="email" class="input" name="email" />
      <span>Email</span>
    </label>

    <label class="full-width">
      <input required placeholder="" type="password" class="input" name="password" />
      <span>Password</span>
    </label>

    <button class="submit" type="submit">Login</button>
    <p class="signin">Don't have an account? <a href="registration.php">Register</a></p>
  </form>
</body>
</html>