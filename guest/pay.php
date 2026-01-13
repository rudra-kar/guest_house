<?php
session_start();
include('../config/db.php');

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$msg = "";

// Handle payment action
if(isset($_POST['pay'])){
    $payment_id = $_POST['payment_id'];

    // Update payment status to Paid and set payment date
    $stmt = $conn->prepare("UPDATE payments SET payment_status='Paid', payment_date=CURDATE() WHERE payment_id=?");
    $stmt->bind_param("i", $payment_id);

    if($stmt->execute()){
        $msg = "<div class='success'>Payment successful!</div>";
    } else {
        $msg = "<div class='error'>Payment failed. Try again.</div>";
    }

    $stmt->close();
}

// Fetch pending payments for this user
$payments = mysqli_query($conn, "
    SELECT p.payment_id, b.booking_id, r.room_number, r.room_type, p.amount, p.payment_status
    FROM payments p
    JOIN bookings b ON p.booking_id = b.booking_id
    JOIN rooms r ON b.room_id = r.room_id
    WHERE b.user_id='$user_id' AND p.payment_status='Pending'
    ORDER BY p.payment_id DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Pay - JKKNIU Guest House</title>
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
    max-width: 800px;
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

.success{
    background:#d4edda;
    color:#155724;
    padding:10px;
    border-radius:6px;
    margin-bottom:15px;
    text-align:center;
}

.error{
    background:#f8d7da;
    color:#721c24;
    padding:10px;
    border-radius:6px;
    margin-bottom:15px;
    text-align:center;
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

.amount{
    font-weight:bold;
    color:#007bff;
}

button.pay-btn{
    padding:8px 15px;
    background:#28a745;
    color:white;
    border:none;
    border-radius:6px;
    cursor:pointer;
}

button.pay-btn:hover{
    background:#218838;
}

.actions{
    margin-top:25px;
    text-align:center;
}

.actions a{
    margin:0 10px;
    padding:10px 18px;
    background:#6c757d;
    color:#fff;
    text-decoration:none;
    border-radius:6px;
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
    <h2>Make Payment</h2>

    <?php if(!empty($msg)) echo $msg; ?>

    <?php if(mysqli_num_rows($payments) > 0){ ?>
    <table>
        <tr>
            <th>Payment ID</th>
            <th>Booking ID</th>
            <th>Room No</th>
            <th>Room Type</th>
            <th>Amount</th>
            <th>Action</th>
        </tr>
        <?php while($row = mysqli_fetch_assoc($payments)){ ?>
        <tr>
            <td><?= $row['payment_id']; ?></td>
            <td><?= $row['booking_id']; ?></td>
            <td><?= $row['room_number']; ?></td>
            <td><?= $row['room_type']; ?></td>
            <td class="amount">৳ <?= $row['amount']; ?></td>
            <td>
                <form method="post" style="margin:0;">
                    <input type="hidden" name="payment_id" value="<?= $row['payment_id']; ?>">
                    <button type="submit" name="pay" class="pay-btn" onclick="return confirm('Are you sure you want to pay?')">Pay</button>
                </form>
            </td>
        </tr>
        <?php } ?>
    </table>
    <?php } else { ?>
        <p style="text-align:center;">No pending payments found.</p>
    <?php } ?>

    <div class="actions">
        <a href="payments.php">Back to Payments</a>
        <a href="dashboard.php">Go to Dashboard</a>
        <a href="../logout.php">Logout</a>
    </div>
</div>

</body>
</html>
