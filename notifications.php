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

// Fetch user data based on the username stored in the session
$username = $_SESSION['username'];
$query = "SELECT * FROM Users WHERE username = '$username'";
$result = $connection->query($query);

// Check if user data exists
if ($result && $result->num_rows > 0) {
    // Extract user data
    $userData = $result->fetch_assoc();
    $residentID = $userData['ResidentID'];

    // Query to fetch all requests made by the user that have an ApproveDate and Status
    $requestQuery = "SELECT * FROM DocumentRequest WHERE ResidentID = $residentID AND ApproveDate IS NOT NULL AND Status = 'Approved'";
    $requestResult = $connection->query($requestQuery);
} else {
    // Handle case when user data is not found
    echo "Error: User data not found.";
    // You may redirect the user or display an error message as needed
}
?>

<!DOCTYPE html>
<head>
    <?php include("components/headers.php"); ?>
    <title>Barangay System | Notifications</title>

    <style>
        * {
            box-sizing: border-box;
        }

        .blur-bg {
            filter: blur(6px);
            -webkit-filter: blur(6px);
            transform: scale(1.01);
            background-image: url("./assets/baguio-travel.jpg");
            background-attachment: fixed;
            background-repeat: no-repeat;
            background-size: cover, contain;
            background-position: center;
            height: 100%;
            width: 100%;
            position: fixed;
            z-index: -1;
        }

        .main-content {
            padding-left: 24px;
            padding-right: 24px;
        }

        .brgy-msg {
            font-size: 2em;
            color: #3d5184;
            font-weight: bold;
            text-shadow: 2px 2px white;
            letter-spacing: 16px;
            text-transform: uppercase;
        }

        .bg-prim {
            background-color: #3d5184;
        }

        .notification-read {
            background-color: #EEEEEE; 
        }
    </style>
</head>
<body>
<div class="blur-bg"></div>

<div class="p-4 main-content">
    <h4 align="center" class="brgy-msg">Barangay Nagkaisang Nayon</h4>
    <br/>

    <?php include("components/navbar.php"); ?>
    <div class="bg-white p-4">
        <br/>
        <h4 class="text-dark text-uppercase fw-bold">Notifications</h4>

        <div class="card px-4" style="background-color: #eeeeee">
            <br/>

            <?php
            // Check if there are any notifications
            if ($requestResult && $requestResult->num_rows > 0) {
                // Loop through each notification and display it
                while ($row = $requestResult->fetch_assoc()) {
                    // Check if the notification is marked as read
                    $referenceID = $row['ReferenceID'];
                    $isRead = isset($_SESSION['read_notifications'][$referenceID]);
                    ?>
                    <div class="card card-body <?php echo $isRead ? 'notification-read' : ''; ?>">
                        <div class="row">
                            <div class="col-8">
                                <?php if ($row['Status'] === 'Approved' && $row['ApproveDate'] !== null) { ?>
                                    <p class="pt-2 mb-0">
                                        Your request with reference number: <?php echo $row['ReferenceID']; ?> [<?php echo $row['DocumentType']; ?>] has been approved. It is now ready for claiming.
                                    </p>
                                <?php } ?>
                            </div>

                            <div class="col-4" align="right">
                                <?php if (!$isRead) { ?>
                                    <button class="btn btn-info mark-as-read" data-reference-id="<?php echo $referenceID; ?>">Mark as read</button>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <br/>
                    <?php
                }
            } else {
                // Display a message if there are no notifications
                ?>
                <div class="card card-body">
                    <center><p class="text-uppercase pt-2 mb-0">No notifications found.</p></center>
                </div>
                <?php
            }
            ?>

        </div>

        <br/>
    </div>
</div>

<br/><br/>
<?php include("components/scripts.php"); ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Retrieve read notifications from localStorage
        var readNotifications = JSON.parse(localStorage.getItem('read_notifications')) || {};

        // Add event listener to all "Mark as read" buttons
        var markAsReadButtons = document.querySelectorAll('.mark-as-read');
        markAsReadButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                // Find the parent card element of the clicked button
                var card = button.closest('.card');
                // Add the CSS class to change the style
                card.classList.add('notification-read');
                // Remove the button
                button.remove();
                // Store the reference ID in localStorage
                var referenceID = button.getAttribute('data-reference-id');
                readNotifications[referenceID] = true;
                localStorage.setItem('read_notifications', JSON.stringify(readNotifications));
                // Update the session variable
                <?php $_SESSION['read_notifications'] = '<script>document.write(JSON.stringify(readNotifications))</script>'; ?>;
            });
        });

        // Mark notifications as read based on localStorage
        Object.keys(readNotifications).forEach(function(referenceID) {
            var card = document.querySelector('.card[data-reference-id="' + referenceID + '"]');
            if (card) {
                card.classList.add('notification-read');
                var button = card.querySelector('.mark-as-read');
                if (button) {
                    button.remove();
                }
            }
        });
    });
</script>

</body>
</html>
