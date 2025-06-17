<nav class="navbar">
        <a href="#" class="navbar-brand">TINDUKA</a>
        
        <div class="menu-toggle">☰</div>
        
        <div class="navbar-links">
        <a href="gallery.php" class="navbar-link <?= ($current_page == 'gallery.php') ? 'active' : '' ?>">Gallery</a>
        <a href="about.html" class="navbar-link <?= ($current_page == 'about.php') ? 'active' : '' ?>">About</a>
        
        <?php if (isset($_SESSION['user'])): ?>
            <!-- Show when logged in -->
            <a href="user_profile.php" class="navbar-link">Profile</a>
            <a href="logout.php" class="navbar-link signup-btn">Logout</a>
        <?php else: ?>
            <!-- Show when logged out -->
            <a href="login.html" class="navbar-link <?= ($current_page == 'signin.php') ? 'active' : '' ?>">Sign In</a>
            <a href="registration.html" class="navbar-link signup-btn <?= ($current_page == 'signup.php') ? 'active' : '' ?>">Sign Up</a>
        <?php endif; ?>
    </div>
        </nav>
<style>
    .navbar {
            background-color: rgba(76, 175, 80, 0.9); /* Green with 90% opacity */
            padding: 15px 30px;
            position: sticky;
            width: 100%;
            top: 0;
            left: 0;
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(5px); /* Adds frosted glass effect */
        }

        .navbar-brand {
            font-size: 1.8rem;
            font-weight: 800;
            color: white;
            text-decoration: none;
            letter-spacing: 1px;
        }

        .navbar-links {
            display: flex;
            gap: 30px;
            align-items: center;
        }

        .navbar-link {
            color: white;
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            padding: 8px 12px;
            border-radius: 4px;
        }

        .navbar-link:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .signup-btn {
            background-color: white;
            color: #4CAF50;
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: 600;
        }

        .signup-btn:hover {
            background-color: #f5f5f5;
        }

        
        .menu-toggle {
            display: none;
            cursor: pointer;
            color: white;
            font-size: 1.5rem;
        }

.navbar-link.active {
    background-color: rgba(255, 255, 255, 0.3);
    border-bottom: 2px solid white;
}

       
.site-footer {
  background-color: #4CAF50; 
  color: white;
  padding: 50px 0 0;
  font-size: 15px;
}

.footer-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 20px;
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 30px;
}

.footer-col h3 {
  font-size: 1.3rem;
  margin-bottom: 20px;
  position: relative;
}

.footer-col h3::after {
  content: '';
  position: absolute;
  left: 0;
  bottom: -8px;
  width: 50px;
  height: 2px;
  background: rgba(255, 255, 255, 0.3);
}

.footer-col ul {
  list-style: none;
}

.footer-col ul li {
  margin-bottom: 10px;
}

.footer-col ul li a {
  color: #e0e0e0;
  text-decoration: none;
  transition: all 0.3s ease;
}

.footer-col ul li a:hover {
  color: white;
  padding-left: 5px;
}

.footer-col i {
  margin-right: 10px;
  color: white;
}

.social-icons {
  margin-top: 20px;
}

.social-icons a {
  display: inline-block;
  color: white;
  background: rgba(255, 255, 255, 0.2);
  width: 36px;
  height: 36px;
  border-radius: 50%;
  text-align: center;
  line-height: 36px;
  margin-right: 8px;
  transition: all 0.3s ease;
}

.social-icons a:hover {
  background: rgba(255, 255, 255, 0.3);
  transform: translateY(-3px);
}

.footer-bottom {
  background: rgba(0, 0, 0, 0.1);
  text-align: center;
  padding: 20px 0;
  margin-top: 40px;
}
</style>