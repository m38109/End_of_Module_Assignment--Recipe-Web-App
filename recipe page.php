<?php

require_once 'conn.php';

//Get the recipe id from the URL parpmeter
$recipe_id = isset($_GET['id']) ? intval($_GET['id']):1;

//Get the recipe details
$recipe_id = isset($_GET['id']) ? intval($_GET['id']) :1;

// Fetch the recipe details
$recipe_sql = "SELECT * FROM recipes WHERE recipesid = $recipe_id";
$recipe_result = $conn->query($recipe_sql);
$recipe = $recipe_result->fetch_assoc();

// Fetch the ingredients
$ingredients_sql = "SELECT * FROM ingredients WHERE recipe_id = $recipe_id";
$ingredients_result = $conn->query($ingredients_sql);

// Fetch the steps
$steps_sql = "SELECT * FROM steps WHERE recipe_id = $recipe_id";
$steps_result = $conn->query($steps_sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($recipe['name']); ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class= recipecontainer>
    <h1><?php echo htmlspecialchars($recipe['name']); ?></h1>
    
    <img src="<?php echo htmlspecialchars($recipe['img']); ?>" alt="<?php echo htmlspecialchars($recipe['name']); ?>">
    
    <p><?php echo htmlspecialchars($recipe['description']); ?></p>

    <h2>Ingredients</h2>
    <ul>
        <?php while($ingredient = $ingredients_result->fetch_assoc()): ?>
            <li><?php echo htmlspecialchars($ingredient['ingredient']); ?></li>
        <?php endwhile; ?>
    </ul>

    <h2>Steps</h2>
        <ol>
            <?php while($step = $steps_result->fetch_assoc()): ?>
                <li><?php echo htmlspecialchars($step['step']); ?></li>
            <?php endwhile; ?>
    </ol>
    </div>
</body>
</html>

<?php
// Close connection
$conn->close();
?>