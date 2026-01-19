<?php
session_start();
include "../db/db.php";

// Check if form is submitted
if (isset($_POST['login_btn'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
    $result = mysqli_query($conn, $sql);
    

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
         print_r($user);
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user_name'] = $user['username'];


                 if ($user['is_shelter_member'] == 1)
                     {
                         $_SESSION['role'] = 'shelter';
                          $_SESSION['shelter_id'] = $user['shelter_id']; 
                     }
                    elseif (!empty($user['is_admin']) && $user['is_admin'] == 1) {
    $_SESSION['role'] = 'admin';
    header("Location: ../index.php");
    exit();
}
                 else{
                        $_SESSION['role'] = 'member';
                        $_SESSION['shelter_id'] = null; 
                        header("Location: ../index.php");
                         exit();
                    }
        
                         header("Location: ../Shelter-member/Shelter_member.php");
        exit();
     } else {
         $error = "Invalid Email or Password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
      <link rel="stylesheet" href="./Login.css">
</head>
<body>
    <div class="login-card">
        <h2>Login</h2>
        
        <?php if(isset($error)) echo "<div class='error-msg'>$error</div>"; ?>

        <form method="POST" action="">
            <div class="input-group">
                <label>Email Address</label>
                <input type="email" name="email" placeholder="Enter your email" required>
            </div>

            <div class="input-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Enter password" required>
            </div>

            <button type="submit" name="login_btn" class="login-btn">Log In</button>
        </form>
        
       
         <p >
            Don't have an account? <a href="../registration/registration.php">Register here</a>
        </p>
     
    </div>
</body>
</html>