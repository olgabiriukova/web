<?php
/**
 * @file
 * PHP code for handling category deletion from the database.
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
 * Process the request for deleting a category.
 *
 * @param mysqli $conn The MySQLi database connection object.
 * @param array  $post The POST data from the form.
 *
 * @return void
 */
function deleteCategory($conn, $post)
{
    // Get the category ID from the form
    $categoryId = $post['category_id'];

    // Prepare the DELETE query using a prepared statement
    $deleteQuery = "DELETE FROM categories WHERE id = ?";
    $stmt = $conn->prepare($deleteQuery);
    
    // Bind the parameter to the prepared statement
    $stmt->bind_param('i', $categoryId);

    // Execute the prepared statement
    $deleteResult = $stmt->execute();

    // Check if the deletion was successful
    if ($deleteResult) {
        // Redirect to the index page after successful deletion
        header("Location: index.php");
        exit();
    } else {
        // If there is an error during deletion, display the error message
        echo 'Error ' . $stmt->error;
    }

    // Close the prepared statement
    $stmt->close();
}

// Process the request for deleting a category
if (isset($_POST['delete_category'])) {
    deleteCategory($conn, $_POST);
}

// Close the database connection
$conn->close();