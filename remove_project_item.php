<?php include 'check.php';

if (isset($_GET['remove_item_id']) && !empty($_GET['remove_item_id'])) {
    $item_id = $_GET['remove_item_id'];

    // Добавить проверку что пользователь имеет право удалять этот элемент !!!
    $query->executeQuery("DELETE FROM project_items WHERE id = $item_id");
    //header("Location: ./shoping-cart.php");
    exit;
}
