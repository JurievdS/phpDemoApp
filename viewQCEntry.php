<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Redirect to login page if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once './controllers/qualityController.php';
$qualityControl = new qualityControl();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $entry = $qualityControl->getQCEntry($id);
    if (!$entry) {
        die("Invalid ID"); // handle the case when the entry with the given ID doesn't exist
    }
}

if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $outcome = $_POST['outcome'];
    $qualityControl->editQCEntry($id, $name, $description, $date, $outcome);
    // set success message in session variable
    $_SESSION['message'] = "QC entry updated successfully!";
    // redirect to the same page to prevent form resubmission
    header("Location: viewQCEntry.php?id=$id");
    exit;
}

// check for success message in session variable

?>
<!DOCTYPE html>
<html>

<head>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous" />
    <title>View QC Entry</title>
</head>

<body>
    <?php include './templates/header.php'; ?>
    <div class="container">


        <h1>View QC Entry</h1>
        <div class="table" style="max-width:50%">
            <?php
            if (isset($_SESSION['message'])) {
                echo "<div class='alert alert-success' id='success-msg'>{$_SESSION['message']}</div>";

                // remove message from session variable so it doesn't show again on next page load
                unset($_SESSION['message']);
            }
            ?>
            <table class="table">
                <tr>
                    <th>ID</th>
                    <td>
                        <?php echo $entry['qc_id']; ?>
                    </td>
                </tr>
                <tr>
                    <th>Name</th>
                    <td>
                        <?php echo $entry['qc_name']; ?>
                    </td>
                </tr>
                <tr>
                    <th>Description</th>
                    <td>
                        <?php echo $entry['qc_description']; ?>
                    </td>
                </tr>
                <tr>
                    <th>Date</th>
                    <td>
                        <?php echo $entry['qc_date']; ?>
                    </td>
                </tr>
                <tr>
                    <th>Outcome</th>
                    <td>
                        <?php echo $entry['qc_outcome']; ?>
                    </td>
                </tr>
            </table>
        </div>

        <?php if ($_SESSION['permission'] !== 'admin'): ?>
            <div class="form" style="max-width:50%">
                <h2>Edit QC Entry</h2>
                <form method="post" action="">

                    <input type="hidden" name="id" value="<?php echo $entry['qc_id']; ?>" />
                    <div class="input-group mb-3">
                        <label class="input-group-text">Name:</label>
                        <input class="form-control" type="text" name="name" value="<?php echo $entry['qc_name']; ?>" /><br>
                    </div>
                    <div class="input-group mb-3">
                        <label class="input-group-text">Description:</label>
                        <input class="form-control" type="text" name="description"
                            value="<?php echo $entry['qc_description']; ?>" /><br>
                    </div>
                    <div class="input-group mb-3">
                        <label class="input-group-text">Date:</label>
                        <input class="form-control" type="text" name="date" value="<?php echo $entry['qc_date']; ?>" /><br>
                    </div>
                    <div class="input-group mb-3">
                        <label class="input-group-text">Outcome:</label>
                        <input class="form-control" type="text" name="outcome"
                            value="<?php echo $entry['qc_outcome']; ?>" /><br>
                    </div>
                    <input type="submit" name="edit" value="Edit" />
                </form>
            </div>
        <?php endif; ?>

    </div>
</body>
<script>
    setTimeout(function () {
        document.getElementById('success-msg').style.display = 'none';
    }, 3000);
</script>

</html>