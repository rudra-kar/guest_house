<?php
session_start();
include('../config/db.php');

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'] ?? "Guest"; // Display guest's name if available
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Guest Dashboard</title>
    <style>
        /* Reset & Body */
        * { box-sizing: border-box; margin:0; padding:0; }
        body { font-family: 'Arial', sans-serif; background: #f4f6f9; }

        /* Header */
        .header {
            background: linear-gradient(90deg, #1d2671, #3a4c99);
            color: white;
            padding: 25px 20px;
            text-align: center;
            font-size: 28px;
            font-weight: bold;
            letter-spacing: 1px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }

        /* Container */
        .container {
            max-width: 900px;
            margin: 50px auto;
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            text-align: center;
        }

        /* Welcome */
        .container h2 {
            margin-bottom: 40px;
            color: #1d2671;
            font-size: 26px;
        }

        /* Dashboard Links */
        .dashboard-links {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 25px;
            justify-items: center;
        }

        .dashboard-links a {
            display: block;
            padding: 20px 25px;
            width: 100%;
            background: #28a745;
            color: white;
            text-decoration: none;
            font-size: 18px;
            font-weight: 600;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: 0.3s;
        }

        .dashboard-links a:hover {
            background: #218838;
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }

        /* Responsive */
        @media(max-width: 500px){
            .container { padding: 25px; }
            .dashboard-links a { font-size: 16px; padding: 15px 20px; }
        }
    </style>
</head>
<body>

<div class="header">Guest Dashboard</div>

<div class="container">
    <h2>Welcome, <?= htmlspecialchars($user_name); ?>!</h2>

    <div class="dashboard-links">
        <a href="book_room.php"> Book a Room</a>
        <a href="my_bookings.php"> My Bookings</a>
        <a href="payments.php"> My Payments</a>
        <a href="../logout.php"> Logout</a>
    </div>
</div>

</body>
</html>
