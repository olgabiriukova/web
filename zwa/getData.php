<?php
/**
 * @file
 * PHP code for retrieving categories with pagination from a database.
 *
 *
 * 
 * @author   Olga Biriukova
 */


/**
 * Retrieves categories with pagination from the database.
 *
 * @param int $offset The offset for pagination.
 * @param int $limit  The number of categories per page.
 *
 * @return array An associative array containing categories and total category pages.
 */


function getCategoriesWithPagination($offset, $limit){
    
    require "connection.php";
    // Prepared statement to get the total number of categories
    $countQuery = "SELECT COUNT(*) as total FROM categories";
    $countStatement = $conn->prepare($countQuery);
    $countStatement->execute();
    $countResult = $countStatement->get_result();
    $totalItems = $countResult->fetch_assoc()['total'];
    $totalPages = ceil($totalItems / $limit);

    // Prepared statement to get categories with pagination and sorting
    $query = "SELECT * FROM categories ORDER BY name LIMIT ?, ?";
    $statement = $conn->prepare($query);
    $statement->bind_param("ii", $offset, $limit);
    $statement->execute();
    $result = $statement->get_result();

    $categories = [];

    if ($result === false) {
        die("Query error " );
    }

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }
    }

    // Close the connection
    $conn->close();

    // Return an associative array with categories and total category pages
    return ['categories' => $categories, 'totalCategoryPages' => $totalPages];
}

// Items per page and current page from the URL
$itemsPerPage = 3;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Validate and set the page number
if (!is_numeric($page) || $page < 1) {
    $page = 1;
}

// Calculate offset for pagination
$offset = ($page - 1) * $itemsPerPage;

// Get categories with pagination
$categoriesData = getCategoriesWithPagination($offset, $itemsPerPage);
$categories = $categoriesData['categories'];
$totalCategoryPages = $categoriesData['totalCategoryPages'];

// Set the content type to JSON and output the result
header('Content-Type: application/json');
echo json_encode(['categories' => $categories, 'totalCategoryPages' => $totalCategoryPages]);
exit();