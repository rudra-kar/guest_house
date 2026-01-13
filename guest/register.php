<?php
include('../config/db.php');

$message = "";

if(isset($_POST['register'])){
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = md5($_POST['password']); // md5 used for consistency with your login

    // Check if email already exists
    $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if(mysqli_num_rows($check) > 0){
        $message = "<div class='error'>Email already registered!</div>";
    } else {
        $query = "INSERT INTO users(name,email,password) 
                  VALUES('$name','$email','$password')";
        if(mysqli_query($conn, $query)){
            $message = "<div class='success'>Registration Successful! <a href='login.php'>Login now</a></div>";
        } else {
            $message = "<div class='error'>Something went wrong. Try again.</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Guest Registration - JKKNIU Guest House</title>

<style>
body{
    font-family: Arial, sans-serif;
    background: url('../assets/images/bg2.jpg') no-repeat center center fixed;
    background-size: cover;
    margin:0;
    height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
}

.overlay{
    position:absolute;
    width:100%;
    height:100%;
    background: rgba(0,0,0,0.6);
    top:0;
    left:0;
    z-index:0;
}

.register-box{
    position:relative;
    z-index:1;
    background: rgba(0,0,0,0.75);
    padding:40px;
    width:100%;
    max-width:420px;
    border-radius:12px;
    box-shadow:0 8px 25px rgba(0,0,0,0.4);
    color:#fff;
    text-align:center;
}

.register-box h2{
    margin-bottom:25px;
    color:#ffd700;
    text-shadow:1px 1px 5px #000;
}

.register-box input{
    width:100%;
    padding:12px;
    margin:12px 0;
    border:none;
    border-radius:6px;
    font-size:16px;
}

.register-box button{
    width:100%;
    padding:12px;
    background:#28a745;
    color:#fff;
    border:none;
    border-radius:6px;
    font-size:18px;
    cursor:pointer;
    transition:0.3s;
}

.register-box button:hover{
    background:#218838;
}

.success{
    background:#d4edda;
    color:#155724;
    padding:12px;
    border-radius:6px;
    margin-bottom:15px;
}

.success a{
    color:#155724;
    font-weight:bold;
}

.error{
    background:#f8d7da;
    color:#721c24;
    padding:12px;
    border-radius:6px;
    margin-bottom:15px;
}

.register-box p{
    margin-top:15px;
}

.register-box a{
    color:#ffc107;
    text-decoration:none;
}

.register-box a:hover{
    text-decoration:underline;
}

@media(max-width:480px){
    .register-box{
        padding:30px;
    }
}
</style>
</head>

<body>
<div class="overlay"></div>

<div class="register-box">
    <h2>Guest Registration</h2>

    <?php if(!empty($message)) echo $message; ?>

    <form method="post">
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email Address" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="register">Register</button>
    </form>

    <p>Already have an account? <a href="login.php">Login here</a></p>
</div>

</body>
</html>
