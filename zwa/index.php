<?php  session_start();
require_once "header.php";
$user = $_SESSION['user'] ?? null;
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GoodJob</title>
    <link rel="stylesheet" href="style.css">
    
</head>
<body>

    <h2>Categories</h2>

    <ul id="categoryList">
        
    </ul>

    <div class="category-pagination">
        
    </div>
    <ul id="jobsList">
        
    </ul>

    <?php if(isset($user) && $user['role'] === "admin"): ?>
        <a href="createCategoryForm.php">Create category</a>
    <?php endif; ?>


    <script src="index.js"></script>

    <?php require_once "footer.php"; ?>
    
</body>
</html>