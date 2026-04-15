<? php
session_start();
include "config.php";


//check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

//sanitize input
function clean_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

$errors = [];

//validate input
if (empty($title)) {
    $errors[] = "Title is required.";
}

$image_file_name = null;

if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($_FILES['image']['type'], $allowed_types)) {
        $errors[] = "Only JPG, PNG, and GIF files are allowed.";
    } else {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image_file_name = uniqid() . "." . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], "uploads/" . $image_file_name);
    }
} else {
    $errors[] = "Image upload failed.";
}