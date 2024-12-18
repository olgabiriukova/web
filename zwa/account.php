<?php
/**
 * @file
 * PHP code for displaying and modifying user profile.
 *
 *
 *
 * @author   Olga Biriukova
 */

// Start the session
session_start();

// Database connection 
require "connection.php";

// Get user ID from the session
$user_id = $_SESSION['user_id'];

// Prepare and execute a query to fetch user details
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if the result is valid
if ($result) {
    $user = $result->fetch_assoc();

    // Check if the user's email is set in the session
    if (isset($_SESSION['user']['email'])) {
        $tmpEmail = $_SESSION['user']['email'];
    }
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve data from the POST request
    $newEmail = $_POST['new_email'];
    $newName = $_POST['new_name'];
    $newSurname = $_POST['new_surname'];

    $newPassword = $_POST['new_password'];

    // Check if the new email is different from the temporary email
    if ($newEmail !== $tmpEmail) {
        // Prepare and execute a query to check for existing email
        $checkEmailQuery = "SELECT * FROM users WHERE email = ?";
        $stmtCheckEmail = $conn->prepare($checkEmailQuery);
        $stmtCheckEmail->bind_param("s", $newEmail);
        $stmtCheckEmail->execute();
        $resultCheckEmail = $stmtCheckEmail->get_result();

        // If email already exists, display an error message
        if ($resultCheckEmail->num_rows > 0) {
            echo "Error: Email already exists";
        } else {
            // Prepare and execute a query to update user details
            if(!empty($newPassword)){
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $updateQuery = "UPDATE users SET name = ?, surname = ?, email = ?, password = ? WHERE id = ?";
                $stmtUpdate = $conn->prepare($updateQuery);
                $stmtUpdate->bind_param("ssssi", $newName, $newSurname, $newEmail, $hashedPassword, $user_id);
            }else{
                $updateQuery = "UPDATE users SET name = ?, surname = ?, email = ? WHERE id = ?";
                $stmtUpdate = $conn->prepare($updateQuery);
                $stmtUpdate->bind_param("sssi", $newName, $newSurname, $newEmail,  $user_id);

            }

            

            // If update is successful, update session variables
            if ($stmtUpdate->execute()) {
                // Update session variables
                if (!empty($newEmail)) {
                    $_SESSION['user']['email'] = $newEmail;
                }
                if (!empty($newName)) {
                    $_SESSION['user']['name'] = $newName;
                }
                if (!empty($newSurname)) {
                    $_SESSION['user']['surname'] = $newSurname;
                }
                echo "Update successful";
            } else {
                echo "Error updating user: " . $stmtUpdate->error;
            }

            // Close the statement
            $stmtUpdate->close();
        }

        // Close the statement
        $stmtCheckEmail->close();
    } else {
        if(!empty($newPassword)){
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $updateQuery = "UPDATE users SET name = ?, surname = ?, email = ?, password = ? WHERE id = ?";
            $stmtUpdate = $conn->prepare($updateQuery);
            $stmtUpdate->bind_param("ssssi", $newName, $newSurname, $newEmail, $hashedPassword, $user_id);
        }else{
            
            $updateQuery = "UPDATE users SET name = ?, surname = ?, email = ? WHERE id = ?";
            $stmtUpdate = $conn->prepare($updateQuery);
            $stmtUpdate->bind_param("sssi", $newName, $newSurname, $newEmail, $user_id);

        }


        

        // If update is successful, update session variables
        if ($stmtUpdate->execute()) {
            // Update session variables
            if (!empty($newEmail)) {
                $_SESSION['user']['email'] = $newEmail;
            }
            if (!empty($newName)) {
                $_SESSION['user']['name'] = $newName;
            }
            if (!empty($newSurname)) {
                $_SESSION['user']['surname'] = $newSurname;
            }
            echo "Update successful";
        } else {
            echo "Error updating user: " . $stmtUpdate->error;
        }

        // Close the statement
        $stmtUpdate->close();
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <?php require "header.php"; ?>
</head>
<body>
    <div class="profile-container">
        <h2>Profile</h2>

        <?php
            $avatarPath = "avatars/" . $_SESSION['user']['avatarName'];
            if (file_exists($avatarPath)) {
                echo '<img src="' . $avatarPath . '" height = "50" width="50" alt="User Avatar"  class="avatar-img"  >';
            } else {
                echo '<p>No avatar available</p>';
            }
            ?>
        <p><strong>Name: </strong> <?php echo htmlspecialchars($_SESSION['user']['name']); ?></p>
        <p><strong>Surname: </strong> <?php echo htmlspecialchars($_SESSION['user']['surname']); ?></p>
        <p><strong>Email: </strong> <?php echo htmlspecialchars($_SESSION['user']['email']); ?></p>
        <p><strong>Role: </strong><?php echo htmlspecialchars($_SESSION['user']['role']); ?></p>

        <?php if ($_SESSION['user']['role'] === "employer"): ?>
            <a href="createJob.php">Create a job</a>

        <?php endif; ?>

        <h2>Edit</h2>
        <form method="post" action="">
            <label for="new_name"><strong>New name:</strong></label>
            <input type="text" accesskey="l" tabindex="1" id="new_name" name="new_name" value="<?php echo htmlspecialchars($_SESSION['user']['name']); ?>">
            <br>
            <label for="new_surname"><strong>New surname:</strong></label>
            <input type="text" accesskey="j" tabindex="2" id="new_surname" name="new_surname" value="<?php echo htmlspecialchars($_SESSION['user']['surname']); ?>">
            <br>
            <label for="new_email"><strong>New email:</strong></label>
            <input type="email" accesskey="t" tabindex="3" id="new_email" name="new_email" value="<?php echo htmlspecialchars($_SESSION['user']['email']); ?>">
            <label for="new_password"><strong>New password:</strong></label>
            <input type="password" accesskey="p" tabindex="4" id="new_password" name="new_password" value="">
            <br>
            <input type="submit" accesskey="k" tabindex="5" value="Save">
        </form>

        <form method="post" action="logout.php">
            <input type="submit" accesskey="x" tabindex="6" value="Exit">
        </form>
    </div>
    <?php require_once "footer.php"; ?>
</body>
</html>