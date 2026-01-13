<?php
session_start();
include('../config/db.php');

// Make sure the user is logged in
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

// Handle cancellation request
if(isset($_GET['cancel'])){
    $booking_id = $_GET['cancel'];

    // Only cancel if status is Pending
    $check = mysqli_query($conn, "SELECT status FROM bookings WHERE booking_id='$booking_id' AND user_id='$user_id'");
    $row = mysqli_fetch_assoc($check);

    if($row && $row['status'] == 'Pending'){
        mysqli_query($conn, "UPDATE bookings SET status='Cancelled' WHERE booking_id='$booking_id'");
        $message = "<div class='success'>Booking #$booking_id has been cancelled.</div>";
    } else {
        $message = "<div class='error'>Cannot cancel this booking.</div>";
    }
}

// Fetch bookings for this user
$query = "
    SELECT b.booking_id, r.room_number, r.room_type, b.check_in, b.check_out, b.status
    FROM bookings b
    JOIN rooms r ON b.room_id = r.room_id
    WHERE b.user_id = '$user_id'
    ORDER BY b.booking_id DESC
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Bookings - JKKNIU Guest House</title>

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
    background: rgba(255,255,255,0.95);
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
    background:#007bff;
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

.status-pending{
    color:#856404;
    background:#fff3cd;
    padding:6px 12px;
    border-radius:20px;
}

.status-approved{
    color:#155724;
    background:#d4edda;
    padding:6px 12px;
    border-radius:20px;
}

.status-cancelled{
    color:#721c24;
    background:#f8d7da;
    padding:6px 12px;
    border-radius:20px;
}

.cancel-btn{
    background:#dc3545;
    color:#fff;
    padding:6px 12px;
    border-radius:5px;
    text-decoration:none;
}

.cancel-btn:hover{
    background:#c82333;
}

.actions{
    margin-top:25px;
    text-align:center;
}

.actions a{
    margin: 0 10px;
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

.success{
    background:#d4edda;
    color:#155724;
    padding:12px;
    border-radius:6px;
    text-align:center;
    margin-bottom:15px;
}

.error{
    background:#f8d7da;
    color:#721c24;
    padding:12px;
    border-radius:6px;
    text-align:center;
    margin-bottom:15px;
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
    <h2>My Bookings</h2>

    <?php if(!empty($message)) echo $message; ?>

    <table>
        <tr>
            <th>Booking ID</th>
            <th>Room No</th>
            <th>Room Type</th>
            <th>Check-In</th>
            <th>Check-Out</th>
            <th>Status</th>
            <th>Action</th>
        </tr>

        <?php
        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_assoc($result)){
                echo "<tr>";
                echo "<td>{$row['booking_id']}</td>";
                echo "<td>{$row['room_number']}</td>";
                echo "<td>{$row['room_type']}</td>";
                echo "<td>{$row['check_in']}</td>";
                echo "<td>{$row['check_out']}</td>";

                $statusClass = strtolower($row['status']);
                echo "<td><span class='status-$statusClass'>{$row['status']}</span></td>";

                echo "<td>";
                if($row['status'] == 'Pending'){
                    echo "<a class='cancel-btn' href='my_bookings.php?cancel={$row['booking_id']}' 
                          onclick='return confirm(\"Are you sure you want to cancel this booking?\")'>Cancel</a>";
                } else {
                    echo "-";
                }
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No bookings found.</td></tr>";
        }
        ?>
    </table>

    <div class="actions">
        <a href="book_room.php">Book New Room</a>
        <a href="dashboard.php">Go to dashboard</a>


        <a class="logout" href="../logout.php">Logout</a>
    </div>
</div>

</body>
</html>
