<?php
session_start();
if (!isset($_SESSION['user']['id'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user']['id'];
$pdo = new PDO("mysql:host=localhost;dbname=gestion_budget", "root", "");


function getTotalRevenu($pdo, $userId) {
    $stmt = $pdo->prepare("
        SELECT SUM(t.montant) AS total 
        FROM transactions t
        JOIN categories c ON t.category_id = c.id
        WHERE c.type = 'revenu' AND t.user_id = ?
    ");
    $stmt->execute([$userId]);
    return $stmt->fetchColumn() ?: 0;
}


function getTotalDepense($pdo, $userId) {
    $stmt = $pdo->prepare("
        SELECT SUM(t.montant) AS total 
        FROM transactions t
        JOIN categories c ON t.category_id = c.id
        WHERE c.type = 'depense' AND t.user_id = ?
    ");
    $stmt->execute([$userId]);
    return $stmt->fetchColumn() ?: 0;
}


function getRecentTransactions($pdo, $userId) {
    $stmt = $pdo->prepare("
        SELECT t.*, c.nom AS categorie, c.type 
        FROM transactions t
        JOIN categories c ON t.category_id = c.id
        WHERE t.user_id = ?
        ORDER BY t.date_transaction DESC
        LIMIT 5
    ");
    $stmt->execute([$userId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


$totalRevenu = getTotalRevenu($pdo, $userId);
$totalDepense = getTotalDepense($pdo, $userId);
$solde = $totalRevenu - $totalDepense;
$recentTransactions = getRecentTransactions($pdo, $userId);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Gestion Budget</title>
    <style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f4f4f9;
    color: #333;
    margin: 0;
    padding: 20px;
}

h1, h2, h3 {
    color: #4a90e2;
    text-align: center;
    margin-bottom: 20px;
}

h1 {
    font-size: 36px;
    margin-top: 20px;
}

h2 {
    font-size: 28px;
}

h3 {
    font-size: 22px;
}


.container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 20px;
    background: #fff;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    border-radius: 8px;
    margin-top: 40px;
}

.container div {
    margin-bottom: 20px;
}

.container h3 {
    font-size: 22px;
    color: #333;
    margin-bottom: 10px;
}

.container p {
    font-size: 18px;
    color: #4a90e2;
}


table {
    width: 100%;
    margin-top: 30px;
    border-collapse: collapse;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

table th, table td {
    padding: 12px;
    text-align: left;
    border: 1px solid #ddd;
}

table th {
    background-color: #4a90e2;
    color: #fff;
}

table tr:nth-child(even) {
    background-color: #f4f4f9;
}

table tr:hover {
    background-color: #e1e1e1;
}


.site-footer {
    background-color: #1f1f1f;
    color: #f1f1f1;
    text-align: center;
    padding: 20px;
    margin-top: 40px;
    border-radius: 8px;
}

.site-footer p {
    font-size: 14px;
    margin: 0;
}

    </style>
</head>
<body>
    <h1>Dashboard Financier</h1>

    
     
            <h3>Total Revenus</h3>
            <p><?= number_format($totalRevenu, 2) ?> €</p>
        </div>
       
            <h3>Total Dépenses</h3>
            <p><?= number_format($totalDepense, 2) ?> €</p>
        </div>
        
            <h3>Solde</h3>
            <p><strong><?= number_format($solde, 2) ?> €</strong></p>
        </div>
    </div>

    <h2>Dernières Transactions</h2>
    <table border="1" cellpadding="8">
        <tr>
            <th>Type</th>
            <th>Catégorie</th>
            <th>Montant</th>
            <th>Description</th>
            <th>Date</th>
        </tr>
        <?php foreach ($recentTransactions as $t): ?>
            <tr>
                <td><?= $t['type'] == 'revenu' ? 'Revenu' : 'Dépense' ?></td>
                <td><?= htmlspecialchars($t['categorie']) ?></td>
                <td><?= number_format($t['montant'], 2) ?> €</td>
                <td><?= htmlspecialchars($t['description']) ?></td>
                <td><?= $t['date_transaction'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <footer class="site-footer">
        <p>&copy; <?= date("Y") ?> MonApp. Tous droits réservés.</p>
    </footer>
</body>
</html>