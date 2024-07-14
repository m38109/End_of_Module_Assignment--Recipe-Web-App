<?php
$exists = false;
$showError = false;

if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include the database connection
    require_once 'conn.php';

    //Get Form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    //check if email is already present
    $check_emailsql = "Select * from users where email='$email'";
    $result = mysqli_query($conn, $check_emailsql);
    $num = mysqli_num_rows($result);

    if($num == 0) {
        
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO `users` (`idusers`, `name`, `email`, `password`) VALUES (NULL,'$username', '$email', '$hashed_password')";

        $result = mysqli_query($conn, $sql);

        if($result) {
                header("Location: login.php");
        }
    }

    if($num>0){
        $exists = "Eamil not Available";
    }
    $conn -> close();
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="styles.css">
    <script src="validate.js"></script>
</head>
<body>
<?php 

    if($exists) { 
        echo '<div class="alert alert-danger" role="alert"> 
    
        <strong>Error!</strong> '. $exists.' 
       </div>';  
    }

?>

    <div class="login-container">
        <h2>Regeister</h2>
        <form name="registerForm" action="register.php" method="POST" onsubmit="return validateForm()">
            <input type="username" name="username" placeholder="Username" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" value="Submit">
        </form>

    </div>
</body>
</html>