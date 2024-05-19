<?php
session_start();

// Include database connection file
include('connection.php');

// Include PHPMailer autoload file
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user input
    $email = $_POST['email'];

    // Check if the email exists in the database
    $query = "SELECT Email FROM Users WHERE Email = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // If email is found, generate a password reset token and store it in the database
    if ($row) {
        // Generate a unique token for password reset
        $token = bin2hex(random_bytes(32));

        // Generate timestamp for token expiration (5 minutes from now)
        $tokenExpiry = date('Y-m-d H:i:s', strtotime('+5 minutes'));

        // Update the ResetToken and ResetTokenExpiry columns in the Users table
        $updateQuery = "UPDATE Users SET ResetToken = ?, ResetTokenExpiry = ? WHERE Email = ?";
        $updateStmt = $connection->prepare($updateQuery);
        $updateStmt->bind_param("sss", $token, $tokenExpiry, $email);
        $updateStmt->execute();

        // Send reset link to user's email
        try {
            // Create a new PHPMailer instance
            $mail = new PHPMailer(true);

            // SMTP configuration
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'vskymro@gmail.com';
            $mail->Password = 'phea uiej zpvx ymrx'; // Use your app-specific password here
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // Sender and recipient
            $mail->setFrom('vskymro@gmail.com', 'Nagkaisang Nayon');
            $mail->addAddress($email); // Add recipient dynamically

            // Email content
            $mail->isHTML(false);
            $mail->Subject = 'Password Reset Link';
            $mail->Body = "Dear Resident,\r\n\r\nPlease click the following link to reset your password:\r\nhttps://nagkaisangnayon.live/reset-forgot-password.php?token=$token\r\n\r\nIf you did not request a password reset, please ignore this email.\r\n\r\nBest regards,\r\nNagkaisang Nayon Dev";

            // Send email
            $mail->send();

            echo "<script>alert('Password reset link sent to your email.');</script>";
        } catch (Exception $e) {
            echo "Failed to send email. Error: {$mail->ErrorInfo}";
        }
    } else {
        // If email not found, display an error message
        echo "<script>alert('Email not found in our records.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Forgot Password</h4>
                    </div>
                    <div class="card-body">
                        <form id="forgotPasswordForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Reset Password</button>
                            <a href="login.php" class="btn btn-secondary">Cancel</a>
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
