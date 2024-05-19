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
    $birthplace = $userData['Birthplace'] ?? '';
    $gender = $userData['Gender'] ?? '';
    $civilStatus = $userData['CivilStatus'] ?? '';
    $phoneNumber = $userData['PhoneNumber'] ?? '';
    $educationalAttainment = $userData['EducationalAttainment'] ?? '';
    $course = $userData['Course'] ?? '';
    $citizenship = $userData['Citizenship'] ?? '';
    $occupation = $userData['Occupation'] ?? '';
    $guardianFirstName = $userData['GuardianFirstName'] ?? '';
    $guardianMiddleName = $userData['GuardianMiddleName'] ?? '';
    $guardianLastName = $userData['GuardianLastName'] ?? '';
    $guardianSuffix = $userData['GuardianSuffix'] ?? '';
    $guardianContactNumber = $userData['GuardianContactNumber'] ?? '';
    $guardianRelationship = $userData['GuardianRelationship'] ?? '';
    

    // Calculate age based on birthdate
    $birthDate = new DateTime($birthdate);
    $currentDate = new DateTime();
    $age = $currentDate->diff($birthDate)->y; // Calculating the difference in years
}
?>

<!DOCTYPE html>
<head>
    <?php include("./components/headers.php"); ?>
    <title>Barangay System | Register</title>

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

        .bg-prim {
            background-color: #3d5184;
        }
    </style>
