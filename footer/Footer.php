<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./footer/Footer.css">
</head>
<body>
    <footer class="admin-footer">
    <div class="footer-content">
        <div class="footer-section">
            <h4>Pet Gallery</h4>
            
        </div>
        
        <div class="footer-links">
            <a href="admin_dashboard.php?tab=Dashboard">Dashboard</a>
            <a href="admin_dashboard.php?tab=Pets">Manage Pets</a>
            <a href="admin_dashboard.php?tab=Adoptions">Adoptions</a>
            <a href="../index.php">View Website</a>
        </div>

        <div class="footer-status">
            <p>&copy; <?php echo date("Y"); ?> PetAdopt. All rights reserved.</p>
            <p class="system-time">System Time: <?php echo date("h:i A"); ?></p>
        </div>
    </div>
</footer>
</body>
</html>