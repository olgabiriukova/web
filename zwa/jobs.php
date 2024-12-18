<?php
/**
 * @file
 * PHP code for displaying a list of jobs
 *
 *
 * 
 * @author   Olga Biriukova 
 */

// Start the session
session_start();

// Database connection 
require "connection.php";

// Get the user ID from the session or set it to an empty string
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';

// Prepared statement to retrieve user information based on the user ID
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if the result is valid
if ($result) {
    // Fetch user information
    $user = $result->fetch_assoc();
} else {
    // Handle the case where the result is not valid
}

// Check if 'category' is set in the URL
if (isset($_GET['category'])) {
    // Get the category ID from the URL
    $categoryId = $_GET['category'];

    // Prepared statement to retrieve the category name based on the category ID
    $categoryNameQuery = "SELECT name FROM categories WHERE id = ?";
    $stmtCategoryName = $conn->prepare($categoryNameQuery);
    $stmtCategoryName->bind_param("i", $categoryId);
    $stmtCategoryName->execute();
    $categoryNameResult = $stmtCategoryName->get_result();

    // Check if the result is valid
    if ($categoryNameResult) {
        // Fetch the category name
        $category = $categoryNameResult->fetch_assoc();
    } else {
        // Handle the case where the result is not valid
        echo "Category error: " . $conn->error;
    }

    // Prepared statement to retrieve jobs based on the category ID
    $jobsQuery = "SELECT * FROM jobs WHERE category_id = ?";
    $stmtJobs = $conn->prepare($jobsQuery);
    $stmtJobs->bind_param("i", $categoryId);
    $stmtJobs->execute();
    $jobsResult = $stmtJobs->get_result();

    // Check if the result is valid
    if (!$jobsResult) {
        // Handle the case where the result is not valid
        echo "Jobs error: " . $conn->error;
    }
} else {
    // Output a message if 'category' is not set in the URL
    echo "Category not found";
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobs List</title>
    <?php require "header.php";?>
</head>
<body>
    <h2><?php echo $category['name']; ?></h2>
    
    <ul id="jobsList">
    </ul>

    <div class="job-pagination">
    </div>

    <script src="jobs.js"></script>
    <?php if(isset($user['role']) && $user['role'] === "admin"){ 
        $_SESSION['role'] = 'admin';?>
        <form action="deleteCategory.php" method="post">
            <input type="hidden" name="category_id" value="<?php echo $categoryId; ?>">
            <input type="submit" name="delete_category" value="Delete">
        </form>
    <?php } ?>
</body>
</html>