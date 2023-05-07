<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

// If the user is already logged in, redirect them to the homepage
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// If the user submitted the login form
if (isset($_POST['submit'])) {
    include_once('util/database.php');
    $db = new Database();
    $conn = $db->getConnection();

    // Check for errors
    if ($conn->connect_errno) {
        echo "Failed to connect to MySQL: " . $conn->connect_error;
        exit();
    }

    // Escape special characters to prevent SQL injection
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);

    // Query the database for the user with the given email and password
    $query = "SELECT id, email, password, permission FROM users WHERE email='$email'";
    $result = $conn->query($query);

    // If the user was found, verify their password
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Save the user ID and user level in the session
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['permission'] = $row['permission'];
            
            // Redirect the user to the homepage
            header("Location: index.php");
            exit();
        } else {
            $error_message = "Incorrect email or password.";
        }
    } else {
        $error_message = "Incorrect email or password.";
    }

    // Close the MySQL connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous" />
</head>
<body>
    <?php include './templates/header.php'; ?>
    <div class="mb-3">
        <div class="container">
            <h1>Login</h1>

            <?php if (isset($error_message)): ?>
            <p style="color: red;">
                <?php echo $error_message; ?>
            </p>
            <?php endif; ?>

            <br />
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" style="max-width:50%">
                <div class="input-group mb-3">
                    <label for="email" class="input-group-text">Email:</label>                
                    <input type="email" class="form-control" id="email" name="email" maxlength="50" required /><br />
                </div>
                <div class="input-group mb-3">
                    <label for="password" class="input-group-text">Password:</label>
                    <input type="password" class="form-control" id="password" name="password" maxlength="20" required /><br />
                </div>
                <input type="submit" name="submit" value="Log in" />

            </form>
            <br/>
            <p>
                Don't have an account? <a href="register.php">Register here</a>.
            </p>
        </div>
    </div>

   
</body>
</html>
