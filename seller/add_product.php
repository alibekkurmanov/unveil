<?php include 'check.php';

$seller_id = $_SESSION['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $category_id = $_POST['category_id'];
    $price_current = $_POST['price_current'];
    $description = $_POST['description'];
    $unit = $_POST['unit'];
    $size = $_POST['size'];
    $fixation = $_POST['fixation'];

    $data = array(
        'name' => $query->validate($name),
        'category_id' => $query->validate($category_id),
        'seller_id' => $query->validate($seller_id),
        'price_current' => $query->validate($price_current),
        'description' => $query->validate($description),
        //'rating' => 5, удалить из базы
        'unit' => $query->validate($unit),
        'size' => $query->validate($size),
        'fixation' => $query->validate($fixation)
    );

    $product_id = $query->lastInsertId('products', $data);

    if ($product_id) {
        if (isset($_FILES['documents'])) {
            $uploaded_documents = $query->saveFilesToDatabase($_FILES['documents'], "../src/documents/products/", $product_id);
        }
        if (isset($_FILES['image'])) {
            $uploaded_images = $query->saveImagesToDatabase($_FILES['image'], "../src/images/products/", $product_id);
        }
        header("Location: ./");
    }
}
