<?php
session_start();
include('../config/db.php');

if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit();
}

/* ---------- ADD ROOM ---------- */
if(isset($_POST['add_room'])){
    $room_number = $_POST['room_number'];
    $room_type = $_POST['room_type'];
    $price = $_POST['price'];

    mysqli_query($conn, "INSERT INTO rooms(room_number, room_type, price) 
                         VALUES('$room_number','$room_type','$price')");
    $msg = "Room added successfully.";
}

/* ---------- UPDATE ROOM ---------- */
if(isset($_POST['update_room'])){
    $room_id = $_POST['room_id'];
    $room_number = $_POST['room_number'];
    $room_type = $_POST['room_type'];
    $price = $_POST['price'];

    mysqli_query($conn, "UPDATE rooms 
                         SET room_number='$room_number',
                             room_type='$room_type',
                             price='$price'
                         WHERE room_id='$room_id'");
    $msg = "Room updated successfully.";
}

/* ---------- TOGGLE STATUS ---------- */
if(isset($_GET['status'])){
    $room_id = $_GET['status'];
    $room = mysqli_query($conn, "SELECT status FROM rooms WHERE room_id='$room_id'");
    $r = mysqli_fetch_assoc($room);
    $new_status = ($r['status'] == 'Available') ? 'Occupied' : 'Available';
    mysqli_query($conn, "UPDATE rooms SET status='$new_status' WHERE room_id='$room_id'");
    header("Location: rooms.php");
    exit();
}

/* ---------- EDIT ROOM FETCH ---------- */
$edit_room = null;
if(isset($_GET['edit'])){
    $edit_id = $_GET['edit'];
    $edit_query = mysqli_query($conn, "SELECT * FROM rooms WHERE room_id='$edit_id'");
    $edit_room = mysqli_fetch_assoc($edit_query);
}

/* ---------- FETCH ROOMS ---------- */
$rooms = mysqli_query($conn, "SELECT * FROM rooms ORDER BY room_id DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Rooms</title>
<style>
/* ✅ YOUR ORIGINAL DESIGN — UNCHANGED */
body{margin:0;font-family:Arial,sans-serif;background:#f4f6f9}
.header{background:#1d2671;color:white;padding:20px;text-align:center;font-size:24px;font-weight:bold}
.container{max-width:1100px;margin:30px auto;background:white;padding:25px;border-radius:10px;box-shadow:0 5px 15px rgba(0,0,0,.15)}
h2{margin-top:0;color:#1d2671;text-align:center}
.msg{background:#dff0d8;color:#3c763d;padding:10px;border-radius:5px;margin-bottom:15px;text-align:center}
form{display:grid;grid-template-columns:repeat(3,1fr);gap:15px;margin-bottom:25px}
input{padding:10px;border:1px solid #ccc;border-radius:5px;width:100%}
.add-btn{grid-column:span 3;padding:12px;background:#27ae60;color:white;border:none;border-radius:6px;cursor:pointer;font-size:16px}
.add-btn:hover{background:#219150}
.update-btn{grid-column:span 3;padding:12px;background:#f39c12;color:white;border:none;border-radius:6px;cursor:pointer;font-size:16px}
table{width:100%;border-collapse:collapse}
table th{background:#1d2671;color:white;padding:10px}
table td{padding:10px;text-align:center;border-bottom:1px solid #ddd}
.available{color:green;font-weight:bold}
.occupied{color:red;font-weight:bold}
.toggle{padding:6px 12px;background:#2980b9;color:white;text-decoration:none;border-radius:4px;font-size:14px}
.edit{padding:6px 12px;background:#8e44ad;color:white;text-decoration:none;border-radius:4px;font-size:14px}
.back{display:inline-block;margin-top:20px;text-decoration:none;background:#1d2671;color:white;padding:10px 15px;border-radius:5px}
</style>
</head>

<body>

<div class="header">Manage Rooms</div>

<div class="container">
<h2><?= $edit_room ? "Edit Room" : "Add New Room"; ?></h2>

<?php if(isset($msg)) echo "<div class='msg'>$msg</div>"; ?>

<form method="post">
    <input type="hidden" name="room_id" value="<?= $edit_room['room_id'] ?? ''; ?>">
    <input type="text" name="room_number" placeholder="Room Number"
           value="<?= $edit_room['room_number'] ?? ''; ?>" required>
    <input type="text" name="room_type" placeholder="Room Type"
           value="<?= $edit_room['room_type'] ?? ''; ?>" required>
    <input type="number" step="0.01" name="price" placeholder="Price"
           value="<?= $edit_room['price'] ?? ''; ?>" required>

    <?php if($edit_room){ ?>
        <button class="update-btn" name="update_room">Update Room</button>
    <?php } else { ?>
        <button class="add-btn" name="add_room">Add Room</button>
    <?php } ?>
</form>

<h2>Room List</h2>

<table>
<tr>
    <th>ID</th>
    <th>Room Number</th>
    <th>Type</th>
    <th>Price</th>
    <th>Status</th>
    <th>Action</th>
</tr>

<?php while($row = mysqli_fetch_assoc($rooms)){ ?>
<tr>
    <td><?= $row['room_id']; ?></td>
    <td><?= $row['room_number']; ?></td>
    <td><?= $row['room_type']; ?></td>
    <td><?= $row['price']; ?> ৳</td>
    <td class="<?= strtolower($row['status']); ?>"><?= $row['status']; ?></td>
    <td>
        <a class="edit" href="rooms.php?edit=<?= $row['room_id']; ?>">Edit</a>
        <a class="toggle" href="rooms.php?status=<?= $row['room_id']; ?>">Toggle</a>
    </td>
</tr>
<?php } ?>
</table>

<a class="back" href="dashboard.php">⬅ Back to Dashboard</a>
</div>

</body>
</html>
