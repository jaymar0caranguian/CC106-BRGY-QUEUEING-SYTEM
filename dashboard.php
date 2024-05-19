<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Include your connection file
include 'connection.php';

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
<head>
    <?php include("components/headers.php"); ?>
    <title>Barangay System | Dashboard</title>

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
    </style>
</head>
<body>
    <div class="blur-bg"></div>

    <div class="p-4 main-content">
        <h4 align="center" class="brgy-msg">Barangay Nagkaisang Nayon</h4>
        <br/>

        <?php include("components/navbar.php"); ?>
        <div class="bg-white">
            <br/><br/>
            <h3 class="text-dark text-uppercase fw-bold" align="center">Please choose from the following services</h3>
            <br/>

            <div class="row px-4">
                <div class="col-lg-4">
                    <a href="ftjs.php" class="btn btn-light w-100">
                        <i class="bi bi-suitcase-lg fw-bold" style="font-size: 6em"></i><br/>
                        First-time Job Seeker
                    </a>
                </div>

                <div class="col-lg-4">
                    <a href="barangay-clearance.php" class="btn btn-light w-100">
                        <i class="bi bi-file-text fw-bold" style="font-size: 6em"></i><br/>
                        Barangay Clearance
                    </a>
                </div>

                <div class="col-lg-4">
                    <a href="barangay-indigency.php" class="btn btn-light w-100">
                        <i class="bi bi-chat-text fw-bold" style="font-size: 6em"></i><br/>
                        Barangay Indigency
                    </a>
                </div>
            </div>
            <br/>

            <div class="row px-4">
                <div class="col-lg-4">
                    <a href="barangay-cedula.php" class="btn btn-light w-100">
                        <i class="bi bi-envelope-paper fw-bold" style="font-size: 6em"></i><br/>
                        Barangay Cedula
                    </a>
                </div>

                <div class="col-lg-4">
                    <a href="barangay-certificate.php" class="btn btn-light w-100">
                        <i class="bi bi-card-checklist fw-bold" style="font-size: 6em"></i><br/>
                        Barangay Certificate
                    </a>
                </div>

                <div class="col-lg-4">
                    <a href="barangay-id.php" class="btn btn-light w-100">
                        <i class="bi bi-person-vcard fw-bold" style="font-size: 6em"></i><br/>
                        Barangay ID
                    </a>
                </div>
            </div>

            <br/><br/><br/>
        </div>
    </div>

    <br/><br/>
    <?php include("components/scripts.php"); ?>
</body>
</html>