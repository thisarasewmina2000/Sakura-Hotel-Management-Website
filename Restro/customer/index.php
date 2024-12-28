

<?php
session_start(); // Start session at the beginning
include('config/config.php');

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (isset($_POST['login'])) {
    $customer_email = $_POST['customer_email'];
    $customer_password = sha1(md5($_POST['customer_password'])); // Double encrypt to increase security

    // Check the values
    echo "Email: $customer_email<br>";
    echo "Password: $customer_password<br>";

    // Prepare the SQL statement
    if ($stmt = $mysqli->prepare("SELECT customer_email, customer_password, customer_id FROM rpos_customers WHERE customer_email = ? AND customer_password = ?")) {
        $stmt->bind_param('ss', $customer_email, $customer_password); // Bind parameters
        $stmt->execute(); // Execute the statement
        $stmt->bind_result($customer_email, $customer_password, $customer_id); // Bind the result

        if ($stmt->fetch()) {
            // Successful login
            $_SESSION['customer_id'] = $customer_id;
            echo "Login successful. Redirecting to dashboard...";
            header("location:dashboard.php");
            exit(); // Ensure no further code is executed
        } else {
            // Failed login
            $err = "Incorrect Authentication Credentials";
            echo $err;
        }

        $stmt->close(); // Close the statement
    } else {
        // SQL statement preparation failed
        $err = "Database error: Unable to prepare statement.";
        echo $err;
    }
}

    require_once('partials/_head.php');
?>

<body class="bg-dark">
    <div class="main-content">
        <div class="header bg-gradient-primar py-7">
            <div class="container">
                <div class="header-body text-center mb-7">
                    <div class="row justify-content-center">
                        <div class="col-lg-5 col-md-6">
                            <h1 class="text-white">Restaurant SAKURA</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Page content -->
        <div class="container mt--8 pb-5">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-7">
                    <div class="card bg-secondary shadow border-0">
                        <div class="card-body px-lg-5 py-lg-5">
                            <form method="post" role="form">
                                <div class="form-group mb-3">
                                    <div class="input-group input-group-alternative">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                                        </div>
                                        <input class="form-control" required name="customer_email" placeholder="Email" type="email">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group input-group-alternative">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                                        </div>
                                        <input class="form-control" required name="customer_password" placeholder="Password" type="password">
                                    </div>
                                </div>
                                <div class="custom-control custom-control-alternative custom-checkbox">
                                    <input class="custom-control-input" id=" customCheckLogin" type="checkbox">
                                    <label class="custom-control-label" for=" customCheckLogin">
                                        <span class="text-muted">Remember me</span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <div class="text-left">
                                        <button type="submit" name="login" class="btn btn-primary my-4">Log In</button>
                                        <a href="create_account.php" class=" btn btn-success pull-right">Create Account</a>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-6">
                            <!-- <a href="../admin/forgot_pwd.php" target="_blank" class="text-light"><small>Forgot password?</small></a> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer -->
    <?php
    require_once('partials/_footer.php');
    ?>
    <!-- Argon Scripts -->
    <?php
    require_once('partials/_scripts.php');
    ?>
</body>

</html>