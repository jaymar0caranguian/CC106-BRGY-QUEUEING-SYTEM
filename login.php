<?php
session_start(); // Start the session

// Check if the user is already logged in
if (isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit();
}

include("connection.php");

// Define variables to store error messages
$registrationUsernameError = $emailError = $registrationPasswordError = $successMessage = "";

// Function to validate email format using regex
function isValidEmail($email) {
    // Define regex pattern for standard email format
    $pattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
    // Perform regex match
    return preg_match($pattern, $email);
}

// Function to validate password strength
function isValidPassword($password) {
    // Password must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one number, and one special character
    $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&]{8,}$/';
    return preg_match($pattern, $password);
}

// Registration Logic
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['new-username']) && isset($_POST['new-email']) && isset($_POST['new-password']) && isset($_POST['new-confirm-password'])) {
    // Check if all fields are filled
    if (!empty($_POST['new-username']) && !empty($_POST['new-email']) && !empty($_POST['new-password']) && !empty($_POST['new-confirm-password'])) {
        $username = $_POST['new-username'];
        $email = $_POST['new-email'];
        $password = $_POST['new-password'];
        $confirmPassword = $_POST['new-confirm-password'];
        
        // Check if passwords match
        if ($password === $confirmPassword) {
            // Check if email format is valid
            if (!isValidEmail($email)) {
                $emailError = "Invalid email format.";
            } else {
                // Check if username is already taken
                $checkUsernameQuery = "SELECT * FROM Users WHERE Username='$username'";
                $checkUsernameResult = mysqli_query($connection, $checkUsernameQuery);
                if (mysqli_num_rows($checkUsernameResult) > 0) {
                    $registrationUsernameError = "Username already taken.";
                }
                
                // Check if email is already registered
                $checkEmailQuery = "SELECT * FROM Users WHERE Email='$email'";
                $checkEmailResult = mysqli_query($connection, $checkEmailQuery);
                if (mysqli_num_rows($checkEmailResult) > 0) {
                    $emailError = "Email already registered.";
                }
            }

            // Check if password meets strength requirements
            if (!isValidPassword($password)) {
                $registrationPasswordError = "Password must be at least 8 characters long and contain at least 1 uppercase letter, 1 lowercase letter, 1 number, and 1 special character.";
            }

            if (empty($registrationUsernameError) && empty($emailError) && empty($registrationPasswordError)) {
                // Hash the password before storing it in the database
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                
                // Insert user into the database
                $query = "INSERT INTO Users (Username, Email, Password) VALUES ('$username', '$email', '$hashedPassword')";
                $result = mysqli_query($connection, $query);
                
                if ($result) {
                    // Success message
                    $successMessage = "Account created successfully!";
                } else {
                    // Error message
                    $registrationPasswordError = "Error creating account. Please try again later.";
                }
            }
        } else {
            // Passwords don't match
            $registrationPasswordError = "Passwords do not match.";
        }

        // Prepare response data
        $responseData = array(
            'success' => empty($registrationUsernameError) && empty($emailError) && empty($registrationPasswordError),
            'usernameError' => $registrationUsernameError,
            'emailError' => $emailError,
            'passwordError' => $registrationPasswordError,
            'confirmPasswordError' => ($password !== $confirmPassword) ? 'Passwords do not match.' : ''
        );

        // Output response as JSON
        header('Content-Type: application/json');
        echo json_encode($responseData);
        exit();
    } 
}
 

// Check if username and password are provided for login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username']) && isset($_POST['password'])) {
    // Check for other errors first
    if (empty($usernameError) && empty($emailError) && empty($passwordError)) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        // Check if user exists in the database
        $checkUserQuery = "SELECT * FROM Users WHERE Username='$username'";
        $checkUserResult = mysqli_query($connection, $checkUserQuery);
        
        if (mysqli_num_rows($checkUserResult) > 0) {
            // User exists, fetch user data
            $userData = mysqli_fetch_assoc($checkUserResult);
            
            // Verify password
            if (password_verify($password, $userData['Password'])) {
                // Set session variable indicating user is logged in
                $_SESSION['username'] = $username;
                
                // Redirect to dashboard.php
                header("Location: dashboard.php");
                exit();
            } else {
                // Password is incorrect
                $passwordError = "Incorrect password.";
            }
        } else {
            // User does not exist or incorrect credentials
            $usernameError = "Invalid username or user does not exist.";
        }
    }

}
?>

