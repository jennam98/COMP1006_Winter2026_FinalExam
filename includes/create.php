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


if (empty($errors)) {
    $stmt = $conn->prepare("INSERT INTO images (user_id, title, description, filename) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $_SESSION['user_id'], $title, $description, $image_file_name);
    $stmt->execute();
    header("Location: index.php");
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Image</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="p-5">
    <h1>Upload New Image</h1>

    <?php foreach ($errors as $err): ?>
        <div class="alert alert-danger"><?= $err ?></div>
    <?php endforeach; ?>

    <form method="POST" enctype="multipart/form-data" class="mt-3">
        <div class="mb-2"><input class="form-control" type="text" name="title" placeholder="Title" required></div>
        <div class="mb-2"><input class="form-control" type="file" name="image"  required></div>
        <button class="btn btn-primary" type="submit">Upload</button>
    </form>

</body>
</html>