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

    // Handle delete request
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
        $referenceID = $_POST['request_id'];
        // Perform delete operation
        $deleteQuery = "DELETE FROM DocumentRequest WHERE ReferenceID = $referenceID AND ResidentID = $residentID";
        if ($connection->query($deleteQuery) === TRUE) {
            header("Location: my-requests.php");
            exit();
        } else {
            // Handle delete error
            echo "Error: " . $connection->error;
        }
    }

    // Query to fetch all requests made by the user
    $requestQuery = "SELECT * FROM DocumentRequest WHERE ResidentID = $residentID";
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

        .request-box {
            background-color: #eeeeee;
            margin-bottom: 20px;
            padding: 20px;
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
            <h4 class="text-dark text-uppercase fw-bold">My Requests</h4>

            <div class="card" style="background-color: #eeeeee">
                <br/>
                <div class="container mt-5">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <!-- Loop through each request and display -->
                            <?php if ($requestResult && $requestResult->num_rows > 0): ?>
                                <?php while ($row = $requestResult->fetch_assoc()): ?>
                                    <div class="request-box">
                                        <h5 class="text-uppercase"><?php echo $row['DocumentType']; ?></h5>
                                        <hr>
                                        <div class="row">
                                            <div class="col-6">
                                                <p>Reference Number:</p>
                                                <p>Name:</p>
                                                <p>Date:</p>
                                                <p>Status:</p>
                                                <p>Reason:</p>
                                            </div>
                                            <div class="col-6">
                                                <p><?php echo $row['ReferenceID']; ?></p>
                                                <p><?php echo $userData['FirstName'] . ' ' . $userData['LastName']; ?></p>
                                                <p><?php echo date('m-d-Y', strtotime($row['RequestDate'])); ?></p>
                                                <p><?php echo isset($row['Status']) ? $row['Status'] : 'Pending'; ?></p>
                                                <p><?php echo $row['Reason']; ?></p>
                                            </div>
                                        </div>
                                        <div class="text-right pt-3">
                                            <?php if ($row['Status'] !== 'Approved' && $row['Status'] !== 'Claimed' && $row['Status'] !== 'Rejected'): ?>
                                            <button type="button" class="btn btn-danger" onclick="confirmDelete(<?php echo $row['ReferenceID']; ?>)">Delete Request</button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <p class="text-center">No requests found.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <br/>
            </div>
            <br/>
        </div>
    </div>

    <br/><br/>
    <?php include("components/scripts.php"); ?>
    
    <script>
        function confirmDelete(referenceID) {
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this request!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    // If user confirms, submit the form for deletion
                    var form = document.createElement('form');
                    form.method = 'post';
                    form.action = '<?php echo $_SERVER['PHP_SELF']; ?>';
                    var input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'delete';
                    input.value = true;
                    form.appendChild(input);
                    var inputRefID = document.createElement('input');
                    inputRefID.type = 'hidden';
                    inputRefID.name = 'request_id';
                    inputRefID.value = referenceID;
                    form.appendChild(inputRefID);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
    <script src="./scripts/sweetalert.min.js"></script>
</body>
</html>
