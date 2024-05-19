<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include('connection.php');

// Fetch logged-in user's information from the database
$username = $_SESSION['username'];
$user_query = "SELECT * FROM Users WHERE Username = '$username'";
$user_result = $connection->query($user_query);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $firstName = $_POST['first-name'];
    $middleName = $_POST['middle-name'];
    $lastName = $_POST['surname'];
    $suffix = $_POST['suffix'];
    $homeAddress = $_POST['home-address'];
    $barangay = $_POST['barangay'];
    $city = $_POST['city'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $citizenship = $_POST['citizenship'];
    $occupation = $_POST['occupation'];
    $birthdate = $_POST['birthdate'];
    $birthplace = $_POST['birthplace'];
    $contact = $_POST['contact'];
    $civilStatus = $_POST['civil-status'];
    $educationalAttainment = $_POST['educational-attainment'];
    $course = $_POST['course'];
    $guardianFirstName = $_POST['guardian-first-name'];
    $guardianMiddleName = $_POST['guardian-middle-name'];
    $guardianLastName = $_POST['guardian-surname'];
    $guardianSuffix = $_POST['guardian-suffix'];
    $guardianRelationship = $_POST['guardian-relationship'];
    $guardianContact = $_POST['guardian-contact'];

    // Check if the user already exists in the database based on specified parameters
    $check_query = "SELECT * FROM Users WHERE FirstName = '$firstName' AND MiddleName = '$middleName' AND LastName = '$lastName' AND Suffix = '$suffix' AND Gender = '$gender' AND Birthdate = '$birthdate'";
    $check_result = $connection->query($check_query);

    if ($check_result->num_rows > 0) {
        // User already exists, display an alert message
        echo "<script>alert('Duplicate records found. Please contact or visit the barangay hall to address this issue.');</script>";
    } else {
        // Proceed with insertion or update

        if ($user_result->num_rows > 0) {
            // Update the existing user's information
            $sql = "UPDATE Users SET FirstName = '$firstName', MiddleName = '$middleName', LastName = '$lastName', Suffix = '$suffix', HouseNo = '$homeAddress', Barangay = '$barangay', City = '$city', Gender = '$gender', Citizenship = '$citizenship', Occupation = '$occupation', Birthdate = '$birthdate', Birthplace = '$birthplace', PhoneNumber = '$contact', CivilStatus = '$civilStatus', EducationalAttainment = '$educationalAttainment', Course = '$course', GuardianFirstName = '$guardianFirstName', GuardianMiddleName = '$guardianMiddleName', GuardianLastName = '$guardianLastName', GuardianSuffix = '$guardianSuffix', GuardianRelationship = '$guardianRelationship', GuardianContactNumber = '$guardianContact' WHERE Username = '$username'";
        } else {
            // Insert into database if the user does not exist
            $sql = "INSERT INTO Users (Username, FirstName, MiddleName, LastName, Suffix, HouseNo, Barangay, City, Gender, Citizenship, Occupation, Birthdate, Birthplace, PhoneNumber, CivilStatus, EducationalAttainment, Course, GuardianFirstName, GuardianMiddleName, GuardianLastName, GuardianSuffix, GuardianRelationship, GuardianContactNumber) 
                    VALUES ('$username', '$firstName', '$middleName', '$lastName', '$suffix', '$homeAddress', '$barangay', '$city', '$gender', '$citizenship', '$occupation', '$birthdate', '$birthplace', '$contact', '$civilStatus', '$educationalAttainment', '$course', '$guardianFirstName', '$guardianMiddleName', '$guardianLastName', '$guardianSuffix', '$guardianRelationship', '$guardianContact')";
        }

        // Execute the SQL query
        if ($connection->query($sql) === TRUE) {
            // Redirect to success page or display success message
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $connection->error;
        }
    }
}

// Fetch Barangay data
$barangay_query = "SHOW COLUMNS FROM Users WHERE Field = 'Barangay'";
$barangay_result = $connection->query($barangay_query);

