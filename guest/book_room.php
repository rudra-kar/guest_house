<?php
session_start();
include('../config/db.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$message = ""; // To display success/error messages

// Fetch available rooms for dropdown
$rooms_result = mysqli_query($conn, "SELECT room_id, room_number, room_type FROM rooms WHERE status='Available' ORDER BY room_number ASC");

// Handle booking form submission
if (isset($_POST['book'])) {
    $room_id = $_POST['room_id'];
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];

    // Validate input
    if (empty($room_id) || empty($check_in) || empty($check_out)) {
        $message = "<div class='error'>All fields are required!</div>";
    } else {
        // Prepare SQL statement to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO bookings(user_id, room_id, check_in, check_out) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiss", $user_id, $room_id, $check_in, $check_out);

        if ($stmt->execute()) {
            $message = "<div class='success'>Booking Request Submitted Successfully!</div>";
        } else {
            $message = "<div class='error'>Error: " . $stmt->error . "</div>";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book a Room - JKKNIU Guest House</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('../assets/images/bg3.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
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

        .booking-container {
            position: relative;
            z-index: 1;
            max-width: 500px;
            margin: 80px auto;
            background: rgba(255,255,255,0.95);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
        }

        .booking-container h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }

        .booking-container input, .booking-container select {
            width: 100%;
            padding: 12px;
            margin: 12px 0;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        .booking-container button {
            width: 100%;
            padding: 12px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 18px;
            cursor: pointer;
            transition: 0.3s;
        }

        .booking-container button:hover {
            background-color: #218838;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 15px;
            text-align: center;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 15px;
            text-align: center;
        }

        @media (max-width: 550px) {
            .booking-container {
                margin: 40px 20px;
                padding: 30px;
            }
        }
    </style>
</head>
<body>
    <div class="overlay"></div>

    <div class="booking-container">
        <h2>Book a Room</h2>

        <?php if(!empty($message)) echo $message; ?>

        <form method="post">
            <label for="room_id">Select Room</label>
            <select id="room_id" name="room_id" required>
                <option value="">-- Choose a Room --</option>
                <?php while($room = mysqli_fetch_assoc($rooms_result)): ?>
                    <option value="<?= $room['room_id']; ?>">
                        <?= $room['room_number']; ?> - <?= $room['room_type']; ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label for="check_in">Check-in Date</label>
            <input type="date" id="check_in" name="check_in" required>

            <label for="check_out">Check-out Date</label>
            <input type="date" id="check_out" name="check_out" required>

            <button type="submit" name="book">Book Room</button>
        </form>
    </div>
</body>
</html>
