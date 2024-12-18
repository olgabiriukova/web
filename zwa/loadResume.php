<?php
/**
 * @file
 * PHP code for handling the upload of resumes and storing them in the database.
 *
 *
 * 
 * @author   Olga Biriukova
 */

 
 session_start();
 $response = array();
 
 if ($_SERVER["REQUEST_METHOD"] === "POST") {
 
     // Добавим абсолютный путь к директории загрузки резюме
     $resumeUploadPath = __DIR__ . "/resume/";
 
     if (!file_exists($resumeUploadPath)) {
         mkdir($resumeUploadPath, 0777, true);
     }
 
     $resumeFile = $_FILES['resume'];
     $jobId = $_POST['job_id']; 
     $resumeFileName = uniqid('resume_', true) . '_' . $resumeFile['name'];
     $resumeFilePath = $resumeUploadPath . $resumeFileName;
 
     if (move_uploaded_file($resumeFile['tmp_name'], $resumeFilePath)) {
         // Подключение к базе данных
         $conn = new mysqli('localhost', 'biriuolg', 'webove aplikace', 'biriuolg');
         
         if ($conn->connect_error) {
             die("error: " . $conn->connect_error);
         }
 
         
         $query = "SELECT email FROM jobs WHERE id = ?";
         $stmt = $conn->prepare($query);
         $stmt->bind_param("i", $jobId);
         $stmt->execute();
         $result = $stmt->get_result();
         
 
         if ($result ) {
             
             $row = $result->fetch_assoc();
             $employerEmail = $row['email'];
 
             // Используем подготовленный запрос для предотвращения SQL-инъекций
             $insertQuery = "INSERT INTO resumes (resume_path) VALUES ( ?)";
             $stmtInsert = $conn->prepare($insertQuery);
             $stmtInsert->bind_param("ss", $resumeFilePath);
 
             if ($stmtInsert->execute()) {
                 $response['success'] = true;
                 $response['message'] = "Resume uploaded successfully";
                 $response['resumePath'] = $resumeFilePath;
             } else {
                 $response['success'] = false;
                 $response['message'] = "Error uploading resume";
             }
 
             $stmtInsert->close();
         } else {
             $response['success'] = false;
             $response['message'] = "Error getting employer email";
         }
 
         $stmt->close();
         $conn->close();
     } else {
         $response['success'] = false;
         $response['message'] = "Error uploading resume";
     }
 } else {
     $response['success'] = false;
     $response['message'] = "Invalid request method";
 }
 
 header('Content-Type: application/json');
 echo json_encode($response);
 