<?php
session_start();

// Clear session
session_unset();
session_destroy();

// Redirect to HOME PAGE
header("Location: index.php");
exit();
