<?php
session_start();
include 'db.php';

$success = "";
$error = "";

// Secret admin code (change this to something only you know)
$admin_secret_code = "pfgadmin123";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm = $_POST["confirm_password"];
    $secret = trim($_POST["admin_code"]);

    if (empty($name) || empty($email) || empty($password) || empty($confirm)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        // Check if email is already taken
        $stmt = $conn->prepare("SELECT id FROM members WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Email already registered.";
        } else {
            // Assign role based on admin code
            $role = ($secret === $admin_secret_code) ? "admin" : "member";

            // Insert new user
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO members (name, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $hashed_password, $role);

            if ($stmt->execute()) {
                $success = "Registered successfully. <a href='login.php' style='color: #FFD700; font-weight: bold;'>Login here</a>.";
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
    <div class="login-container">
        <h2>Register</h2>

        <?php if ($success): ?>
            <p class="success"><?php echo $success; ?></p>
        <?php elseif ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="POST">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" required>

            <label for="email">Email:</label>
            <input type="text" name="email" id="email" required>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>

            <label for="confirm_password">Confirm Password:</label>
            <input type="password" name="confirm_password" id="confirm_password" required>

            <label for="admin_code">Admin Code (optional):</label>
            <input type="text" name="admin_code" id="admin_code" placeholder="Leave empty for member">

            <input type="submit" value="Register">
        </form>
<p style="margin-top: 10px; color: #FFD700;">
    Already have an account? 
    <a href="login.php" style="color: #FFD700; font-weight: bold;">Login here</a>
</p>

    </div>
</body>
</html>
