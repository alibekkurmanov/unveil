<?php
include 'check.php';

if (isset($_POST['item_id']) && isset($_POST['field']) && isset($_POST['value'])) {
    $itemId = $_POST['item_id'];
    $field = $_POST['field'];
    $value = $_POST['value'];
    // Добавить проверку пользовательских прав при необходимости !!!
    $query->executeQuery("UPDATE project_items SET $field = '$value' WHERE id = $itemId");
}
?>