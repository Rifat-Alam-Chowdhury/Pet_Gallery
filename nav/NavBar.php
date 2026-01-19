<?php

session_start();

print_r($_SESSION);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   
      <link rel="stylesheet" href="./nav/NavBar.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <a href="index.php" class="nav-logo">
                <span class="paw-icon">🐾</span> PetGallery
            </a>

            <ul class="nav-links">
                <li><a href="index.php" >Home</a></li>
                <li><a href="browse.php">Find a Pet</a></li>
                <li><a href="shelters.php">Shelters</a></li>
                
                
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'shelter'): ?>
                
                <li>
                    <li><a href="shelters.php">Edit Profile</a></li>
                    <a href="../Shelter-Member/Shelter_member_view.php" class="shelter-accent">Manage Pets</a>
                </li>
            <?php endif; ?>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                
                <li>
                    <li><a href="shelters.php">Edit Profile</a></li>
                   <li><a href="../Admin/Admin_dashboard.php">Admin</a></li>
                </li>
            <?php endif; ?>
                
                <li class="nav-auth">
                        <?php if (!isset($_SESSION['user_id'])): ?>
                            <a href="./Login/Login.php" class="login-link">Login</a>
                            
                            <a href="./registration/registration.php" class="register-btn-nav">Join Now</a>
                            
                          <?php else: ?>
                            <span class="user-name">Hi, <?php echo htmlspecialchars($_SESSION['user_name']); ?>                             </span>
                            <li><a href="shelters.php">Edit Profile</a></li>
                            <a href="Logout.php" class="login-link" style="color: #e74c3c;">Log Out</a>
                            
                       <?php endif; ?>
                   
                
                    
                </li>
            </ul>

            
        </div>
</nav>
</body>
</html>