<!DOCTYPE html>
<head>
    <?php include("./components/headers.php"); ?>
    <title>Barangay System | Login</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body, html {
            margin: 0px;
            padding: 0px;
            width: 100%;
            height: 100%;
            overflow: hidden;
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

        h4, h5.modal-title {
            color: white;
            text-transform: uppercase;
        }

        .brgy-msg {
            font-size: 2em;
            color: #3d5184;
            font-weight: bold;
            text-shadow: 2px 2px white;
            letter-spacing: 16px;
            text-transform: uppercase;
        }

        .dvr {
            background-color: #3d5184;
        }

        label {
            text-transform: uppercase;
            padding-bottom: 0px;
        }

        input[type="text"] {
            margin-top: -12px;
        }
    </style>
</head>
<body>
    <div class="blur-bg"></div>

    <div class="row">
        <div class="col-lg-6">
            <center>
                <br/>
                <h4 class="brgy-msg">Barangay<br/>Nagkaisang Nayon</h4>
            </center>
        </div>

        <div class="col-lg-6 dvr" style="height: 100vh" align="center">
            <br/><br/><br/><br/>
            <h4>Sign in</h4>

            <br/>
            <br/>

            <div class="w-50" align="left">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <label for="username" class="text-white form-label">Username</label><br/>
                    <input type="text" id="username" name="username" class="form-text w-100" required />
                    <?php if (!empty($usernameError)) { ?>
                            <div class="text-danger"><?php echo $usernameError; ?></div>
                    <?php } ?>
                    <br/><br/>

                    <label for="password" class="text-white form-label">Password</label><br/>
                    <input type="password" id="password" name="password" class="form-text w-100" required />
                    <?php if (!empty($passwordError)) { ?>
                            <div class="text-danger"><?php echo $passwordError; ?></div>
                    <?php } ?>
                    <br/><br/>
                    <div align="center">
                        <input type="submit" value="Log-in" class="btn btn-light" />
                        <br/><br/>

                        <a href="forgot-password.php" class="text-white">Forgot Password</a><br/><br/>
                        <p class="text-white">No accounts yet? <a href="#" class="text-white" data-bs-toggle="modal" data-bs-target="#new-account">Click Here</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="new-account" tabindex="-1" aria-labelledby="new-accountLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content dvr">
            <div class="modal-header">
                <button type="button" class="btn-close btn-light bg-white" data-bs-dismiss="<?php echo (empty($registrationUsernameError) && empty($emailError) && empty($registrationPasswordError)) ? 'modal' : '' ?>" aria-label="Close"></button>
            </div>
            <div class="modal-body">
    <center>
        <h5 class="modal-title text-white" id="new-accountLabel">Create Account</h5>
    </center>

    <div class="card bg-white mt-4 p-4">
        <form id="create-account-form">
            <label for="new-username" class="form-label mb-3">Username</label>
            <input type="text" class="form-control" name="new-username" id="new-username" required />
            <div id="username-error" class="text-danger"></div>
            <br/>

            <label for="new-email" class="form-label mb-1">Email</label>
            <input type="email" class="form-control" name="new-email" id="new-email" required />
            <div id="email-error" class="text-danger"></div>
            <br/>

            <label for="new-password" class="form-label mb-1">Password</label>
            <input type="password" class="form-control" name="new-password" id="new-password" required />
            <div id="password-error" class="text-danger"></div>
            <br/>

            <label for="new-confirm-password" class="form-label mb-1">Confirm Password</label>
            <input type="password" class="form-control" name="new-confirm-password" id="new-confirm-password" required />
            <div id="confirm-password-error" class="text-danger"></div>
            <br/>

            <div id="success-message" class="alert alert-success" role="alert" style="display: none;"></div>

            <button type="submit" class="btn btn-primary">Create</button>
        </form>
    </div>
</div>
</div>

<script>
    // JavaScript function to handle form submission using AJAX
    document.getElementById("create-account-form").addEventListener("submit", function(event) {
        event.preventDefault(); // Prevent default form submission
        
        // Prepare form data
        var formData = new FormData(this);
        
        // Send AJAX request
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>", true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                // Response received successfully
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    // Display success message
                    document.getElementById("success-message").innerText = "Account created successfully!";
                    document.getElementById("success-message").style.display = "block";

                    // Close the modal
                    var modal = document.getElementById("new-account");
                    var modalInstance = bootstrap.Modal.getInstance(modal);
                    modalInstance.hide();

                    // Show alert
                    alert("Account created successfully!");
                } else {
                    // Display error messages
                    document.getElementById("username-error").innerText = response.usernameError;
                    document.getElementById("email-error").innerText = response.emailError;
                    document.getElementById("password-error").innerText = response.passwordError;
                    document.getElementById("confirm-password-error").innerText = response.confirmPasswordError;
                }
            }
        };
        xhr.send(formData);
    });
</script>

<!--
<script>
    // JavaScript function to handle form submission using AJAX
    document.getElementById("create-account-form").addEventListener("submit", function(event) {
        event.preventDefault(); // Prevent default form submission
        
        // Prepare form data
        var formData = new FormData(this);
        
        // Send AJAX request
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "Jaymar Caranguian", true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                // Response received successfully
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    // Display success message
                    document.getElementById("success-message").innerText = "Account created successfully!";
                    document.getElementById("success-message").style.display = "block";

                    // Close the modal
                    var modal = document.getElementById("new-account");
                    var modalInstance = bootstrap.Modal.getInstance(modal);
                    modalInstance.hide();
                } else {
                    // Display error messages
                    document.getElementById("username-error").innerText = response.usernameError;
                    document.getElementById("email-error").innerText = response.emailError;
                    document.getElementById("password-error").innerText = response.passwordError;
                    document.getElementById("confirm-password-error").innerText = response.confirmPasswordError;
                }
            }
        };
        xhr.send(formData);
    });
</script>
-->

</div>
</div>


    <?php include("components/scripts.php"); ?>
</body>
</html>
