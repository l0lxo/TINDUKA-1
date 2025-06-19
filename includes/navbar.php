

<nav style="background-color: #4CAF50; padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
    <div style="display: flex; align-items: center;">
        <a href="index.php" style="color: white; text-decoration: none; font-size: 1.5rem; font-weight: bold;">TINDUKA</a>
    </div>
    
    <div style="display: flex; gap: 15px; align-items: center;">
        <?php if (isset($_SESSION['user_id'])): ?>
            <!-- Show when user is logged in -->
            <span style="color: white; font-weight: 500;">Welcome, <?php echo htmlspecialchars($_SESSION['firstname'] ?? 'User'); ?></span>
            <a href="submit_photo.php" style="color: white; text-decoration: none; padding: 8px 15px; border-radius: 4px; transition: background-color 0.3s;">Upload</a>
            <a href="profile.php" style="color: white; text-decoration: none; padding: 8px 15px; border-radius: 4px; transition: background-color 0.3s;">Profile</a>
            <a href="logout.php" style="color: white; text-decoration: none; padding: 8px 15px; background-color: #f44336; border-radius: 4px; transition: background-color 0.3s;">Logout</a>
        <?php else: ?>
            <!-- Show when user is not logged in -->
            <a href="registration.php" style="color: white; text-decoration: none; padding: 8px 15px; border-radius: 4px; transition: background-color 0.3s;">Register</a>
            <a href="login.php" style="color: white; text-decoration: none; padding: 8px 15px; background-color: #2196F3; border-radius: 4px; transition: background-color 0.3s;">Login</a>
        <?php endif; ?>
    </div>
</nav>