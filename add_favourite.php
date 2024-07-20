<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

require_once 'conn.php';

$user_id = $_SESSION['idusers']; 
$recipe_id = $_GET['idrecipes'];

// Check if the favourite already exists
$sql_check = "SELECT * FROM favourite WHERE idusers = ? AND recipe_id = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("ii", $user_id, $recipe_id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows == 0) {
    // Insert the new favourite entry
    $sql_insert = "INSERT INTO favourite (idusers, recipe_id) VALUES (?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("ii", $user_id, $recipe_id);
    $stmt_insert->execute();
    $_SESSION['error'] = "Added to your Favourites.";
} else {
    $_SESSION['error'] = "You have already added this recipe to your favourites.";
}

$stmt_check->close();
$conn->close();

// Redirect back to the homepage
header("Location: homepage.php");
exit();
?>