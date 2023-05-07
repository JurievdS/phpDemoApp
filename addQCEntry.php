<?php
session_start();

// Redirect based on user permission level
if ($_SESSION['permission'] == 'admin') {
    header("Location: index.php");
}

require_once('./controllers/qualityController.php');
$qualityControl = new qualityControl();

if (isset($_POST['submit'])&& $_SESSION['permission'] !== 'admin') {
    // Clean the data
    $qc_name = htmlspecialchars(trim($_POST['name']));
    $qc_description = htmlspecialchars(trim($_POST['description']));
    $qc_date = htmlspecialchars(trim($_POST['date']));
    $qc_outcome = htmlspecialchars(trim($_POST['outcome']));

    // Check for errors
    $error_message = "";
    if (empty($qc_name)) {
        $error_message .= "Name is required. ";
    }
    if (empty($qc_date)) {
        $error_message .= "Date is required. ";
    }
    if (empty($qc_outcome)) {
        $error_message .= "Outcome is required. ";
    }

    if (empty($error_message)) {
        // Pass the cleaned data to the addQCEntry function
        $qualityControl->addQCEntry($qc_name, $qc_description, $qc_date, $qc_outcome);
        header("Location: index.php");
    } else {
        
        $_SESSION['error_message'] = $error_message;
        header("Location: addQCEntry.php");
    }
}
?>

<!DOCTYPE html>
<html>

    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous" />
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-PO5nR+oVdD5lr5pNR53HJUCt/kp7V+ZrCJ98DkdLjIcP+i2oylE9JTpjFCVJzvIV"
            crossorigin="anonymous"></script>

    </head>
    <body>
        <?php include './templates/header.php'; ?>

        <div class="container">
            <h1>Add Quality Control Entry</h1>

            <?php if (isset($error_message)): ?>
                <p style="color: red;">
                    <?php echo $error_message; ?>
                </p>
            <?php endif; ?>
            <br />
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" style="max-width:50%">
                <div class="input-group mb-3">
                    <label for="name" class="input-group-text">Name:</label>
                    <input type="text" class="form-control" id="name" name="name" maxlength="50" required />
                    <br />
                </div>
                <div class="input-group mb-3">
                    <label for="description" class="input-group-text">Description:</label>
                    <textarea type="text-" class="form-control" id="description" name="description" maxlength="100"></textarea>
                    <br />
                </div>
                <div class="input-group mb-3">
                    <label for="date" class="input-group-text">Audit Date:</label>
                    <input type="date" class="form-control" id="date" name="date" required />
                    <br />
                </div>
                <div class="input-group mb-3">
                    <label for="outcome" class="input-group-text">Outcome:</label>
                    <select class="form-select" aria-label="Select" name="outcome" required>
                        <option disabled selected>Select outcome</option>
                        <option value="Pass">Pass</option>
                        <option value="Fail">Fail</option>
                    </select>
                    <br />
                </div>
                <input type="submit" name="submit" value="Submit" />
            </form>
        </div>
        </div>
    </body>
</html>