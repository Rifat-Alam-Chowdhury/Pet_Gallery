<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./RecentlyAdded/recentlyaddedpets.css">
 
</head>
<body>
   <section class="pets-section">
    <div class="section-header">
        <span class="sub-title">Recently Added</span>
        <h2>Meet Our <span class="orange-text">Newest</span> Friends</h2>
        <p>These lovable pets just arrived and are looking for their forever homes.</p>
    </div>

    <div class="pets-grid">
        <div class="pet-card">
            <div class="pet-image">
                <img src="../images/dog1.jpg" alt="Pet Name">
                <span class="category-badge">Dog</span>
            </div>
            <div class="pet-info">
                <div class="pet-name-row">
                    <h3>Charlie</h3>
                    <span class="pet-age">2 Years</span>
                </div>
                <p class="pet-location">📍 New York, NY</p>
                <div class="pet-footer">
                    
                    <a href="pet-details.php?id=1" class="view-btn">View Profile</a>
                </div>
            </div>
        </div>

        <div class="pet-card">
            <div class="pet-image">
                <img src="../images/cat1.jpg" alt="Pet Name">
                <span class="category-badge cat">Cat</span>
            </div>
            <div class="pet-info">
                <div class="pet-name-row">
                    <h3>Luna</h3>
                    <span class="pet-age">6 Months</span>
                </div>
                <p class="pet-location">📍 Brooklyn, NY</p>
                <div class="pet-footer">
                    
                    <a href="pet-details.php?id=2" class="view-btn">View Profile</a>
                </div>
            </div>
        </div>
        
        </div>
    
    <div class="center-btn">
        <a href="browse.php" class="browse-all">View All Available Pets</a>
    </div>
</section>
</body>
</html>