<?php
// Database connection parameters
$host = 'localhost'; 
$dbname = 'imbsqms'; 
$username = 'bsqms'; 
$password = '&o0T**8Ue6vi'; 

// Create mysqli connection
$connection = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Set charset to UTF-8
$connection->set_charset("utf8");
?>
