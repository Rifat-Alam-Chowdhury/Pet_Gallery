<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "rifatpetgallery";

// The one-line connection
$conn = mysqli_connect($host, $user, $pass, $db);

// Check if it worked
if (!$conn) {
    echo("Connection failed: " . mysqli_connect_error());
}

?>
