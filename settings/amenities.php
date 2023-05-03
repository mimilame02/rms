<?php
session_start();
require_once '../includes/dbconfig.php';




if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST["action"];

    // Add feature
    if ($action == "add") {
        $feature_name = $_POST["feature_name"];
        $created_at = date('Y-m-d H:i:s');
        $updated_at = $created_at;
        $sql = "INSERT INTO features (feature_name, created_at, updated_at) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'sss', $feature_name, $created_at, $updated_at);
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION["success_message"] = "Feature added successfully";
        } else {
            $_SESSION["error_message"] = "Error adding feature";
        }
    }
    
    
    // Redirect back to the settings page
    header("Location: settings.php");
    exit();
}
?>

