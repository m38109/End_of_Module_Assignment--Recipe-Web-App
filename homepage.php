<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}
?>
<?php
require_once 'conn.php';

// Searching funciton
$whereClause = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rating = $_POST['rating'];
    $cuisine = $_POST['cuisine'];
    $name = $_POST['name'];
    
    $conditions = [];
    if (!empty($rating)) {
        $conditions[] = "rating = '$rating'";
    }
    if (!empty($cuisine)) {
        $conditions[] = "cuisine LIKE '%$cuisine%'";
    }
    if (!empty($name)) {
        $conditions[] = "name LIKE '%$name%'";
    }
    
    if (count($conditions) > 0) {
        $whereClause = "WHERE " . implode(" AND ", $conditions);
    }
}

// fetch all the recipes
$sql = "SELECT * FROM recipes $whereClause";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Homepage</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        function confirmLogout() {
            var username = "<?php echo $_SESSION['username']; ?>";
            if (confirm(username + ", are you sure you want to log out?")) {
                window.location.href = "logout.php";
            }
        }
    </script>
</head>
<body>
    <!-- show the error message --> 
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
        <h1>Recipe Homepage</h1>    
        <p class="welcome">Hi, <?php echo $_SESSION['username']; ?><br>
        <a href="logout.php" class="logout" onclick="confirmLogout()">Logout</a><br>
        <a href="favouritepage.php" class="logout">My Favourites</a></p>
    </div>
    <!-- searching -->
    <form method="post" action="">
        Name of the recipe: <input type="text" name="name">
        Cuisine: <input type="text" name="cuisine">
        Rating: <input type="text" name="rating">
        <input type="submit" value="Search">
    </form>

    <h2>Recipes List</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Rating</th>
            <th>Cuisine</th>
            <th>Cooking Time</th>
            <th>Image</th>
            <th>Favourite</th>
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
                echo "<td><img src='data:image/jpeg;base64,".base64_encode($row["img"])."' width='100' height='100'/></td>";
                echo "<td><a href='add_favourite.php?idrecipes=" . $row["recipesid"]. "'>Add to Favourite</a></td>"; 
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>0 results</td></tr>";
        }
        $conn->close();
        ?>
    </table>
</body>
</html>