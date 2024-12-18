<?php
/**
 * @file
 * PHP code for user registration.
 *
 *
 * @author   Olga Biriukova
 */

// Start the session
session_start();

require "connection.php";


// Response array to be sent as JSON
$response = array(); 

// Check if the request method is POST and CSRF token is valid
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token']) {
    // Get user registration data from the POST request
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['repeat'];
    $role = $_POST['role'];

    // Validate user input
    if (empty($name) || empty($surname) || empty($email) || empty($password) || empty($confirmPassword) || empty($role)) {
        $response['success'] = false;
        $response['message'] = "All fields must be filled";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['success'] = false;
        $response['message'] = "Invalid email format";
    } elseif ($password != $confirmPassword) {
        $response['success'] = false;
        $response['message'] = "Passwords do not match";
    } else {
        // Check if the email is already registered
        $checkEmailQuery = "SELECT * FROM users WHERE email='$email'";
        $result = $conn->query($checkEmailQuery);

        if ($result->num_rows > 0) {
            $response['success'] = false;
            $response['message'] = "Email is already registered";
        } else {
            // Hash the password for storage
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            if (!extension_loaded('gd')) {
                $response['success'] = false;
                $response['message'] = "GD extension is not available. Please enable GD in your PHP configuration.";
                // Дополнительные действия, если расширение GD отсутствует
            }

            // Configure avatar upload
            $targetDir = "avatars"; 
            $avatarName = basename($_FILES["avatar"]["name"]);
            $targetFilePath = $targetDir . '/' . $avatarName;
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

            // Allowed avatar formats
            $allowedFormats = array("jpg", "png", "jpeg");


            // Check if the avatar format is allowed
            if (in_array($fileType, $allowedFormats)) {
                // Move the uploaded avatar to the target directory
                if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $targetFilePath)) {

                    // Resize the avatar
                    $filename = $targetFilePath;
                    list($width, $height) = getimagesize($filename);
                    $newheight = 50;
                    $newwidth = 50;
                    switch ($fileType) {
                        case "jpeg" || "jpg":
                            $thumb = imagecreatetruecolor($newwidth, $newheight);
                            $source = imagecreatefromjpeg($filename);
                            break;
                        case "png":
                            $thumb = imagecreatetruecolor($newwidth, $newheight);
                            $source = imagecreatefrompng($filename);
                            break;
                            
                    }

                    imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

                    switch ($fileType) {
                        case "jpeg" || "jpg":
                            header('Content-Type: image/jpeg');
                            break;
                        case "png":
                            header('Content-Type: image/png');
                            break;
                    }

                    // Insert user data into the database
                    $sql = $conn->prepare("INSERT INTO users (name, surname, email, password, role, avatar) VALUES (?, ?, ?, ?, ?, ?)");
                    $sql->bind_param("ssssss", $name, $surname, $email, $hashed_password, $role, $avatarName);
    
                    // Execute the insert query
                    
                    $resultInsert = $sql->execute();

                    // Set session variables for the registered user
                    $_SESSION['user_id'] = $conn->insert_id;
                    $_SESSION['user']['name'] = $name;
                    $_SESSION['user']['surname'] = $surname;
                    $_SESSION['user']['email'] = $email;
                    $_SESSION['user']['role'] = $role;
                    $_SESSION['user']['avatarName'] = $avatarName;

                    // Check if the SQL query is executed successfully
                    if ($resultInsert === TRUE) {
                        $response['success'] = true;
                        $response['message'] = "Registration successful";
                        //header("Location: account.php");
                        //exit();
                       
                    } else {
                        $response['success'] = false;
                        $response['message'] = "Error: ";
                    }
                } else {
                    $response['success'] = false;
                    $response['message'] = "Sorry, there was an error uploading your file.";
                }
            } else {
                $response['success'] = false;
                $response['message'] = "Invalid file format. Allowed formats: " . implode(", ", $allowedFormats);
            }
        }
    }

   

}

// Close the database connection
$conn->close();

header('Content-Type: application/json');
// Send the response as JSON
echo json_encode($response);
exit();