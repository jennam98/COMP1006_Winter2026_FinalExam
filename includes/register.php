<?php
session_start();
include "config.php";

// Sanitize input
function clean_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = clean_input($_POST['email']);
    $username = clean_input($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate input
    if (!$email) $errors[] = "Email is required.";
    if (!$username) $errors[] = "Username is required.";
    if (!$password) $errors[] = "Password is required.";
    if ($password !== $confirm_password) $errors[] = "Passwords do not match.";


    // Check if username exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) $errors[] = "Username already taken.";

    // Insert user
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (email,username, password) VALUES (?, ?)");
        $stmt->bind_param("sss", $email, $username, $hashed_password);
        $stmt->execute();
        $_SESSION['user_id'] = $conn->insert_id;
        $_SESSION['username'] = $username;
        header("Location: index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-5">

<h2>Register</h2>

<?php foreach ($errors as $err): ?>
    <div class="alert alert-danger"><?= $err ?></div>
<?php endforeach; ?>

<form method="POST" class="mt-3">
    <div class="mb-2"><input class="form-control" type="email" name="email" placeholder="Email" required></div>
    <div class="mb-2"><input class="form-control" type="text" name="username" placeholder="Username" required></div>
    <div class="mb-2"><input class="form-control" type="password" name="password" placeholder="Password" required></div>
    <div class="mb-2"><input class="form-control" type="password" name="confirm_password" placeholder="Confirm Password" required></div>
    <button class="btn btn-primary" type="submit">Register</button>
    <a class="btn btn-secondary" href="login.php">Login</a>
</form>

</body>
</html>

