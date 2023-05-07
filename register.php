<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

include_once('./controllers/userController.php');
$registerUser = new UserController();

// If the user is already logged in, redirect them to the homepage
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// If the user submitted the registration form
if (isset($_POST['submit'])) {
    // Call the registerUser method to handle the registration process
    $result = $registerUser->registerUser($_POST['email'], $_POST['password']);

    // If the registration was successful, redirect the user to the login page
    if ($result === '') {
        header("Location: login.php");
        exit();
    } else {
        // If there was an error, display the error message
        echo $result;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous" />
</head>
<body>
    <?php include './templates/header.php'; ?>
    <div class="mb-3">
        <div class="container">
            <h1>Register</h1>

            <?php if (isset($error_message)): ?>
            <p style="color: red;">
                <?php echo $error_message; ?>
            </p>
            <?php endif; ?>

            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" style="max-width:50%">
                <div class="input-group mb-3">
                    <label for="email" class="input-group-text">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" required /><br />
                </div>
                <div class="input-group mb-3">
                    <label for="password" class="input-group-text">Password:</label>
                    <input type="password" class="form-control" id="password" name="password" required /><br />
                </div>
                <input type="submit" name="submit" value="Register" />
            </form>
            <br />
            <p>
                Have an account? <a href="login.php">Login here</a>.
            </p>
        </div>
    </div>
</body>
</html>