<?php
session_start();
include('conn.php');

if (!isset($_SESSION['idusers'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['idusers']; 

// Fetch favorite recipes for the logged-in user
$query = "SELECT * FROM favourite
          JOIN recipes ON favourite.recipe_id = recipesid
          WHERE favourite.idusers = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Favourite Recipes</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php
    if (isset($_SESSION['error'])) {
        echo "<div class='alert alert-danger' id='message'>" . $_SESSION['error'] . "</div>";
        unset($_SESSION['error']); // Clear the error message
    }

    if (isset($_SESSION['message'])) {
        echo "<div class='alert alert-danger' id='message'>" . $_SESSION['message'] . "</div>";
        unset($_SESSION['message']); // Clear the success message
    }
    ?>

    <script>
    // Function to hide the message after 2 seconds
    setTimeout(function() {
        var message = document.getElementById('message');
        if (message) {
            message.style.display = 'none';
        }
    }, 2000);
    </script>

    <div class="header">
        <h1>My Favourite</h1>
        <p class="welcome">Hi, <?php echo $_SESSION['username']; ?><br>
        <a href="logout.php" class="logout" onclick="confirmLogout()">Logout</a><br>
        <a href="homepage.php">Back to Homepage</a></p>
    </div>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Rating</th>
            <th>Cuisine</th>
            <th>Cooking Time</th>
            <th>Delete</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            // pull all the data
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["recipesid"]. "</td>";
                echo "<td><a href='recipe page.php?id=" . $row["recipesid"]. "'>" . htmlspecialchars($row["name"]). "</a></td>";
                echo "<td>" . $row["description"]. "</td>";
                echo "<td>" . $row["rating"]. "</td>";
                echo "<td>" . $row["cuisine"]. "</td>";
                echo "<td>" . $row["cookingtime"]. " mins </td>"; 
                echo "<td><a href='delete_favourite.php?idrecipes=" . $row["recipesid"]. "'>Delete</a></td>";
                echo "</tr>";
        }
    } else {
        echo "<p>No favourite recipes found.</p>";
    }
    $stmt->close();
    $conn->close();
    ?>

</body>
</html>