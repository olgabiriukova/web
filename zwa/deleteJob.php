<?php
/**
 * @file
 * PHP code for deleting jobs from the database.
 *
 *
 * 
 * @author   Olga Biriukova
 */

// Start the session
session_start();

// Database connection 
require "connection.php";

/**
 * Deletes a job from the database based on its ID.
 *
 * @param mysqli $conn    The MySQLi database connection object.
 * @param int    $job_id  The ID of the job to be deleted.
 *
 * @return void
 */
function deleteJob($conn, $job_id)
{
    // Prepare and execute a query to delete a job based on its ID
    $deleteQuery = "DELETE FROM jobs WHERE id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $job_id);

    // Check if the deletion was successful
    if ($stmt->execute()) {
        // Redirect to the index page after successful deletion
        header("Location: index.php");
        exit();
    } else {
        // If there is an error during deletion, display the error message
        echo "Error: " . $stmt->error;
    }

    // Close the prepared statement
    $stmt->close();
}

// Check if 'job_id' is set in the URL
if (isset($_GET['job_id'])) {
    // Get the job ID from the URL
    $job_id = $_GET['job_id'];

    // Delete the job
    deleteJob($conn, $job_id);
} else {
    // If 'job_id' is not set in the URL, display a message
    echo "No job ID specified.";
}

// Close the database connection
$conn->close();