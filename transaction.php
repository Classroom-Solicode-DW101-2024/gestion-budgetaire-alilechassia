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
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <h1>Dashboard Financier</h1>

    <div style="display:flex; gap:20px;">
        <div style="padding:20px; background:#e0ffe0; border-radius:10px;">
            <h3>Total Revenus</h3>
            <p><?= number_format($totalRevenu, 2) ?> €</p>
        </div>
        <div style="padding:20px; background:#ffe0e0; border-radius:10px;">
            <h3>Total Dépenses</h3>
            <p><?= number_format($totalDepense, 2) ?> €</p>
        </div>
        <div style="padding:20px; background:#e0e0ff; border-radius:10px;">
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
