<?php

require 'connect.php';

$errors = [];

if (isset($_POST['registerBtn'])) {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($nom)) {
        $errors['nom'] = 'Nom requis.';
    }

    if (empty($email)) {
        $errors['email'] = 'Email requis.';
    }

    if (empty($password)) {
        $errors['password'] = 'Mot de passe requis.';
    }

    if (empty($confirm_password)) {
        $errors['confirm_password'] = 'Confirmation du mot de passe requise.';
    }

    if (!empty($password) && !empty($confirm_password) && $password !== $confirm_password) {
        $errors['match'] = 'Les mots de passe ne correspondent pas.';
    }

    if (empty($errors)) {
        $user = [
            'nom' => htmlspecialchars($nom),
            'email' => htmlspecialchars($email),
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ];

        $stmt = $pdo->prepare("INSERT INTO users (nom, email, password, created_at) VALUES (:nom, :email, :password, NOW())");
        $stmt->bindParam(':nom', $user['nom']);
        $stmt->bindParam(':email', $user['email']);
        $stmt->bindParam(':password', $user['password']);
        $stmt->execute();

        echo "<p>Inscription réussie !</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>register</title>
</head>
<body>
<form method="POST" action="register.php">
    <label>Nom:</label>
    <input type="text" name="nom" placeholder="Nom">

    <?php if (isset($errors['nom'])): ?>
        <p><?php echo $errors['nom']; ?></p>
    <?php endif; ?>

    <label>Email:</label>
    <input type="email" name="email" placeholder="Email">

    <?php if (isset($errors['email'])): ?>
        <p><?php echo $errors['email']; ?></p>
    <?php endif; ?>

    <label>Mot de passe:</label>
    <input type="password" name="password" placeholder="Mot de passe">

    <?php if (isset($errors['password'])): ?>
        <p><?php echo $errors['password']; ?></p>
    <?php endif; ?>

    <label>Confirmer mot de passe:</label>
    <input type="password" name="confirm_password" placeholder="Confirmez le mot de passe">

    <?php if (isset($errors['confirm_password'])): ?>
        <p><?php echo $errors['confirm_password']; ?></p>
    <?php endif; ?>

    <?php if (isset($errors['match'])): ?>
        <p><?php echo $errors['match']; ?></p>
    <?php endif; ?>

    <button type="submit" name="registerBtn">S'inscrire</button>
</form>
</body>
</html>
