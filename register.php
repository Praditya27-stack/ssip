<?php
// Include the database connection file
include __DIR__ . "/database.php";
session_start();

// Check if the form is submitted
if (isset($_POST["register-button"])) {
    // Retrieve form data
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $rpassword = $_POST["repeat-password"];
    if($password ==$rpassword ){

        // Check if the username already exists in the database
        $check_sql = "SELECT * FROM customers WHERE username = '$username'";
        $check_result = $db->query($check_sql);
        $message = "Username already exists.";

        if ($check_result && $check_result->num_rows > 0) {
            $message = "Username already exists.";
            header("location: register.php?message=" . urlencode($message));
            exit; // Stop further execution
        } else {
            $hash_pass = hash("sha256", $password);

            $insert_sql = "INSERT INTO customers (customer_name, email, phone,username,password)
                        VALUES ('$name', '$email', '$phone','$username', '$hash_pass')";
            
            $insert_result = $db->query($insert_sql);
    
            if ($insert_result) {
                header("location: submit-login.php");
                exit; // Stop further execution
            } 
        }
    }
    else{
        $message = "Password is not match";
        header("location: register.php?message=" . urlencode($message));
    }

}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="register.css">

    <title>Register</title>
</head>
<body>
    <div class="register-container">
        <form action="register.php" method="POST" class="register-form">
            <h2>Register</h2>
            <div class="input-group">
            <?php
                
                echo"<p>'$message'</p>"?>
                <label for="name">Name</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="input-group">
                <label for="phone">Nomor Telepon</label>
                <input type="tel" id="phone" name="phone" required>
            </div>
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="input-group">
                <label for="repeat-password">Ulangi Password</label>
                <input type="password" id="repeat-password" name="repeat-password" required>
            </div>
            <button type="submit" name="register-button" class="register-button">Daftar</button>
        </form>
    </div>
</body>
</html>
