<?php
session_start();
include('conn.php');

if (!isset($_SESSION['idusers'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['idusers'];
$recipe_id = $_GET['idrecipes'];

if (isset($recipe_id)) {
    // Prepare a statement to delete the favorite recipe for the user
    $query = "DELETE FROM favourite WHERE idusers = ? AND recipe_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $user_id, $recipe_id);
    if ($stmt->execute()) {
        // Redirect to the favourites page
        header("Location: favouritepage.php");
        $_SESSION['error'] = "Deleted.";
    } else {
        // Redirect to the favourites page
        header("Location: favouritepage.php");
    }
    $stmt->close();
}

$conn->close();
?>