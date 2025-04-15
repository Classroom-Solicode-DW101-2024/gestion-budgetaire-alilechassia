<?php
require 'connect.php';

if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>
<body>

<form action="register.php" method="post">
    <label for="username">Username</label>
    <br>
    <input type="text" name="username" placeholder="Username">
    <br><br>
    <label for="email">Email</label>
    <br>
    <input type="text" name="email" placeholder="Email">
    <br><br>
    <label for="password">Password</label>
    <br>
    <input type="password" name="password" placeholder="Password">
    <br><br>
    <input type="submit" value="Register">
</form>

</body>
</html>