<?php
include 'check.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_SESSION['id'];

    $product_id = $_POST['product_id'] ?? '';
    $quantity = $_POST['quantity'] ?? '';
    $projectId = $_POST['projectsList'] ?? '';

    $projectItems = array(
        'user_id' => $userId,
        'product_id' => $product_id,
        'quantity' => $quantity,
        'project_id' => $projectId
    );

    $query->insert('project_items', $projectItems);

    echo "Продукт успешно добавлен!";
} else {
    echo "Ошибка";
}
?>