<?php
session_start();
include "config.php";


function clean_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

$errors = [];


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = clean_input($_POST['username']);
    $password = $_POST['password'];

    if (!$username) $errors[] = "Username is required.";
    if (!$password) $errors[] = "Password is required.";


    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id, password FROM users WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($id, $hashed_password);
        if ($stmt->num_rows == 1) {
            $stmt->fetch();
            if (password_verify($password, $hashed_password)) {
                $_SESSION['user_id'] = $id;
                $_SESSION['username'] = $username;
                header("Location: index.php");
                exit();
            } else {
                $errors[] = "Incorrect password.";
            }
        } else {
            $errors[] = "User not found.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
   
</head>
<body class="p-5">

<h2>Login</h2>

<?php foreach ($errors as $err): ?>
    <div class="alert alert-danger"><?= $err ?></div>
<?php endforeach; ?>

<form method="POST" class="mt-3">
    <div class="mb-2"><input class="form-control" type="text" name="username" placeholder="Username" required></div>
    <div class="mb-2"><input class="form-control" type="password" name="password" placeholder="Password" required></div>
    <button class="btn btn-primary" type="submit">Login</button>
    <a class="btn btn-secondary" href="register.php">Register</a>
</form>

</body>
</html>

