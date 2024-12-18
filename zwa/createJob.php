<?php session_start();
    require_once "header.php" ?>
    
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <title>SignUp</title>
    <script src="regValidate.js"></script>
  </head>

<body>
    <div class="reg-form">
    <form id = "createJobForm" action="uploadJob.php" method="post">
        <label for="name">Name*:</label>
        <input tabindex = "1" accesskey="j" type="text" id="name" name="name" required>
        <label for="company">Company*:</label>
        <input tabindex = "2" accesskey="p" type="text" id="company" name="company" required>
        <label for="email">Email*:</label>
        <input tabindex = "3" accesskey="l" type="email" id="email" name="email" required>
        <label for="salary">Salary*:</label>
        <input tabindex = "4" accesskey="d" type="text" id="salary" name="salary"  required value="<?php echo isset($_POST['salary']) ? htmlspecialchars($_POST['salary'], ENT_QUOTES, 'UTF-8') : ''; ?>">

    <?php
    require "connection.php";

    $query = "SELECT id, name FROM categories";
    $result = $conn->query($query);


    if ($result->num_rows > 0) {
        echo '<label for="category">Choose category*:</label>';
        echo '<select id="category" name="category">';
        
        while ($row = $result->fetch_assoc()) {
            echo '<option value="' . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') . '</option>';
        }

        echo '</select>';
    } else {
        echo 'noo categories';
    }

    $conn->close();
    ?>

        <label for="description">Description*:</label>
        <textarea name="description" tabindex = "5" accesskey="y" type="text" rows="8" cols="40" required>
        <?php if(isset($_POST['description'])) echo htmlspecialchars($_POST['description'], ENT_QUOTES, 'UTF-8'); ?>
        </textarea>
        <p>* required field</p>


        <button tabindex = "6" accesskey="k" id = "submitButton" type="submit">Odeslat</button>
    </form>
    </div>
    <?php require_once "footer.php"; ?>
</body>
</html>