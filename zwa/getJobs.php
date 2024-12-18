<?php
/**
 * @file
 * PHP code for retrieving jobs by category with pagination from a database.
 *
 * 
 * @author   Olga Biriukova
 */


/**
 * Retrieves jobs by category with pagination from the database.
 *
 * @param int $categoryId  The category ID.
 * @param int $offset      The offset for pagination.
 * @param int $limit       The number of jobs per page.
 *
 * @return array An array containing jobs.
 */
function getJobsByCategory($categoryId, $offset, $limit) {
    require "connection.php";

    // Prepared statement to get jobs by category with pagination and sorting
    $query = "SELECT * FROM jobs WHERE category_id = ? ORDER BY name LIMIT ?, ?";
    $statement = $conn->prepare($query);
    $statement->bind_param("iii", $categoryId, $offset, $limit);
    $statement->execute();
    $result = $statement->get_result();

    $jobs = [];

    if ($result === false) {
        die("Query error " );
    }

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $jobs[] = $row;
        }
    }

    // Close the connection
    $conn->close();

    // Return the array of jobs
    return $jobs;
}

/**
 * Calculates and returns the total number of pages for pagination.
 *
 * @param int $categoryId    The category ID.
 * @param int $itemsPerPage  The number of items per page.
 *
 * @return int The total number of pages.
 */
function getTotalPages($categoryId, $itemsPerPage) {
    require "connection.php";


    
    // Prepared statement to get the total number of jobs for a category
    $query = "SELECT COUNT(*) as total FROM jobs WHERE category_id = ?";
    $statement = $conn->prepare($query);
    $statement->bind_param("i", $categoryId);
    $statement->execute();
    $result = $statement->get_result();

    if ($result === false) {
        die("Database error " );
    }

    $totalJobs = 0;
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $totalJobs = $row['total'];
    }

    // Close the connection
    $conn->close();

    // Return the total number of pages
    return ceil($totalJobs / $itemsPerPage);
}

// Check if category and page parameters are set in the URL
if (isset($_GET['category']) && isset($_GET['page'])) {
    // Get category and page parameters from the URL
    $categoryId = $_GET['category'];
    if ($categoryId !== null && $categoryId !== false && $categoryId > 0) {
    }else{
        echo json_encode(['error' => 'Invalid category ID']);
    }
    $page = $_GET['page'];
    // Validate and set the page number
    if (!is_numeric($page) || $page < 1) {
        $page = 1;
    }

    // Set items per page and calculate offset for pagination
    $itemsPerPage = 4;
    $offset = ($page - 1) * $itemsPerPage;

    // Get jobs by category with pagination
    $jobs = getJobsByCategory($categoryId, $offset, $itemsPerPage);

    // Get total number of pages for pagination
    $totalPages = getTotalPages($categoryId, $itemsPerPage);

    
   

    // Set the content type to JSON and output the result
    header('Content-Type: application/json');
    echo json_encode(['jobs' => $jobs, 'totalJobPages' => $totalPages]);
} else {
    // If category or page parameters are not set, return a 400 Bad Request response
    header('HTTP/1.1 400 Bad Request');
    echo 'Error: Category ID or Page not found';
}