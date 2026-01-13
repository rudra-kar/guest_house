<?php
session_start();
include('../config/db.php');

if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit();
}

// Mark payment as Paid
if(isset($_GET['pay'])){
    $payment_id = $_GET['pay'];
    mysqli_query($conn, "UPDATE payments SET payment_status='Paid', payment_date=CURDATE() WHERE payment_id='$payment_id'");
}

// Fetch all payments
$payments = mysqli_query($conn, "
    SELECT p.payment_id, b.booking_id, u.name AS guest_name, r.room_number, r.room_type, 
           p.amount, p.payment_status, p.payment_date
    FROM payments p
    JOIN bookings b ON p.booking_id = b.booking_id
    JOIN users u ON b.user_id = u.id
    JOIN rooms r ON b.room_id = r.room_id
    ORDER BY p.payment_id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Payments Management</title>
    <style>
        body{
            margin:0;
            font-family: Arial, sans-serif;
            background:#f4f6f9;
        }

        .header{
            background:#1d2671;
            color:white;
            padding:20px;
            text-align:center;
            font-size:24px;
            font-weight:bold;
        }

        .container{
            max-width:1200px;
            margin:30px auto;
            background:white;
            padding:20px;
            border-radius:10px;
            box-shadow:0 5px 15px rgba(0,0,0,.15);
        }

        h2{
            margin-top:0;
            color:#1d2671;
            text-align:center;
        }

        table{
            width:100%;
            border-collapse:collapse;
            margin-top:20px;
        }

        table th{
            background:#1d2671;
            color:white;
            padding:10px;
        }

        table td{
            padding:10px;
            text-align:center;
            border-bottom:1px solid #ddd;
        }

        tr:hover{
            background:#f1f1f1;
        }

        .paid{
            color:green;
            font-weight:bold;
        }

        .pending{
            color:#d35400;
            font-weight:bold;
        }

        .btn{
            padding:6px 12px;
            background:#27ae60;
            color:white;
            text-decoration:none;
            border-radius:4px;
            font-size:14px;
        }

        .btn:hover{
            background:#219150;
        }

        .back{
            display:inline-block;
            margin-top:20px;
            text-decoration:none;
            background:#1d2671;
            color:white;
            padding:10px 15px;
            border-radius:5px;
        }

        .back:hover{
            background:#142056;
        }
    </style>
</head>
<body>

<div class="header">
    Payments Management
</div>

<div class="container">
    <h2>All Payments</h2>

    <table>
        <tr>
            <th>Payment ID</th>
            <th>Booking ID</th>
            <th>Guest Name</th>
            <th>Room No</th>
            <th>Room Type</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Payment Date</th>
            <th>Action</th>
        </tr>

        <?php while($row = mysqli_fetch_assoc($payments)){ ?>
        <tr>
            <td><?= $row['payment_id']; ?></td>
            <td><?= $row['booking_id']; ?></td>
            <td><?= $row['guest_name']; ?></td>
            <td><?= $row['room_number']; ?></td>
            <td><?= $row['room_type']; ?></td>
            <td><?= $row['amount']; ?> ৳</td>
            <td class="<?= strtolower($row['payment_status']); ?>">
                <?= $row['payment_status']; ?>
            </td>
            <td><?= $row['payment_date'] ?? '-'; ?></td>
            <td>
                <?php if($row['payment_status'] == 'Pending'){ ?>
                    <a class="btn" href="payments.php?pay=<?= $row['payment_id']; ?>" 
                       onclick="return confirm('Mark this payment as Paid?')">
                        Mark Paid
                    </a>
                <?php } else { echo "-"; } ?>
            </td>
        </tr>
        <?php } ?>
    </table>

    <a class="back" href="dashboard.php">⬅ Back to Dashboard</a>
</div>

</body>
</html>
