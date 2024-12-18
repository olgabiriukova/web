<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Category</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <?php require_once "header.php"; ?>
    <div class="reg-form">
    <h1>Create Category</h1>

    <form action="createCategory.php" method="post">
        <label for="categoryName*">Category Name:</label>
        <input tabindex = "1" accesskey="j" type="text" id="name" name="name" required value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8') : ''; ?>">
        
        <button type="submit" tabindex = "2" accesskey="k" name="createCategory">Create Category</button>
    </form>
    <p>* required field</p>
    </div>
    <?php require_once "footer.php"; ?>
</body>
</html>