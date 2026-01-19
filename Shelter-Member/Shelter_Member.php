<?php
session_start();
include "../db/db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'shelter') {
    header("Location: ../login/Login.php");
    exit();
}

$my_user_id    = $_SESSION['user_id'];
$my_username   = $_SESSION['user_name'];
$my_shelter_id = $_SESSION['shelter_id'];

$sql = "SELECT s.shelter_name, l.name AS city_name, s.pets_count, p.* FROM shelter s
        JOIN locations l ON s.location_id = l.id 
        LEFT JOIN pets p ON s.shelter_id = p.shelter_id
        WHERE s.shelter_id = '$my_shelter_id'
        ORDER BY p.id DESC";

$result = mysqli_query($conn, $sql);
$data = mysqli_fetch_all($result, MYSQLI_ASSOC);


// Handle empty shelter cases safely
$shelter_name = $data[0]['shelter_name'] ?? "Shelter";
$city_name    = $data[0]['city_name'] ?? "Unknown";
$pets_count   = $data[0]['pets_count'] ?? 0;
$pet_list     = (!empty($data) && $data[0]['id'] !== null) ? $data : [];



if (isset($_POST['delete_request'])) {
    $pet_id     = (int) $_POST['pet_id'];          // integer → no need for escape
$member_id  = (int) $_SESSION['user_id'];      // integer
$shelter_id = (int) $_SESSION['shelter_id'];   // integer

echo $pet_id . ' ' . $member_id . ' ' . $shelter_id;

    // Updating ONLY the deleted_req_by column
    $sql = "UPDATE pets SET 
                deleted_req_by = '$member_id' 
            WHERE id = '$pet_id' AND shelter_id = '$shelter_id'";
            
    if (mysqli_query($conn, $sql)) {
        // IMPORTANT: No echo/output before header()
        header("Location: Shelter_member.php");
        exit();
    } else {
        die("Error updating record: " . mysqli_error($conn));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shelter Dashboard | <?php echo $shelter_name; ?></title>
    <link rel="stylesheet" href="../Shelter-Member/Shelter_member.css">
</head>
<body>
        <div class="Topbar">
                    <div class="topbar-left">
                        <div class="Shelter_name_img"></div>
                        <div class="Shelter_name">
                            <h2><?php echo $shelter_name; ?></h2>
                            <p> <?php echo $city_name; ?></p>    
                        </div>
                    </div>
            
                    <div class="topbar-right">
                        <h1>Welcome back, <span><?php echo $my_username; ?></span></h1>
                        <p class="Pets-count">Total Pets: <strong><?php echo $pets_count; ?></strong></p> 
                    </div>
            </div>


            <div class="inventory">
                <h2>Your Pet Inventory</h2>
                <a href="./Shelter_Member_Add_Pets.php">Add Pets</a>
            </div>

   
            <div class="pets-grid">
                <?php if (!empty($pet_list)): ?>
                    <?php foreach ($pet_list as $pet): ?>
                        <div class="pet-card">
                            <div class="status-badge <?php echo ($pet['adoption_status']); ?>">
                                <?php echo $pet['adoption_status']; ?>
                            </div>
                            <?php if ($pet['requested_notification'] > 0): ?>
                                <div class="request-badge">
                                    <i class="fas fa-bell"></i>
                                    <?php echo $pet['requested_notification']; ?>
                                </div>
                            <?php endif; ?>
                            <img src="<?php echo $pet['pet_photo']; ?>" alt="Pet">
                            <div class="pet-info">
                                <h3><?php echo $pet['pet_name']; ?></h3>
                                <p><strong>Breed:</strong> <?php echo $pet['breed']; ?></p>
                                <p><strong>Vax:</strong> <?php echo $pet['is_vaccinated'] ? '✅ Protected' : '❌ Pending'; ?></p>
                                <div class="card-actions">
                                    <div>
                                                
                                              <a href="./Shelter_member_view.php?id=<?php echo $pet['id']; ?>" class="edit-sm-btn">View</a>
                                              <a href="./Shelter_member_view.php?id=<?php echo $pet['id']; ?>" class="edit-sm-btn">Edit</a>
                                    </div>
                                <form method="POST" action="" style="display:inline; ">
                                    <input type="hidden" name="pet_id" value="<?php echo $pet['id']; ?>">
                                    <?php 
                                      
                                        $has_been_deleted = (isset($pet['deleted_req_by']) && $pet['deleted_req_by'] > 0);
                                    ?>

                                    <button type="submit" name="delete_request" 
                                            class="del-sm-btn" 
                                            onclick="return confirm('Delete this application?')"
                                            <?php echo $has_been_deleted ? 'disabled style="background-color: #ccc; cursor: not-allowed;"' : ''; ?>>
                                        
                                        <?php echo $has_been_deleted ? 'Wait For Admin Approve' : 'Delete'; ?>
                                        
                                    </button>
                                </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">
                       
                        <p>No pets found in your shelter yet.</p>
                    </div>
                <?php endif; ?>
            </div>
    


    <script src="../Shelter-Member/Shelter_member.js"></script>
</body>
</html>