</head>
<body>
    <div class="blur-bg"></div>
    <br/><br/>

    <div class="p-4 main-content">
        <div class="bg-prim">
            <h1 class="p-2 text-white" align="center">About Me</h1>
        </div>
        <div class="bg-white" style="margin-top: -8px">
            <br/>

            <div class="row px-4">
                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="first-name" class="form-label">First Name</label>
                            <input type="text" class="form-control" name="first-name" id="first-name" disabled required readonly value="<?php echo $firstName; ?>" />
                        </div>

                        <div class="col-md-6">
                            <label for="middle-name" class="form-label">Middle Name</label>
                            <input type="text" class="form-control" name="middle-name" id="middle-name" disabled readonly value="<?php echo $middleName; ?>" />
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="surname" class="form-label">Surname</label>
                            <input type="text" class="form-control" name="surname" id="surname" disabled required readonly value="<?php echo $surname; ?>" />
                        </div>

                        <div class="col-md-6">
                            <label for="suffix" class="form-label">Suffix</label>
                            <input type="text" class="form-control" name="suffix" id="suffix" disabled readonly value="<?php echo $suffix; ?>" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="row px-4 pt-4">
                <div class="col-lg-6">
                    <label for="home-address" class="form-label">Home Address</label>
                    <input type="text" class="form-control" id="home-address" name="home-address" disabled required value="<?php echo $homeAddress; ?>" />
                </div>

                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-6">
                            <label for="barangay" class="form-label">Barangay</label>
                            <input type="text" class="form-control" id="home-address" name="home-address" disabled required value="<?php echo $barangay; ?>" />
                        </div>

                        <div class="col-6">
                            <label for="city" class="form-label">City</label>
                            <input type="text" class="form-control" id="home-address" name="home-address" disabled required value="<?php echo $city; ?>" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="row px-4 pt-4">
                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-6">
                            <label for="age" class="form-label">Age</label>
                            <input type="number" class="form-control" name="age" id="age" disabled required readonly value="<?php echo $age; ?>" />
                        </div>

                        <div class="col-6">
                            <label for="gender" class="form-label">Gender</label>
                            <input type="text" class="form-control" id="gender" name="gender" disabled required readonly value="<?php echo $gender; ?>" />
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-6">
                            <label for="citizenship" class="form-label">Citizenship</label>
                            <input type="text" class="form-control" id="citizenship" name="citizenship" disabled required readonly value="<?php echo $citizenship; ?>" />
                        </div>

                        <div class="col-6">
                            <label for="occupation" class="form-label">Occupation</label>
                            <input type="text" class="form-control" id="occupation" name="occupation" disabled required readonly value="<?php echo $occupation; ?>" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="row pt-4 px-4">
                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-6">
                            <label for="birthdate" class="form-label">Birth Date</label>
                            <input type="date" class="form-control" id="birthdate" name="birthdate" disabled required readonly value="<?php echo $birthdate; ?>" />
                        </div>

                        <div class="col-6">
                            <label for="birthplace" class="form-label">Birthplace</label>
                            <input type="text" class="form-control" id="birthplace" name="birthplace" disabled required readonly value="<?php echo $birthplace; ?>" />
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-6">
                            <label for="contact" class="form-label">Contact</label>
                            <input type="text" class="form-control" id="contact" name="contact" disabled required readonly value="<?php echo $phoneNumber; ?>" />
                        </div>

                        <div class="col-6">
                            <label for="civil-status" class="form-label">Civil Status</label>
                            <input type="text" class="form-control" id="civil-status" name="civil-status" disabled required readonly value="<?php echo $civilStatus; ?>" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="row pt-4 px-4">
                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-6">
                            <label for="educational-attainment" class="form-label">Educational Attainment</label>
                            <input type="text" class="form-control" id="educational-attainment" name="educational-attainment" disabled required readonly value="<?php echo $educationalAttainment; ?>" />
                        </div>

                        <div class="col-6">
                            <label for="course" class="form-label">Course</label>
                            <input type="text" class="form-control" id="course" name="course" disabled readonly value="<?php echo $course; ?>" />
                        </div>
                    </div>
                </div>
            </div>

            <br/><br/><br/>
            <h3 align="center" style="text-transform: uppercase">Incase of Emergency</h3>
            <br/><br/>

            <div class="row px-4 pt-4">
                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-6">
                            <label for="guardian-first-name" class="form-label">Guardian's First Name</label>
                            <input type="text" class="form-control" id="ioe-first-name" name="ioe-first-name" disabled required readonly value="<?php echo $guardianFirstName; ?>" />
                        </div>

                        <div class="col-6">
                            <label for="guardian-middle-name" class="form-label">Guardian's Middle Name</label>
                            <input type="text" class="form-control" id="ioe-middle-name" name="ioe-middle-name" disabled readonly value="<?php echo $guardianMiddleName; ?>" />
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-6">
                            <label for="guardian-surname" class="form-label">Guardian's Surname</label>
                            <input type="text" class="form-control" id="ioe-surname" name="ioe-surname" disabled required readonly value="<?php echo $guardianLastName; ?>" />
                        </div>

                        <div class="col-6">
                            <label for="guardian-suffix" class="form-label">Guardian's Suffix</label>
                            <input type="text" class="form-control" id="ioe-suffix" name="ioe-suffix" disabled readonly value="<?php echo $guardianSuffix; ?>" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="row pt-4 px-4">
                <div class="col-3">
                    <label for="guardian-relationship" class="form-label">Relationship</label>
                    <input type="text" class="form-control" name="guardian-relationship" id="guardian-relationship" disabled required readonly value="<?php echo $guardianRelationship; ?>" />
                </div>

                <div class="col-3">
                <label for="guardian-contact" class="form-label">Contact</label>
                <input type="text" class="form-control" id="contact" name="contact" disabled required readonly value="<?php echo $guardianContactNumber; ?>" />
                </div>
            </div>

            <br/><br/>

            <div align="center">
                <a href="reset-password.php" class="btn btn-primary">Change Password</a>
                <a href="dashboard.php" class="btn btn-light">Go back to dashboard</a>
            </div>

            <br/><br/><br/>
        </div>
    </div>
    <br/><br/>

    <?php include("./components/scripts.php"); ?>
    <script>window.onload = ()=> $("#notif").modal("show");</script>
</body>
</html>

