<?php
session_start();
$exists = false;
$showError = false;

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    require_once 'conn.php';

    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if the email exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $num = $result->num_rows;

    if ($num == 1){
        $row = mysqli_fetch_assoc($result);
        $username = $row['name'];
        $idusers = $row['idusers'];
        $stored_hash = $row['password'];
        

        //Verify password
        if (password_verify($password, $stored_hash)) {
            // correct password
            // Set session variables
            $_SESSION['username']= $username;
            $_SESSION['email']= $email;
            $_SESSION['idusers']=$idusers;
            $_SESSION['loggedin']= true;
            header("Location: homepage.php");

        } else {
            $showError = "Invaild Password";
        }
    } else {
        $exists = "Email not exists.";
    }

    $stmt->close();
    $conn ->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php 

    if($exists) { 
        echo '<div class="alert alert-danger" role="alert"> 

        <strong>Error!</strong> '. $exists.' 
    </div>';  
    }

    if ($showError) {
        echo '<div class="alert alert-danger" role="alert"> 

        <strong>Error!</strong> '. $showError.' 
    </div>';  
    }

    ?>
    <div class="login-container">
        <h2>Login</h2>
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" value="Login">
            <a href="register.php">Register page</a>
        </form>

    </div>
</body>
</html>