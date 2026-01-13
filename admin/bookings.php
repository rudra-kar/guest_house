<?php
session_start();
include('../config/db.php');

if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit();
}

// Approve booking
if(isset($_GET['approve'])){
    $booking_id = $_GET['approve'];

    mysqli_query($conn, "UPDATE bookings SET status='Approved' WHERE booking_id='$booking_id'");

    $room_query = mysqli_query($conn, "
        SELECT r.price 
        FROM bookings b 
        JOIN rooms r ON b.room_id = r.room_id 
        WHERE b.booking_id='$booking_id'
    ");
    $room = mysqli_fetch_assoc($room_query);
    $amount = $room['price'];

    mysqli_query($conn, "INSERT INTO payments(booking_id, amount) VALUES('$booking_id', '$amount')");
}

if(isset($_GET['cancel'])){
    $booking_id = $_GET['cancel'];
    mysqli_query($conn, "UPDATE bookings SET status='Cancelled' WHERE booking_id='$booking_id'");
}

$bookings = mysqli_query($conn, "
    SELECT b.booking_id, u.name AS guest_name, r.room_number, r.room_type, 
           b.check_in, b.check_out, b.status
    FROM bookings b
    JOIN users u ON b.user_id = u.id
    JOIN rooms r ON b.room_id = r.room_id
    ORDER BY b.booking_id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Bookings</title>
    <style>
        body{
            font-family: Arial, sans-serif;
            background:#f4f6f9;
            margin:0;
            padding:20px;
        }
        h2{
            text-align:center;
            margin-bottom:20px;
        }
        table{
            width:100%;
            border-collapse:collapse;
            background:#fff;
            box-shadow:0 5px 15px rgba(0,0,0,.1);
        }
        th, td{
            padding:12px;
            text-align:center;
            border-bottom:1px solid #ddd;
        }
        th{
            background:#1d2671;
            color:white;
        }
        tr:hover{
            background:#f1f1f1;
        }
        a{
            text-decoration:none;
            padding:6px 10px;
            border-radius:4px;
            font-size:14px;
        }
        .approve{
            background:green;
            color:white;
        }
        .cancel{
            background:red;
            color:white;
        }
        .back{
            display:inline-block;
            margin-top:15px;
            background:#1d2671;
            color:white;
            padding:10px 15px;
            border-radius:5px;
        }
    </style>
</head>
<body>

<h2>Manage Bookings</h2>

<table>
<tr>
    <th>ID</th>
    <th>Guest Name</th>
    <th>Room No</th>
    <th>Room Type</th>
    <th>Check-In</th>
    <th>Check-Out</th>
    <th>Status</th>
    <th>Action</th>
</tr>

<?php
while($row = mysqli_fetch_assoc($bookings)){
    echo "<tr>";
    echo "<td>{$row['booking_id']}</td>";
    echo "<td>{$row['guest_name']}</td>";
    echo "<td>{$row['room_number']}</td>";
    echo "<td>{$row['room_type']}</td>";
    echo "<td>{$row['check_in']}</td>";
    echo "<td>{$row['check_out']}</td>";
    echo "<td>{$row['status']}</td>";
    echo "<td>";
    if($row['status'] == 'Pending'){
        echo "<a class='approve' href='bookings.php?approve={$row['booking_id']}'>Approve</a> ";
        echo "<a class='cancel' href='bookings.php?cancel={$row['booking_id']}' 
              onclick='return confirm(\"Are you sure?\")'>Cancel</a>";
    } else {
        echo "-";
    }
    echo "</td>";
    echo "</tr>";
}
?>

</table>

<a class="back" href="dashboard.php">⬅ Back to Dashboard</a>

</body>
</html>
