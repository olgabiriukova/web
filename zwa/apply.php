<?php
/**
 * @file
 * PHP code for retrieving job details from a database and returning them as JSON.
 *
 *
 * @author   Olga Biriukova
 */

// Database connection
require "connection.php";


/**
 * Function to retrieve job details by job ID.
 *
 * @param mysqli $conn   The database connection.
 * @param int    $jobId  The job ID.
 *
 * @return array|false   Associative array with job details or false if job not found.
 */
// Get the job ID from the URL
$jobId = $_GET['job'];

// Prepare and execute a query to retrieve job details based on the job ID
$stmt = $conn->prepare("SELECT * FROM jobs WHERE id = ?");
    
    // Bind the job ID parameter
    $stmt->bind_param("i", $jobId);

    // Execute the statement
    $stmt->execute();

    // Get the result set
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

// Close the database connection
$conn->close();
