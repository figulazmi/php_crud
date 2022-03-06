<?php 

session_start();
require 'functions.php';

// cek cookie
if( isset($_COOKIE['id']) && isset($_COOKIE['key']) ) {
    $id = $_COOKIE['id'];
    $key = $_COOKIE['key'];

    // ambil username berdasarkan id
    $result = mysqli_query($conn, "SELECT username FROM user WHERE id = $id");
    $row = mysqli_fetch_assoc($result);

    // cek cookie dan username
    if( $key === hash('sha256', $row['username']) ) {
        $_SESSION['login'] = true;
    }
}

if( isset($_SESSION["login"])) {
    header("Location: index.php");
    exit;
}


if( isset($_POST["login"]) ) {

    $username = $_POST["username"];
    $password = $_POST["password"];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");

    // cek username
    if( mysqli_num_rows($result) === 1 ) {

        // cek password 
        $row = mysqli_fetch_assoc($result);
        if( password_verify($password, $row["password"]) ) {

            // set session
            $_SESSION["login"] = true;
            
            // cek remember me
            if( isset ($_POST["remember"]) ) {
                // buat cookie
                setcookie('login', 'true', time() + 60);
            }

            header("Location: index.php");
            exit;
        }
    }

    $error = true;

}
    
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Login</title>
</head>
<body>
    <h1>Halaman Login</h1>

    <?php if( isset($error) ) : ?>
        <p style="color: red; font-style:italic;">Username / Password salah</p>
    <?php endif; ?>

    <form action="" method="POST">
        <ul>
            <li>
                <label>
                    Username :
                    <input type="text" name="username">
                </label>
            </li>
            <li>
                <label>
                    Password :
                    <input type="password" name="password">
                </label>
            </li>
            <li>
                <label>
                    <input type="checkbox" name="remember">
                    Remember me
                </label>
            </li>
            <li>
                <button type="submit" name="login">Login</button>
            </li>
        </ul>
    </form>
</body>
</html>