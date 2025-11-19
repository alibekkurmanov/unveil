<?php
include 'check.php';

$project_id = $query->validate($_GET['project_id']);
$p_id = $query->select('project', 'id', 'where id = ' . $project_id);

if (!is_numeric($project_id) or !$p_id) {
    // Проект не найден, перенаправляем на главную страницу
    // !!! Добавить провреку на принадлежность проекта пользователю !!!
    header("Location: ./");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Ogani Template">
    <meta name="keywords" content="Ogani, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="./favicon.ico">
    <title>Детали проекта</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200;300;400;600;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./src/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="./src/css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="./src/css/elegant-icons.css" type="text/css">
    <link rel="stylesheet" href="./src/css/nice-select.css" type="text/css">
    <link rel="stylesheet" href="./src/css/jquery-ui.min.css" type="text/css">
    <link rel="stylesheet" href="./src/css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="./src/css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="./src/css/style.css" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<style>
    .product-image {
        margin-right: 15px;
    }

    .shoping__cart__item__clo span {
        font-size: 24px;
        color: #b2b2b2;
        cursor: pointer;
    }

    .shoping__cart__item__clo span:hover {
        color: #ff6347;
        cursor: pointer;
    }

    .quantity input {
        width: 50px;
        text-align: center;
    }

    .cart-input {
        width: 50px;
        padding: 5px;
        text-align: center;
        border-radius: 10px;
        border: 0.5px solid #3085d6;
    }
</style>

<body>

    <?php include './includes/header.php'; ?>

    <section class="shoping-cart spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <?php 
                    $projectItems = $query->getProjectItems($project_id);
                    if (!empty($projectItems)) { ?>
                        <div class="shoping__cart__table">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Обозначение на плане</th>
                                        <th>Внишний вид</th>
                                        <th>Продукт</th>
                                        <th>Продавец</th>
                                        <th>Габариты</th>
                                        <th>Фиксация</th>
                                        <th>Кол-во</th>
                                        <th>Цена</th>
                                        <th>Кто добавил</th>
                                        <th>Описание</th>
                                        <th>Статус</th>
                                        <th>Действия</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($projectItems as $item) { ?>
                                        <tr>
                                            <td class=schematic"><?php echo $item['schematic']; ?>TMP</td>
                                            <td class="shoping__cart__item">
                                                <img src="./src/images/products/<?php echo $query->getProductImages($item['product_id'])[0] ?>" alt="">
                                            </td>
                                            <td><?php echo $item['productName']; ?></td>
                                            <td><?php echo $item['sellerName']; ?></td>
                                            <td><?php echo $item['size']; ?></td>
                                            <td>Пол(TMP)</td>
                                            <td class="shoping__cart__quantity">
                                                <div class="quantity">
                                                    <input type="number" value="<?php echo $item['quantity']; ?>"
                                                        id="quantity_<?php echo $item['id']; ?>" class="cart-input" data-product-id="<?php echo $item['id']; ?>"
                                                        onchange="updateQuantity(<?php echo $item['id']; ?>)">
                                                </div>
                                            </td>
                                            <td><?php echo $item['price_current']; ?></td>
                                            <td><?php echo $item['whoAdded']; ?></td>
                                            <td>
                                                <textarea id="comments_<?php echo $item['id']; ?>" data-item-id="<?php echo $item['id']; ?>" onchange="commentsChange(<?php echo $item['id']; ?>)"><?php echo $item['comments']; ?></textarea>
                                            </td>
                                            <td>
                                                <input type="text" id="status_<?php echo $item['id']; ?>" data-item-id="<?php echo $item['id']; ?>" value="<?php echo $item['status']; ?>" onchange="statusChange(<?php echo $item['id']; ?>)">
                                            </td>
                                            <td class="shoping__cart__item__clo">
                                                <span onclick="removeCartItem(<?php echo $item['id']; ?>)"><i class="fas fa-trash-alt"></i></span>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } else { ?>
                        <div style="padding: 10vh 0;">
                            <p style="text-align: center; font-size:25px">Проект пуст</p>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>

    <?php include './includes/footer.php'; ?>

    <script src="./src/js/jquery-3.3.1.min.js"></script>
    <script src="./src/js/bootstrap.min.js"></script>
    <script src="./src/js/jquery.nice-select.min.js"></script>
    <script src="./src/js/jquery-ui.min.js"></script>
    <script src="./src/js/jquery.slicknav.js"></script>
    <script src="./src/js/mixitup.min.js"></script>
    <script src="./src/js/owl.carousel.min.js"></script>
    <script src="./src/js/main.js"></script>

    <script>
        function commentsChange(itemId) {
            var comments = document.getElementById("comments_" + itemId).value;
            var field = "comments";

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'update-project-item.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.send("item_id=" + itemId + "&field=" + field + "&value=" + comments.trim());

            xhr.onload = function () {
                if (xhr.status == 200) {
                    Swal.fire('Quantity Updated!', '', 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error!', 'Failed to update the quantity', 'error');
                }
            };
        }

        function statusChange(itemId) {
            var status = document.getElementById("status_" + itemId).value;
            var field = "status";

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'update-project-item.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.send("item_id=" + itemId + "&field=" + field + "&value=" + encodeURIComponent(status.trim()));

            xhr.onload = function () {
                if (xhr.status == 200) {
                    Swal.fire('Status Updated!', '', 'success');
                } else {
                    Swal.fire('Error!', 'Failed to update status', 'error');
                }
            };
        }

        function updateQuantity(itemId) {
            var quantity = document.getElementById("quantity_" + itemId).value;
            if (quantity < 1) {
                Swal.fire("Quantity must be at least 1!");
                return;
            }
            var field = "quantity";

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'update-project-item.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.send("item_id=" + itemId + "&field=" + field + "&value=" + quantity);

            xhr.onload = function () {
                if (xhr.status == 200) {
                    Swal.fire('Quantity Updated!', '', 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error!', 'Failed to update the quantity', 'error');
                }
            };
        }

        function removeCartItem(itemId) {
            Swal.fire({
                title: 'Do you want to remove this product?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, remove it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    var xhr = new XMLHttpRequest();
                    xhr.open('GET', 'remove_project_item.php?remove_item_id=' + itemId, true);
                    xhr.send();

                    xhr.onload = function () {
                        if (xhr.status == 200) {
                            Swal.fire({
                                title: 'Removed!',
                                text: 'The product was successfully removed from the cart.',
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: 'An error occurred: ' + xhr.statusText,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    };
                }
            });
        }
    </script>
</body>

</html>