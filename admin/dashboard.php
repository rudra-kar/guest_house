<?php
session_start();
include('../config/db.php');

// Check if admin is logged in
if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body{
            margin:0;
            font-family: Arial, sans-serif;
            background:#f4f6f9;
        }

        /* Header */
        .header{
            background:#1d2671;
            color:white;
            padding:20px;
            text-align:center;
            font-size:24px;
            font-weight:bold;
        }

        /* Container */
        .container{
            max-width:900px;
            margin:40px auto;
            padding:20px;
        }

        /* Dashboard cards */
        .cards{
            display:grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap:20px;
        }

        .card{
            background:white;
            padding:25px;
            text-align:center;
            border-radius:10px;
            box-shadow:0 5px 15px rgba(0,0,0,.15);
            transition:transform .2s;
        }

        .card:hover{
            transform:translateY(-5px);
        }

        .card a{
            text-decoration:none;
            color:#1d2671;
            font-size:18px;
            font-weight:bold;
            display:block;
        }

        /* Logout button */
        .logout{
            margin-top:30px;
            text-align:center;
        }

        .logout a{
            background:#c33764;
            color:white;
            padding:12px 20px;
            border-radius:5px;
            text-decoration:none;
            font-weight:bold;
        }

        .logout a:hover{
            background:#a52b50;
        }

        /* Footer */
        .footer{
            text-align:center;
            margin-top:40px;
            color:#777;
            font-size:14px;
        }
    </style>
</head>
<body>

<div class="header">
    Admin Dashboard
</div>

<div class="container">

    <div class="cards">
        <div class="card">
            <a href="rooms.php">🏨 Manage Rooms</a>
        </div>

        <div class="card">
            <a href="bookings.php">📋 Manage Bookings</a>
        </div>

        <div class="card">
            <a href="payments.php">💳 View Payments</a>
        </div>
    </div>

    <div class="logout">
        <a href="../logout.php">🚪 Logout</a>
    </div>

</div>

<div class="footer">
    Guest House Management System © <?php echo date('Y'); ?>
</div>

</body>
</html>
