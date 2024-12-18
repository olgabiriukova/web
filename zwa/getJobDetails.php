<?php
/**
 * @file
 * PHP code for retrieving job details from a database and returning them as JSON.
 *
 *
 * 
 * @author   Olga Biriukova
 */

/**
 * Retrieves job details from the database based on the job ID and outputs them as JSON.
 *
 * @return void
 */
function getJobDetails()
{
    // Database connection
    require "connection.php";

    // Get the job ID from the URL
    $jobId = $_GET['job'];

    // Validate the job ID
    if ($jobId !== null && $jobId !== false && $jobId > 0) {
        // Prepare and execute a query to retrieve job details based on the job ID
        $query = "SELECT * FROM jobs WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $jobId);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if there are rows in the result set
        if ($result->num_rows > 0) {
            // Fetch job details from the result set
            $jobDetails = $result->fetch_assoc();

            // Set the content type to JSON
            header('Content-Type: application/json');

            // Output the job details as JSON
            echo json_encode(['job' => $jobDetails]);
        } else {
            // If no rows are found, set the content type to JSON and output an error message
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Job not found']);
        }

        // Close the prepared statement
        $stmt->close();
    } else {
        // Output an error message for invalid job ID
        echo json_encode(['error' => 'Invalid job ID']);
    }

    // Close the database connection
    $conn->close();
}

// Call the function to retrieve and output job details
getJobDetails();

