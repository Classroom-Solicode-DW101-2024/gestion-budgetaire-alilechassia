<?php
function getCategories($pdo) {
    return [
        'revenu' => ['Salaire', 'Bourse', 'Ventes', 'Autres'],
        'depense' => ['Logement', 'Transport', 'Alimentation', 'Santé', 'Divertissement', 'Éducation', 'Autres']
    ];
}

function addTransaction($pdo, $userId, $categoryName, $categoryType, $amount, $description, $date) {
    $stmt = $pdo->prepare("INSERT INTO categories (nom, type) VALUES (?, ?)");
    $stmt->execute([$categoryName, $categoryType]);
    $categoryId = $pdo->lastInsertId();

    $stmt = $pdo->prepare("INSERT INTO transactions (user_id, category_id, montant, description, date_transaction) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$userId, $categoryId, $amount, $description, $date]);
}
?>