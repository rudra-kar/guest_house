<?php
session_start();
include('../config/db.php');

$message = ""; // To display login errors

if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = md5($_POST['password']); // Using md5 as per your code (not recommended for production)

    $query = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result)==1){
        $user = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['name'] ?? $user['email']; // Optional username session
        header("Location: dashboard.php");
        exit();
    } else {
        $message = "<div class='error'>Invalid Email or Password</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Guest Login - JKKNIU Guest House</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('../assets/images/bg2.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
        }

        .overlay {
            position: absolute;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.6);
            top: 0;
            left: 0;
            z-index: 0;
        }

        .login-box {
            position: relative;
            z-index: 1;
            background: rgba(0,0,0,0.75);
            padding: 40px;
            border-radius: 12px;
            max-width: 400px;
            width: 90%;
            text-align: center;
            box-shadow: 0 8px 25px rgba(0,0,0,0.4);
        }

        .login-box h2 {
            margin-bottom: 25px;
            font-size: 28px;
            color: #ffd700;
            text-shadow: 1px 1px 5px #000;
        }

        .login-box input {
            width: 100%;
            padding: 12px;
            margin: 12px 0;
            border-radius: 6px;
            border: none;
            font-size: 16px;
        }

        .login-box button {
            width: 100%;
            padding: 12px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 18px;
            cursor: pointer;
            margin-top: 10px;
            transition: 0.3s;
        }

        .login-box button:hover {
            background-color: #218838;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 15px;
            text-align: center;
        }

        .login-box p {
            margin-top: 15px;
        }

        .login-box a {
            color: #ffc107;
            text-decoration: none;
        }

        .login-box a:hover {
            text-decoration: underline;
        }

        @media (max-width: 450px) {
            .login-box {
                padding: 30px;
            }
        }
    </style>
</head>
<body>
    <div class="overlay"></div>

    <div class="login-box">
        <h2>Guest Login</h2>

        <?php if(!empty($message)) echo $message; ?>

        <form method="post" action="">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Login</button>
        </form>

        <p>Don't have an account? <a href="register.php">Register here</a></p>
        <p>Admin? <a href="../admin/admin_login.php">Login here</a></p>
    </div>
</body>
</html>
