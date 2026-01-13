<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>JKKNIU Guest House</title>
    <style>
        /* Reset defaults */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body, html {
            height: 100%;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow: hidden;
        }

        /* Slideshow container */
        .slideshow {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: -1;
        }

        .slide {
            position: absolute;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            opacity: 0;
            transition: opacity 1.5s ease-in-out;
        }

        .slide.active {
            opacity: 1;
        }

        /* Overlay for readability */
        .overlay {
            position: absolute;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            top: 0;
            left: 0;
            z-index: 0;
        }

        /* Container for content */
        .container {
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100%;
            text-align: center;
            padding: 20px;
            color: #fff;
        }

        /* Header */
        h1 {
            font-size: 60px;
            margin-bottom: 10px;
            text-shadow: 2px 2px 10px #000;
        }

        p.description {
            font-size: 22px;
            margin-bottom: 40px;
            text-shadow: 1px 1px 5px #000;
        }

        /* Buttons */
        a {
            display: inline-block;
            margin: 10px;
            padding: 15px 35px;
            font-size: 18px;
            text-decoration: none;
            color: #fff;
            background-color: #007BFF;
            border-radius: 8px;
            transition: 0.3s;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        a:hover {
            background-color: #0056b3;
            transform: translateY(-3px);
        }

        /* Footer */
        .footer {
            position: absolute;
            bottom: 15px;
            width: 100%;
            text-align: center;
            color: #ccc;
            font-size: 14px;
        }

        /* Responsive */
        @media (max-width: 600px) {
            h1 {
                font-size: 40px;
            }
            p.description {
                font-size: 18px;
            }
            a {
                font-size: 16px;
                padding: 12px 25px;
            }
        }
    </style>
</head>
<body>

<!-- Slideshow Background -->
<div class="slideshow">
    <div class="slide active" style="background-image: url('assets/images/bg1.jpg');"></div>
    <div class="slide" style="background-image: url('assets/images/bg2.jpg');"></div>
    <div class="slide" style="background-image: url('assets/images/bg3.jpg');"></div>
</div>

<div class="overlay"></div>

<div class="container">
    <!-- Header -->
    <h1>JKKNIU Guest House</h1>
    <p class="description">Book your stay easily and manage your reservations online</p>

    <!-- Dynamic Buttons -->
    <?php if(isset($_SESSION['admin_id'])): ?>
        <p>Welcome, Admin!</p>
        <a href="admin/dashboard.php">Go to Dashboard</a>
        <a href="logout.php">Logout</a>

    <?php elseif(isset($_SESSION['user_id'])): ?>
        <p>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
        <a href="guest/dashboard.php">Go to dashboard</a>
        <a href="logout.php">Logout</a>

    <?php else: ?>
        <a href="guest/login.php">Guest Login</a>
        <a href="guest/register.php">Guest Register</a>
        <a href="admin/admin_login.php">Admin Login</a>
    <?php endif; ?>
</div>

<div class="footer">
    &copy; <?php echo date("Y"); ?> JKKNIU Guest House. All Rights Reserved.
</div>

<!-- JavaScript for slideshow -->
<script>
    const slides = document.querySelectorAll('.slide');
    let current = 0;

    function showNextSlide() {
        slides[current].classList.remove('active');
        current = (current + 1) % slides.length;
        slides[current].classList.add('active');
    }

    setInterval(showNextSlide, 5000); // Change slide every 5 seconds
</script>

</body>
</html>
