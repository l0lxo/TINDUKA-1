<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sign up Today</title>
  <link rel="stylesheet" href="registration.css" />
</head>
<body>
  <!-- Change action to register.php and add error display -->
  <form class="form" method="post" action="register.php">
    <p class="title">Register</p>
    <p class="message">Signup now and get full access to our features.</p>

    <!-- Show PHP errors if they exist -->
    <?php if (isset($_GET['error'])): ?>
      <div style="color: red; text-align: center; margin-bottom: 10px;">
        <?php echo htmlspecialchars($_GET['error']); ?>
      </div>
    <?php endif; ?>

    <div class="flex">
      <label>
        <input required placeholder="" type="text" class="input" name="firstname" />
        <span>Firstname</span>
      </label>
      &nbsp;&nbsp;
      <label>
        <input required placeholder="" type="text" class="input" name="lastname" />
        <span>Lastname</span>
      </label>
    </div>
    <label class="full-width">
      <input required placeholder="" type="email" class="input" name="email" />
      <span>Email</span>
    </label>
    <label class="full-width">
      <input required placeholder="" type="password" class="input" name="password" />
      <span>Password</span>
    </label>
    <label class="full-width">
      <input required placeholder="" type="password" class="input" name="confirmpassword" />
      <span>Confirm password</span>
    </label>
    <button class="submit" type="submit">Submit</button>
    <p class="signin">Already have an account? <a href="/signin">Signin</a></p>
  </form>
</body>
</html>
