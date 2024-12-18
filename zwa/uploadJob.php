<?php
/**
 * @file
 * Insert Job Information into the Database.
 * 
 * 
 * @author Olga Biriukova 
 * 
 */

// Database connection 
require "connection.php";

/**
 * @brief Get job information from POST data.
 * @var string $name - The name of the job.
 * @var string $company - The company offering the job.
 * @var string $email - The email associated with the job.
 * @var float $salary - The salary for the job.
 * @var int $categoryID - The category ID associated with the job.
 * @var string $description - The description of the job.
 */
$name = $_POST["name"];
$company = $_POST["company"];
$email = $_POST["email"];
$salary = $_POST["salary"];
$categoryID = $_POST["category"]; // Assuming this is "category_id"
$description = $_POST["description"];




// Check if required fields are empty
if (empty($name) || empty($company) || empty($email) || empty($description)) {
    echo "All fields must be filled.";
} else {
    /**
     * @brief Insert job information into the database.
     * @var string $queryInsertJob - The SQL query to insert job information.
     * @var bool|mysqli_result $resultInsertJob - The result of executing the insert query.
     */
    $queryInsertJob = $conn->prepare("INSERT INTO jobs (name, company, email, salary, category_id, description) VALUES (?, ?, ?, ?, ?, ?)");
    $queryInsertJob->bind_param("ssssss", $name, $company, $email, $salary, $categoryID, $description);
    
    // Execute the insert query
    
    $resultInsertJob = $queryInsertJob->execute();

    // Check if the insertion was successful
    if ($resultInsertJob) {
        // Redirect to the index.php page
        header("Location: index.php");
        exit();
    } else {
        // Display an error message if the insertion fails
        echo "Error: " . $conn->error;
    }

    $queryInsertJob->close();
}

// Close the database connection
$conn->close();
