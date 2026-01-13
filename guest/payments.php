<?php
session_start();
include('../config/db.php');

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$payments = mysqli_query($conn, "
    SELECT p.payment_id, b.booking_id, r.room_number, r.room_type,
           p.amount, p.payment_status, p.payment_date
    FROM payments p
    JOIN bookings b ON p.booking_id = b.booking_id
    JOIN rooms r ON b.room_id = r.room_id
    WHERE b.user_id='$user_id'
    ORDER BY p.payment_id DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Payments - JKKNIU Guest House</title>

<style>
body{
    font-family: Arial, sans-serif;
    background: url('../assets/images/bg2.jpg') no-repeat center center fixed;
    background-size: cover;
    margin:0;
}

.overlay{
    position: fixed;
    width:100%;
    height:100%;
    background: rgba(0,0,0,0.6);
    top:0;
    left:0;
    z-index:0;
}

.container{
    position: relative;
    z-index:1;
    max-width: 1100px;
    margin: 60px auto;
    background: rgba(255,255,255,0.96);
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
}

h2{
    text-align:center;
    margin-bottom:25px;
    color:#333;
}

table{
    width:100%;
    border-collapse: collapse;
}

th{
    background:#343a40;
    color:#fff;
    padding:12px;
}

td{
    padding:12px;
    border-bottom:1px solid #ddd;
    text-align:center;
}

tr:hover{
    background:#f2f2f2;
}

.status-paid{
    background:#d4edda;
    color:#155724;
    padding:6px 14px;
    border-radius:20px;
}

.status-pending{
    background:#fff3cd;
    color:#856404;
    padding:6px 14px;
    border-radius:20px;
}

.status-failed{
    background:#f8d7da;
    color:#721c24;
    padding:6px 14px;
    border-radius:20px;
}

.amount{
    font-weight:bold;
    color:#007bff;
}

.actions{
    margin-top:25px;
    text-align:center;
}

.actions a{
    margin:0 10px;
    padding:10px 18px;
    background:#28a745;
    color:#fff;
    text-decoration:none;
    border-radius:6px;
}

.actions a.logout{
    background:#6c757d;
}

.actions a:hover{
    opacity:0.9;
}

@media(max-width:768px){
    table, th, td{
        font-size:14px;
    }
}
</style>
</head>

<body>
<div class="overlay"></div>

<div class="container">
    <h2>My Payments</h2>

    <table>
        <tr>
            <th>Payment ID</th>
            <th>Booking ID</th>
            <th>Room No</th>
            <th>Room Type</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Payment Date</th>
        </tr>

        <?php
        if(mysqli_num_rows($payments) > 0){
            while($row = mysqli_fetch_assoc($payments)){
                $statusClass = strtolower($row['payment_status']);

                echo "<tr>";
                echo "<td>{$row['payment_id']}</td>";
                echo "<td>{$row['booking_id']}</td>";
                echo "<td>{$row['room_number']}</td>";
                echo "<td>{$row['room_type']}</td>";
                echo "<td class='amount'>৳ {$row['amount']}</td>";
                echo "<td><span class='status-$statusClass'>{$row['payment_status']}</span></td>";
                echo "<td>{$row['payment_date']}</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No payment records found.</td></tr>";
        }
        ?>
    </table>

    <div class="actions">
        <a href="pay.php">Pay</a>
        <a href="dashboard.php">Go to dashboard</a>
        <a class="logout" href="../logout.php">Logout</a>
    </div>
</div>

</body>
</html>
