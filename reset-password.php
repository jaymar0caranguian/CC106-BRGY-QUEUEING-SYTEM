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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user input
    $username = $_SESSION['username'];
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    // Check if new password matches the confirm password
    if ($newPassword !== $confirmPassword) {
        echo "<script>
                alert('New password and confirm password do not match.');
                window.location = 'reset-password.php';
            </script>";
        exit();
    }

    // Retrieve the current password from the database
    $query = "SELECT Password FROM Users WHERE Username = ?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // Check if the user exists
    if (!$row) {
        echo "<script>
                alert('User not found.');
                window.location = 'reset-password.php';
            </script>";
        exit();
    }

    $currentPasswordHash = $row['Password'];

    // Verify if the entered current password matches the one in the database
    if (!password_verify($currentPassword, $currentPasswordHash)) {
        echo "<script>
                alert('Current password is incorrect.');
                window.location = 'reset-password.php';
            </script>";
        exit();
    }

    // Hash the new password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Update the password in the database
    $updateQuery = "UPDATE Users SET Password = ? WHERE Username = ?";
    $updateStmt = $connection->prepare($updateQuery);
    $updateStmt->bind_param("ss", $hashedPassword, $username);
    $updateStmt->execute();

    echo "<script>
            alert('Password changed successfully.');
            window.location = 'dashboard.php';
          </script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Password Reset</title>
  <!-- Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            <h4>Password Reset</h4>
          </div>
          <div class="card-body">
            <form id="resetForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
              <div class="form-group">
                <label for="currentPassword">Current Password</label>
                <input type="password" class="form-control" id="currentPassword" name="currentPassword" required>
              </div>
              <div class="form-group">
                <label for="newPassword">New Password</label>
                <input type="password" class="form-control" id="newPassword" name="newPassword" required>
              </div>
              <div class="form-group">
                <label for="confirmPassword">Confirm New Password</label>
                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
              </div>
              <button type="submit" class="btn btn-primary">Reset Password</button>
              <a href="dashboard.php" class="btn btn-danger">Cancel</a>
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
