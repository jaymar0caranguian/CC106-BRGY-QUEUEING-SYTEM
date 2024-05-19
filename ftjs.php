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
    // Set default values for user data
    $firstName = $userData['FirstName'] ?? '';
    $middleName = $userData['MiddleName'] ?? '';
    $surname = $userData['LastName'] ?? '';
    $suffix = $userData['Suffix'] ?? '';
    $homeAddress = $userData['HouseNo'] ?? '';
    $barangay = $userData['Barangay'] ?? '';
    $city = $userData['City'] ?? '';
    $birthdate = $userData['Birthdate'] ?? '';
    $gender = $userData['Gender'] ?? '';
    $civilStatus = $userData['CivilStatus'] ?? '';
    $phoneNumber = $userData['PhoneNumber'] ?? '';
    $educationalAttainment = $userData['EducationalAttainment'] ?? '';
    $course = $userData['Course'] ?? '';

    // Calculate age based on birthdate
    $birthDate = new DateTime($birthdate);
    $currentDate = new DateTime();
    $age = $currentDate->diff($birthDate)->y; // Calculating the difference in years

    // Process form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve form data
        $purpose = $_POST['purpose'] ?? '';
        $quantity = $_POST['quantity'] ?? 1; // Default quantity to 1 if not provided
        
        // Insert into DocumentRequest table
        $insertQuery = "INSERT INTO DocumentRequest (ResidentID, DocumentType, Purpose, Quantity) VALUES ('{$userData['ResidentID']}', 'First Time Job Seeker', '$purpose', $quantity)";
        if ($connection->query($insertQuery) === TRUE) {
            // Redirect to success page or show success message
            header("Location: success.php");
            exit();
        } else {
            // Handle insert error
            echo "Error: " . $connection->error;
        }
    }
} else {
    // Handle case when user data is not found
    echo "Error: User data not found.";
    // You may redirect the user or display an error message as needed
}
?>

<!DOCTYPE html>
<head>
    <?php include("components/headers.php"); ?>
    <title>Barangay System | First-time Job Seeker</title>

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
        <div class="bg-white p-4">
            <br/>
            <h4 class="text-dark text-uppercase fw-bold">First-time Job Seeker</h4>

            <div class="card px-4" style="background-color: #eeeeee">
                <br/>

                <div class="row">
                    <div class="col-6">
                        <div class="row">
                            <div class="col-6">
                                <label for="first-name" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="first-name" name="first-name" disabled required readonly value="<?php echo $firstName; ?>" />
                            </div>

                            <div class="col-6">
                                <label for="middle-name" class="form-label">Middle Name</label>
                                <input type="text" class="form-control" id="middle-name" name="middle-name" disabled readonly value="<?php echo $middleName; ?>" />
                            </div>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="row">
                            <div class="col-6">
                                <label for="surname" class="form-label">Surname</label>
                                <input type="text" class="form-control" id="surname" name="surname" disabled required readonly value="<?php echo $surname; ?>"  />
                            </div>

                            <div class="col-6">
                                <label for="suffix" class="form-label">Suffix</label>
                                <input type="text" class="form-control" id="suffix" name="suffix" disabled readonly value="<?php echo $suffix; ?>" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row pt-4">
                    <div class="col-6">
                        <label for="home-address" class="form-label">Home Address</label>
                        <input type="text" class="form-control" id="home-address" name="home-address" disabled required readonly value="<?php echo $homeAddress . ', ' . $barangay . ', ' . $city; ?>" />
                    </div>

                    <div class="col-3">
                        <label for="age" class="form-label">Age</label>
                        <input type="text" class="form-control" id="age" name="age" disabled required readonly value="<?php echo $age; ?>" />
                    </div>

                    <div class="col-3">
                        <label for="gender" class="form-label">Gender</label>
                        <input type="text" class="form-control" id="gender" name="gender" disabled required readonly value="<?php echo $gender; ?>" />
                    </div>
                </div>

                <div class="row pt-4">
                    <div class="col-3">
                        <label for="birthdate" class="form-label">Birthdate</label>
                        <input type="date" class="form-control" id="birthdate" name="birthdate" disabled required readonly value="<?php echo $birthdate; ?>" />
                    </div>

                    <div class="col-3">
                        <label for="contact" class="form-label">Contact</label>
                        <input type="text" class="form-control" id="contact" name="contact" disabled required readonly value="<?php echo $phoneNumber; ?>" />
                    </div>

                    <div class="col-3">
                        <label for="civil-status" class="form-label">Civil Status</label>
                        <input type="text" class="form-control" id="civil-status" name="civil-status" disabled required readonly value="<?php echo $civilStatus; ?>" />
                    </div>
                </div>

                <div class="row pt-4">
                    <div class="col-3">
                        <label for="educational-attainment" class="form-label">Educational Attainment</label>
                        <input type="text" class="form-control" id="educational-attainment" name="educational-attainment" disabled required readonly value="<?php echo $educationalAttainment; ?>" />
                    </div>

                    <div class="col-3">
                        <label for="course" class="form-label">Course</label>
                        <input type="text" class="form-control" id="course" name="course" disabled readonly value="<?php echo $course; ?>" />
                    </div>
                </div>

                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="row pt-4">
                    <div class="col-3">
                        <label for="purpose" class="form-label">Purpose</label>
                        <input type="text" class="form-control" id="purpose" name="purpose" required />
                    </div>

                    <div class="col-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" required />
                    </div>
                </div>

                <br/>
            </div>

            <br/><br/>
            <p align="center"><span class="text-danger text-uppercase fw-bold">NOTE</span>: Thank you for completing the required fields. Please review your entries one last time before proceeding.</p>
            <br/>

            <div align="center">
                <button type="submit" class="btn btn-primary">Submit</button>
                <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
            </div>
            </form>
            <br/><br/>
        </div>
    </div>

    <div class="modal fade" id="success" tabindex="-1" aria-labelledby="successLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content dvr">
                <div class="modal-body">
                    <div class="d-block" align="right">
                        <button type="button" class="btn-close btn-light bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div align="center">
                        <br/><br/>
                        <b style="text-transform: uppercase">Your request has been successfully processed!</b>
                        <br/><br/>
                    </div>
                </div>
                <div class="modal-footer dvr">
                    <a href="#" class="btn btn-primary">Ok</a>
                </div>
            </div>
        </div>
    </div>

    <br/><br/>
    <?php include("components/scripts.php"); ?>
</body>
</html>