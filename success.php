<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Include database connection file
include('connection.php');

// Get the logged-in user's first name and last name from the Users table
$username = $_SESSION['username'];
$sql = "SELECT FirstName, LastName FROM Users WHERE Username = '$username'";
$result = $connection->query($sql);

if ($result->num_rows == 0) {
    // If the user doesn't have first name and last name in the database, redirect them to aboutme.php
    header("Location: aboutme.php");
    exit();
}

$row = $result->fetch_assoc();
$firstName = $row['FirstName'];
$lastName = $row['LastName'];

// Check if both FirstName and LastName are blank
if (empty($firstName) && empty($lastName)) {
    header("Location: aboutme.php"); // Redirect to aboutme.php
    exit(); // Stop further execution
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Request Success</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <style>
        /* Add your custom styles here */
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h2 class="text-center text-success mb-4">Request Submitted Successfully!</h2>
                        <p class="text-center">Your document request has been successfully processed.</p>
                        <div class="text-center mt-4">
                            <a href="dashboard.php" class="btn btn-primary">Back to Dashboard</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
