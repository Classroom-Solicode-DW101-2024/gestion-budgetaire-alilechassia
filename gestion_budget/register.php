<?php
require 'connect.php';
require 'user.php';

$errors = [];

if (isset($_POST['registerBtn'])) {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

 
    if (empty($nom)) $errors['nom'] = 'Nom requis';
    if (empty($email)) $errors['email'] = 'Email requis';
    if (empty($password)) $errors['password'] = 'Mot de passe requis';
    if ($password !== $confirm_password) $errors['confirm'] = 'Les mots de passe ne correspondent pas';

    
    if (checkUser($email, $pdo)) {
        $errors['email'] = "Cet email est déjà utilisé";
    }

   
    if (empty($errors)) {
        $user = [
            'nom' => htmlspecialchars($nom),
            'email' => htmlspecialchars($email),
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ];
        addUser($user, $pdo); 
    }
}
?>

<form method="POST">
    <input type="text" name="nom" placeholder="Nom">
    <?php if (isset($errors['nom'])) echo "<p>" . $errors['nom'] . "</p>"; ?>

    <input type="email" name="email" placeholder="Email">
    <?php if (isset($errors['email'])) echo "<p>" . $errors['email'] . "</p>"; ?>

    <input type="password" name="password" placeholder="Mot de passe">
    <?php if (isset($errors['password'])) echo "<p>" . $errors['password'] . "</p>"; ?>

    <input type="password" name="confirm_password" placeholder="Confirmer le mot de passe">
    <?php if (isset($errors['confirm'])) echo "<p>" . $errors['confirm'] . "</p>"; ?>

    <button name="registerBtn">S'inscrire</button>
</form>
