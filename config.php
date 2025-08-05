<?php
// config.php

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root'); // Default XAMPP MySQL username
define('DB_PASSWORD', '');     // Default XAMPP MySQL password (empty)
define('DB_NAME', 'contact_form_db'); // The database name we created

// Attempt to connect to MySQL database
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>