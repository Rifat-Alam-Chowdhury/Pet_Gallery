<?php
if (isset($_GET['id'])) {
    $caught_id = (int)$_GET['id']; 
    echo "You are looking at Pet ID: " . $caught_id;
}