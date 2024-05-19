<!DOCTYPE html>
<head>
    <?php include("./components/headers.php"); ?>
    <title>Barangay System</title>

    <style>
        * {
            text-transform: uppercase;
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

        .welc-msg {
            color: white;
            text-shadow: 2px 2px black;
            letter-spacing: 5px;
            font-weight: bold;
        }

        .brgy-msg {
            font-size: 4em;
            color: #3d5184;
            font-weight: bold;
            text-shadow: 2px 2px white;
            letter-spacing: 16px;
        }

        /* Media Query for Mobile */
        @media (max-width: 768px) {
            .welc-msg {
                font-size: 1.5rem;
                letter-spacing: 1px; /* Adjusted for readability */
            }
            .brgy-msg {
                font-size: 2.5rem;
                letter-spacing: 4px; /* Adjusted for readability */
                margin-bottom: 10px;
            }
            .location-msg {
                font-size: 1rem;
            }
            .btn-primary {
                padding: 12px 24px;
            }
            .welcome-container {
                padding-top: 10vh; /* Adjusted for centering */
            }
            .blur-bg {
                filter: blur(4px); /* Reduced blur effect for better readability */
            }
        }
    </style>
</head>
<body>
    <div class="blur-bg"></div>

    <br/><br/><br/><br/><br/>
    <center>
        <h2 class="welc-msg">Welcome to</h2>
        <h1 class="brgy-msg">Barangay<br/>Nagkaisang Nayon</h1>
        <h4 class="text-white mt-3">Area IX District V, Quezon City</h4>

        <br/><br/><br/><br/><br/><br/>
        <a class="btn btn-primary" href="login.php">Log-in</a>
    </center>
</body>
</html>