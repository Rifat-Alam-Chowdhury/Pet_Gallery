<?php
session_start();
include "../db/db.php";

// 1. Role Check
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'shelter') {
    die("Unauthorized access. Shelter members only.");
}

$shelter_id = $_SESSION['shelter_id'];
$member_id = $_SESSION['user_id'] ?? 0; // The logged-in member's ID

// 2. HANDLE THE FORM SUBMISSION (POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pet_id = mysqli_real_escape_string($conn, $_POST['pet_id']);
        $msg_type = "updated";

    // Handle Image Upload (Converts to Base64 string)
  $image_sql_part = ""; 
if (!empty($_FILES['pet_image']['tmp_name'])) {
    $imageData = file_get_contents($_FILES['pet_image']['tmp_name']);
    $base64 = 'data:' . $_FILES['pet_image']['type'] . ';base64,' . base64_encode($imageData);
    
    // We include the comma and the column name inside this string
    // CRUCIAL: Use mysqli_real_escape_string to handle the special characters in Base64
    $safe_base64 = mysqli_real_escape_string($conn, $base64);
    $image_sql_part = ", pet_photo = '$safe_base64'"; 
}
    // --- CASE 1: Confirm Appointment Button Clicked ---
    if (isset($_POST['confirm_appointment'])) {
        $app_time = mysqli_real_escape_string($conn, $_POST['appointment_datetime']);
        
        if (empty($app_time)) {
            die("Error: Please select a date and time before confirming.");
        }

        $update_sql = "UPDATE pets SET 
                    request_status = 'Accepted',
                    adoption_status = 'Pending',
                    appointment_datetime = '$app_time',
                    appointment_set_by = '$member_id',
                    approved_by = '$member_id'
                   
                WHERE id = '$pet_id' AND shelter_id = '$shelter_id'";
        
        $msg_type = "confirmed";

    // --- CASE 2: Normal Save Changes Button Clicked ---
    } else {
        $pet_name = mysqli_real_escape_string($conn, $_POST['pet_name']);
        $breed = mysqli_real_escape_string($conn, $_POST['breed']);
        $category = mysqli_real_escape_string($conn, $_POST['category']);
        $age = mysqli_real_escape_string($conn, $_POST['age']);
        $color = mysqli_real_escape_string($conn, $_POST['color']);
        $behaviour = mysqli_real_escape_string($conn, $_POST['behaviour']);
        $is_vaccinated = (int)$_POST['is_vaccinated'];
        $adoption_status = mysqli_real_escape_string($conn, $_POST['adoption_status']);
        $request_status = mysqli_real_escape_string($conn, $_POST['request_status']);
        
        $appointment_datetime = !empty($_POST['appointment_datetime']) ? "'" . mysqli_real_escape_string($conn, $_POST['appointment_datetime']) . "'" : "NULL";

     $update_sql = "UPDATE pets SET 
                pet_name = '$pet_name',
                breed = '$breed',
                category = '$category',
                age = '$age',
                color = '$color',
                is_vaccinated = '$is_vaccinated',
                adoption_status = '$adoption_status',
                request_status = '$request_status',
                appointment_datetime = $appointment_datetime,
                behaviour = '$behaviour'
                $image_sql_part
            WHERE id = '$pet_id' AND shelter_id = '$shelter_id'";
        
        $msg_type = "updated";
    }

    if (mysqli_query($conn, $update_sql)) {
        header("Location: Shelter_member_view.php?id=$pet_id&msg=$msg_type");
        exit();
    } else {
        die("Error updating record: " . mysqli_error($conn));
    }
}

