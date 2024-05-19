<?php
session_start();

// Include database connection file
include('connection.php');

// Check if the token is provided in the URL
if (!isset($_GET['token']) || empty($_GET['token'])) {
    echo "<script>alert('Invalid token.'); window.location.href = 'login.php';</script>";
    exit();
}

$token = $_GET['token'];

// Verify the token in the database
$query = "SELECT Username FROM Users WHERE ResetToken = ?";
$stmt = $connection->prepare($query);
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// If token is not found in the database, display an error message
if (!$row) {
    echo "<script>alert('Invalid token.'); window.location.href = 'login.php';</script>";
    exit();
}

$username = $row['Username'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user input
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    // Check if new password matches the confirm password
    if ($newPassword !== $confirmPassword) {
        echo "<script>alert('New password and confirm password do not match.');</script>";
    } else {
        // Hash the new password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update the password and clear the reset token in the database
        $updateQuery = "UPDATE Users SET Password = ?, ResetToken = NULL WHERE Username = ?";
        $updateStmt = $connection->prepare($updateQuery);
        $updateStmt->bind_param("ss", $hashedPassword, $username);
        $updateStmt->execute();

        echo "<script>alert('Password changed successfully.'); window.location.href = 'login.php';</script>";
        exit();
    }
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Reset Password</h4>
                    </div>
                    <div class="card-body">
                        <form id="resetForm" method="POST" action="">
                            <div class="form-group">
                                <label for="newPassword">New Password</label>
                                <input type="password" class="form-control" id="newPassword" name="newPassword" required>
                            </div>
                            <div class="form-group">
                                <label for="confirmPassword">Confirm New Password</label>
                                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Reset Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
