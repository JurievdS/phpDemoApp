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


include_once('util/database.php');
$db = new Database();
$conn = $db->getConnection();


// Import qualityController
require_once('./controllers/qualityController.php');
// Create new instance
$qualityControl = new qualityControl();
// Retrieve all QC entries
$qualityControlEntries = $qualityControl->getQCEntries();

if (isset($_POST['delete']) && $_SESSION['permission'] == 'admin') {
    // Check if delete button was clicked
    $qc_id = $_POST['id'];
    $qualityControl->deleteQCEntry($qc_id);
    header("Location: {$_SERVER['PHP_SELF']}");
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Home</title>
    <?php // Import Bootstrap?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-PO5nR+oVdD5lr5pNR53HJUCt/kp7V+ZrCJ98DkdLjIcP+i2oylE9JTpjFCVJzvIV" crossorigin="anonymous"></script>
</head>
<body>
    <?php // Insert header ?>
    <?php include './templates/header.php'; ?>


    <?php
          // Display error messages if any
          if(isset($_SESSION['error'])) {
            echo '<p style="color:red">'.$_SESSION['error'].'</p>';
            unset($_SESSION['error']);
          }
    ?>

    <div class="container">
        <h1>Quality Control Audits</h1>

        <br/>

        <!-- Display table of qualityControl data -->
        <div class="table">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Description</th>
                        <th scope="col">Audit date</th>
                        <th scope="col">Outcome</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if(empty($qualityControlEntries)){echo '<tr><td colspan="7">No data to display.</td></tr>';}?>
                    <?php foreach($qualityControlEntries as $entry): ?>
                    <tr>
                        <th class="row">
                            <?php echo $entry['qc_id']; ?>
                        </th>
                        <td>
                            <?php echo $entry['qc_name']; ?>
                        </td>
                        <td>
                            <?php echo $entry['qc_description']; ?>
                        </td>
                        <td>
                            <?php echo $entry['qc_date']; ?>
                        </td>
                        <td>
                            <?php echo $entry['qc_outcome']; ?>
                        </td>
                        <td>
                            <form method="get" action="viewQCEntry.php">
                                <input type="hidden" name="id" value="<?php echo $entry['qc_id']; ?>" />
                                <input type="submit" name="view" value="View" />
                            </form>
                            <?php if($_SESSION['permission'] == 'admin'): ?>
                            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                <input type="hidden" name="id" value="<?php echo $entry['qc_id']; ?>" />
                                <input type="submit" name="delete" value="Delete" />
                            </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php if($_SESSION['permission'] !== 'admin'): ?>
            <button onclick="window.location.href='addQCEntry.php';">Add Entry</button>
        <?php endif; ?>
    </div>
</body>
</html>