// 3. FETCH DATA (GET) - Using LEFT JOIN to get User Info
if (isset($_GET['id'])) {
    $pet_id = (int)$_GET['id']; 

    $ApplicantSql = "SELECT pets.*, 
                    users.full_name AS applicant_name, 
                    users.email AS applicant_email,
                    users.phone_number AS applicant_phone,
                    users.home_address AS applicant_address
            FROM pets 
            LEFT JOIN users ON pets.applicant_id = users.user_id 
            WHERE pets.id = $pet_id AND pets.shelter_id = '$shelter_id'";

    $result = mysqli_query($conn, $ApplicantSql);
    $pet = mysqli_fetch_assoc($result);

    if (!$pet) {
        die("Pet not found or you do not have permission to view it.");
    }
} else {
    header("Location: Shelter_Member.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Viewing <?php echo htmlspecialchars($pet['pet_name']); ?></title>
    <link rel="stylesheet" href="../Shelter-Member/Shelter_member_edit.css">
</head>
<body>

<form id="petForm" method="POST" class="profile-container" enctype="multipart/form-data">
    <input type="hidden" name="pet_id" value="<?php echo $pet['id']; ?>">

   <div class="image-section">
        <img src="<?php echo $pet['pet_photo']; ?>" id="petPreview">
        <a href="javascript:history.back()" class="back-btn"> Back</a>
        <div id="uploadArea" style="display:none;">
            <label for="pet_image" class="btn-upload">Choose New Photo</label>
            <input type="file" name="pet_image" id="pet_image" accept="image/*" ">
        </div>
    </div>

    <div class="details-section">
        <div class="status-pill"><?php echo $pet['adoption_status']; ?></div>
        
        <input type="text" name="pet_name" class="editable-input name-input" value="<?php echo $pet['pet_name']; ?>" readonly>
        
        <p class="breed-line">
            <input type="text" name="breed" class="editable-input inline-input" value="<?php echo $pet['breed']; ?>" readonly> 
            <input type="text" name="category" class="editable-input inline-input" value="<?php echo $pet['category']; ?>" readonly>
        </p>

        <div class="info-grid">
            <div class="info-item">
                <label>Age</label>
                <input type="text" name="age" class="editable-input" value="<?php echo $pet['age']; ?>" readonly>
            </div>
            
            <div class="info-item">
                <label>Color</label>
                <input type="text" name="color" class="editable-input" value="<?php echo $pet['color']; ?>" readonly>
            </div>

            <div class="info-item">
                <label>Vaccination</label>
                <select name="is_vaccinated" class="editable-input" disabled>
                    <option value="1" <?php echo $pet['is_vaccinated'] ? 'selected' : ''; ?>>✅ Vaccinated</option>
                    <option value="0" <?php echo !$pet['is_vaccinated'] ? 'selected' : ''; ?>>❌ Not Vaccinated</option>
                </select>
            </div>

            <div class="info-item">
                <label>Adoption Status</label>
                <select name="adoption_status" class="editable-input" disabled>
                    <option value="Available" <?php echo $pet['adoption_status'] == 'Available' ? 'selected' : ''; ?>>Available</option>
                    <option value="Pending" <?php echo $pet['adoption_status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="Adopted" <?php echo $pet['adoption_status'] == 'Adopted' ? 'selected' : ''; ?>>Adopted</option>
                </select>
            </div>

            <div class="info-item full-width">
                <label>Applicant Information</label>
                <div class="applicant-card">
                    <?php if ($pet['applicant_id']): ?>
                        <div class="user-main">
                            <span><strong><?php echo htmlspecialchars($pet['applicant_name']); ?></strong></span>
                        </div>
                        <div class="user-details">
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($pet['applicant_email']); ?></p>
                            <p><strong>Phone:</strong> <?php echo htmlspecialchars($pet['applicant_phone']); ?></p>
                            <p><strong>Address:</strong> <?php echo htmlspecialchars($pet['applicant_address']); ?></p>
                        </div>
                    <?php else: ?>
                        <p class="no-data">No current applicant for this pet.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="info-item">
                <label>Request Status</label>
                <select name="request_status" class="editable-input" disabled>
                    <option value="None" <?php echo $pet['request_status'] == 'None' ? 'selected' : ''; ?>>None</option>
                    <option value="Pending" <?php echo $pet['request_status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="Accepted" <?php echo $pet['request_status'] == 'Accepted' ? 'selected' : ''; ?>>Accepted</option>
                    <option value="Rejected" <?php echo $pet['request_status'] == 'Rejected' ? 'selected' : ''; ?>>Rejected</option>
                </select>
            </div>

            <div class="info-item">
                <label>Appointment Date & Time</label>
                <input type="datetime-local" name="appointment_datetime" id="app_date" class="editable-input" 
                       value="<?php echo $pet['appointment_datetime'] ? date('Y-m-d\TH:i', strtotime($pet['appointment_datetime'])) : ''; ?>" 
                       <?php echo ($pet['applicant_id']) ? '' : 'disabled'; ?>
            </div>
        </div>

        <?php if ($pet['applicant_id'] && $pet['request_status'] !== 'Accepted'): ?>
            <div class="appointment-action" style="margin-bottom: 20px;">
                <button type="submit" name="confirm_appointment" class="btn-confirm-app">
                    Confirm Appointment & Accept Application
                </button>
            </div>
        <?php endif; ?>

        <div class="description">
            <h3>Behaviour & Temperament</h3>
            <textarea name="behaviour" class="editable-input" readonly rows="4"><?php echo $pet['behaviour']; ?></textarea>
        </div>
        
        <div class="action-bar">
            <div id="viewMode">
                <button type="button" class="btn btn-edit" onclick="enableEdit()">Edit Profile</button>
            </div>

            <div id="editMode" style="display: none;">
                <button type="submit" name="save_changes" class="btn btn-save">Save Changes</button>
                <button type="button" class="btn btn-cancel" onclick="location.reload()">Discard</button>
            </div>
        </div>
    </div>
</form>

<script src="../Shelter-Member/Shelter_member_edit.js"></script>
</body>
</html>
