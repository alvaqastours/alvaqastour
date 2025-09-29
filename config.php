<?php
$servername = "sql109.infinityfree.com";   
$username   = "if0_39882333";     // your InfinityFree MySQL username
$password   = "9JGGw5gifTvp460";   // the MySQL password you set
$dbname     = "if0_39882333_newsletter"; // your database name
$port       = 3306;                 // InfinityFree always uses 3306

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die(json_encode([
        "status" => "error",
        "message" => "Database connection failed: " . $conn->connect_error
    ]));
}
?>
