<?php
session_start();

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
        $stored_hash = $row['password'];
        

        //Verify password
        if (password_verify($password, $stored_hash)) {
            // correct password
            echo "Login sucessful";
            header("Location: config.php");

        } else {
            echo "Invaild Password";
        }
    } else {
        echo "Email not exists.";
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
    <div class="login-container">
        <h2>Login</h2>
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" value="Login">
        </form>

    </div>
</body>
</html>