// Fetch City data
$city_query = "SHOW COLUMNS FROM Users WHERE Field = 'City'";
$city_result = $connection->query($city_query);

// Fetch Gender data
$gender_query = "SHOW COLUMNS FROM Users WHERE Field = 'Gender'";
$gender_result = $connection->query($gender_query);

// Fetch Citizenship data
$citizenship_query = "SHOW COLUMNS FROM Users WHERE Field = 'Citizenship'";
$citizenship_result = $connection->query($citizenship_query);

// Fetch Civil Status data
$civil_status_query = "SHOW COLUMNS FROM Users WHERE Field = 'CivilStatus'";
$civil_status_result = $connection->query($civil_status_query);

// Fetch Educational Attainment data
$educational_attainment_query = "SHOW COLUMNS FROM Users WHERE Field = 'EducationalAttainment'";
$educational_attainment_result = $connection->query($educational_attainment_query);

// Fetch Relationship data
$relationship_query = "SHOW COLUMNS FROM Users WHERE Field = 'GuardianRelationship'";
$relationship_result = $connection->query($relationship_query);

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
            <h1 class="p-2 text-white" align="center">Fill Out This Form</h1>
        </div>
        <div class="bg-white" style="margin-top: -8px">
            <br/>
            <form method="post" action="aboutme.php">
            <div class="row px-4">
                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="first-name" class="form-label">First Name</label>
                            <input type="text" class="form-control" name="first-name" id="first-name" required />
                            <div id="first-name-error" style="color: red;"></div>
                        </div>

                        <div class="col-md-6">
                            <label for="middle-name" class="form-label">Middle Name</label>
                            <input type="text" class="form-control" name="middle-name" id="middle-name" />
                            <div id="middle-name-error" style="color: red;"></div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="surname" class="form-label">Surname</label>
                            <input type="text" class="form-control" name="surname" id="surname" required />
                            <div id="surname-error" style="color: red;"></div>
                        </div>

                        <div class="col-md-6">
                            <label for="suffix" class="form-label">Suffix</label>
                            <input type="text" class="form-control" name="suffix" id="suffix" />
                            <div id="suffix-error" style="color: red;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row px-4 pt-4">
                <div class="col-lg-6">
                    <label for="home-address" class="form-label">Home Address</label>
                    <input type="text" class="form-control" name="home-address" id="home-address" required />
                </div>

                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-6">
                            <label for="barangay" class="form-label">Barangay</label>
                            <select name="barangay" class="form-control" required>
                                <option selected disabled>Select Barangay</option>
                                <?php
                                if ($barangay_result->num_rows > 0) {
                                    $row = $barangay_result->fetch_assoc();
                                    $enum_str = $row['Type'];
                                    preg_match("/^enum\(\'(.*)\'\)$/", $enum_str, $matches);

                                    if (isset($matches[1])) {
                                        $enum_values = explode("','", $matches[1]);
                                        foreach ($enum_values as $value) {
                                            echo "<option value='$value'>$value</option>";
                                        }
                                    } else {
                                        echo "<option value=''>No options found</option>";
                                    }
                                } else {
                                    echo "<option value=''>No data found</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-6">
                            <label for="city" class="form-label">City</label>
                            <select name="city" class="form-control" required>
                                <option selected disabled>Select City</option>
                                <?php
                                if ($city_result->num_rows > 0) {
                                    $row = $city_result->fetch_assoc();
                                    $enum_str = $row['Type'];
                                    preg_match("/^enum\(\'(.*)\'\)$/", $enum_str, $matches);

                                    if (isset($matches[1])) {
                                        $enum_values = explode("','", $matches[1]);
                                        foreach ($enum_values as $value) {
                                            echo "<option value='$value'>$value</option>";
                                        }
                                    } else {
                                        echo "<option value=''>No options found</option>";
                                    }
                                } else {
                                    echo "<option value=''>No data found</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row px-4 pt-4">
                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-6">
                            <label for="age" class="form-label">Age</label>
                            <input type="number" class="form-control" name="age" id="age" readonly />
                        </div>

                        <script>
                            function calculateAge() {
                                var birthdate = document.getElementById('birthdate').value;
                                var today = new Date();
                                var birthDate = new Date(birthdate);
                                var age = today.getFullYear() - birthDate.getFullYear();
                                var m = today.getMonth() - birthDate.getMonth();
                                if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                                    age--;
                                }
                                document.getElementById('age').value = age;
                            }
                        </script>

                        <div class="col-6">
                            <label for="gender" class="form-label">Gender</label>
                            <select name="gender" class="form-control" required>
                                <option selected disabled>Select Gender</option>
                                <?php
                                if ($gender_result->num_rows > 0) {
                                    $row = $gender_result->fetch_assoc();
                                    $enum_str = $row['Type'];
                                    preg_match("/^enum\(\'(.*)\'\)$/", $enum_str, $matches);

                                    if (isset($matches[1])) {
                                        $enum_values = explode("','", $matches[1]);
                                        foreach ($enum_values as $value) {
                                            echo "<option value='$value'>$value</option>";
                                        }
                                    } else {
                                        echo "<option value=''>No options found</option>";
                                    }
                                } else {
                                    echo "<option value=''>No data found</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-6">
                            <label for="citizenship" class="form-label">Citizenship</label>
                            <select name="citizenship" class="form-control" required>
                                <option selected disabled>Select Citizenship</option>
                                <?php
                                if ($citizenship_result->num_rows > 0) {
                                    $row = $citizenship_result->fetch_assoc();
                                    $enum_str = $row['Type'];
                                    preg_match("/^enum\(\'(.*)\'\)$/", $enum_str, $matches);

                                    if (isset($matches[1])) {
                                        $enum_values = explode("','", $matches[1]);
                                        foreach ($enum_values as $value) {
                                            echo "<option value='$value'>$value</option>";
                                        }
                                    } else {
                                        echo "<option value=''>No options found</option>";
                                    }
                                } else {
                                    echo "<option value=''>No data found</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-6">
                            <label for="occupation" class="form-label">Occupation</label>
                            <input type="text" class="form-control" name="occupation" id="occupation" />
                            <div id="occupation-error" style="color: red;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row pt-4 px-4">
                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-6">
                            <label for="birthdate" class="form-label">Birth Date</label>
                            <input type="date" class="form-control" name="birthdate" id="birthdate" required onchange="calculateAge()" />
                            <div id="birthdate-error" style="color: red;"></div>
                        </div>

                        <div class="col-6">
                            <label for="birthplace" class="form-label">Birthplace</label>
                            <input type="text" class="form-control" name="birthplace" id="birthplace" required />
                            <div id="birthplace-error" style="color: red;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-6">
                            <label for="contact" class="form-label">Contact</label>
                            <input type="text" class="form-control" name="contact" id="contact" required />
                            <div id="contact-error" style="color: red;"></div>
                        </div>

                        <div class="col-6">
                            <label for="civil-status" class="form-label">Civil Status</label>
                            <select name="civil-status" class="form-control" required>
                                <option selected disabled>Select Civil Status</option>
                                <?php
                                if ($civil_status_result->num_rows > 0) {
                                    $row = $civil_status_result->fetch_assoc();
                                    $enum_str = $row['Type'];
                                    preg_match("/^enum\(\'(.*)\'\)$/", $enum_str, $matches);

                                    if (isset($matches[1])) {
                                        $enum_values = explode("','", $matches[1]);
                                        foreach ($enum_values as $value) {
                                            echo "<option value='$value'>$value</option>";
                                        }
                                    } else {
                                        echo "<option value=''>No options found</option>";
                                    }
                                } else {
                                    echo "<option value=''>No data found</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row pt-4 px-4">
                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-6">
                            <label for="educational-attainment" class="form-label">Educational Attainment</label>
                            <select name="educational-attainment" class="form-control" required>
                                <option selected disabled>Select Educational Attainment</option>
                                <?php
                                if ($educational_attainment_result->num_rows > 0) {
                                    $row = $educational_attainment_result->fetch_assoc();
                                    $enum_str = $row['Type'];
                                    preg_match("/^enum\(\'(.*)\'\)$/", $enum_str, $matches);

                                    if (isset($matches[1])) {
                                        $enum_values = explode("','", $matches[1]);
                                        foreach ($enum_values as $value) {
                                            echo "<option value='$value'>$value</option>";
                                        }
                                    } else {
                                        echo "<option value=''>No options found</option>";
                                    }
                                } else {
                                    echo "<option value=''>No data found</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-6">
                            <label for="course" class="form-label">Course</label>
                            <input type="text" class="form-control" name="course" id="course" />
                            <div id="course-error" style="color: red;"></div>
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
                            <input type="text" class="form-control" name="guardian-first-name" id="guardian-first-name" />
                            <div id="guardian-first-name-error" style="color: red;"></div>
                        </div>

                        <div class="col-6">
                            <label for="guardian-middle-name" class="form-label">Guardian's Middle Name</label>
                            <input type="text" class="form-control" name="guardian-middle-name" id="guardian-middle-name" />
                            <div id="guardian-middle-name-error" style="color: red;"></div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-6">
                            <label for="guardian-surname" class="form-label">Guardian's Surname</label>
                            <input type="text" class="form-control" name="guardian-surname" id="guardian-surname" required />
                            <div id="guardian-surname-error" style="color: red;"></div>
                        </div>

                        <div class="col-6">
                            <label for="guardian-suffix" class="form-label">Guardian's Suffix</label>
                            <input type="text" class="form-control" name="guardian-suffix" id="guardian-suffix" />
                            <div id="guardian-suffix-error" style="color: red;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row pt-4 px-4">
                <div class="col-3">
                    <label for="guardian-relationship" class="form-label">Relationship</label>
                    <select name="guardian-relationship" class="form-control" required>
                        <option selected disabled>Select Relationship</option>
                        <?php
                        if ($relationship_result->num_rows > 0) {
                            $row = $relationship_result->fetch_assoc();
                            $enum_str = $row['Type'];
                            preg_match("/^enum\(\'(.*)\'\)$/", $enum_str, $matches);

                            if (isset($matches[1])) {
                                $enum_values = explode("','", $matches[1]);
                                foreach ($enum_values as $value) {
                                    echo "<option value='$value'>$value</option>";
                                }
                            } else {
                                echo "<option value=''>No options found</option>";
                            }
                        } else {
                            echo "<option value=''>No data found</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="col-3">
                <label for="guardian-contact" class="form-label">Contact</label>
                    <input type="text" class="form-control" name="guardian-contact" id="guardian-contact" required />
                    <div id="guardian-contact-error" style="color: red;"></div>
                </div>
            </div>
        
            <br/><br/>
            <p align="center"><span class="text-danger fw-bold">NOTE</span>: Thank you for completing the required fields. Please review your entries one last time before proceeding.</p>

            <div align="center">
                <button type="submit" class="btn btn-primary">Submit</button>
                <a href="logout.php" class="btn btn-light">Cancel</a>
            </div>
            </form>

            <br/><br/><br/>
        </div>
    </div>
    <br/><br/>


    <div class="modal fade" id="success" tabindex="-1" aria-labelledby="successLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content dvr">
                <div class="modal-body">
                    <div class="d-block" align="right">
                        <button type="button" class="btn-close btn-light bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div align="center">
                        <br/><br/>
                        <b style="text-transform: uppercase">Your submission has been successfully processed!</b>
                        <br/><br/>
                    </div>
                </div>
                <div class="modal-footer dvr">
                    <a href="" class="btn btn-primary">Ok</a>
                </div>
            </div>
        </div>
    </div>

    <script src="./scripts/aboutme-age.js"></script>
    <script src="./scripts/aboutme-letters.js"></script>
    <script src="./scripts/aboutme-numbers.js"></script>         
    <?php include("./components/scripts.php"); ?>
    <script>window.onload = ()=> $("#notif").modal("show");</script>
</body>
</html>