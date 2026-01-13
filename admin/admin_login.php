<?php
session_start();
include('../config/db.php');

// If admin already logged in
if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";

// Handle login
if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND role = 'admin'");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();

        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['name'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Incorrect password!";
        }
    } else {
        $error = "Admin not found!";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Login - JKKNIU Guest House</title>

<style>
body{
    font-family: Arial, sans-serif;
    background: linear-gradient(135deg, #1e3c72, #2a5298);
    height:100vh;
    margin:0;
    display:flex;
    align-items:center;
    justify-content:center;
}

.admin-login-box{
    background:#fff;
    width:100%;
    max-width:420px;
    padding:40px;
    border-radius:12px;
    box-shadow:0 15px 40px rgba(0,0,0,0.3);
    text-align:center;
}

.admin-login-box h2{
    margin-bottom:25px;
    color:#2a5298;
}

.admin-login-box input{
    width:100%;
    padding:12px;
    margin:12px 0;
    border-radius:6px;
    border:1px solid #ccc;
    font-size:16px;
}

.admin-login-box button{
    width:100%;
    padding:12px;
    background:#2a5298;
    color:#fff;
    border:none;
    border-radius:6px;
    font-size:18px;
    cursor:pointer;
    transition:0.3s;
}

.admin-login-box button:hover{
    background:#1e3c72;
}

.error{
    background:#f8d7da;
    color:#721c24;
    padding:12px;
    border-radius:6px;
    margin-bottom:15px;
}

.footer-links{
    margin-top:15px;
}

.footer-links a{
    color:#2a5298;
    text-decoration:none;
    font-size:14px;
}

.footer-links a:hover{
    text-decoration:underline;
}

.admin-badge{
    font-size:14px;
    background:#2a5298;
    color:#fff;
    display:inline-block;
    padding:5px 12px;
    border-radius:20px;
    margin-bottom:10px;
}
</style>
</head>

<body>

<div class="admin-login-box">
    <span class="admin-badge">ADMIN PANEL</span>
    <h2>Admin Login</h2>

    <?php if(!empty($error)) echo "<div class='error'>$error</div>"; ?>

    <form method="post">
        <input type="email" name="email" placeholder="Admin Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Login</button>
    </form>

    <div class="footer-links">
        <a href="../index.php">← Back to Home</a>
    </div>
</div>

</body>
</html>
