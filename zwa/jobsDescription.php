<?php
/**
 * @file
 * PHP code for retrieving job description from a database.
 *
 *
 * 
 * @author   Olga Biriukova
 */

// Start the session
session_start();

// Database connection 
require "connection.php";

// Check if 'user_id' is set in the session
if (isset($_SESSION['user_id'])) {
    // Get the user ID from the session
    $user_id = $_SESSION['user_id'];

    // Query to retrieve user information based on the user ID
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    
    // Bind the user ID parameter
    $stmt->bind_param("i", $user_id);

    // Execute the statement
    $stmt->execute();

    // Get the result set
    $result = $stmt->get_result();
    // Check if the result is valid
    if ($result) {
        // Fetch user information
        $user = $result->fetch_assoc();
    } else {
        // Handle the case where the result is not valid
    }
} else {
    // If 'user_id' is not set in the session, set an empty user array
    $user = [];
}

// Check if 'job' is set in the URL
if (isset($_GET['job'])) {
    // Get the job ID from the URL
    $job_id = $_GET['job'];

    // Prepared statement to retrieve job information based on the job ID
    $jobQuery = "SELECT * FROM jobs WHERE id = ?";
    $stmtJob = $conn->prepare($jobQuery);
    $stmtJob->bind_param("i", $job_id);
    $stmtJob->execute();
    $jobResult = $stmtJob->get_result();

    // Check if the result is valid
    if ($jobResult) {
        // Fetch job information
        $job = $jobResult->fetch_assoc();
    } else {
        // Output an error message if the result is not valid
        echo "Error: " . $stmtJob->error;
    }

    // Close the prepared statement for the job query
    $stmtJob->close();
} else {
    // Output a message if 'job' is not set in the URL
    echo "Job not found";
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Details</title>
    <?php require "header.php"; ?>
</head>
<body>
<div id="jobDetails" class="flex-container">
    <div class="job-description">
    </div>
    <script src="jobDescription.js"></script>

    <?php if(isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === "worker"): 
        ?>
        <form id="resumeForm" action="loadResume.php" method="post">
        <div class="resume-section">
            <label for="resume">Submit resume (PDF):</label>
            <input type="file" id="resumeInput" name="resume" accept=".pdf" lang="en">
            <?php if(isset($_GET['job'])): ?>
                <input type="hidden" name="job_id" value="<?php echo htmlspecialchars($_GET['job']); ?>">
            <?php endif; ?>
            <input type="submit" value="Submit">
        </div>
    </form>
        
    <?php endif; ?>

  
    <?php
    if(isset($user['role']) && $user['role'] === "admin" && isset($_GET['job'])) {
        $job_id = $_GET['job'];
        echo '<a href="deleteJob.php?job_id=' . $job_id . '">Delete</a>';
    }
    ?>
    <script src="loadResume.js"></script>
</div>
</body>
</html>