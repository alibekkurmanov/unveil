<?php
include 'check.php';

//$cartItems = $query->getCartItems($_SESSION['id']);
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
    <title>Shopping Cart</title>
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

<body>

    <?php include './includes/header.php'; ?>

    <section class="projects spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div><a onclick="addNewProject()">Новый проект(кликабельная кнопка)</a></div>
                    <div>-----</div> 
                    <?php 
                    $projectsList = $query->getProjectsList($_SESSION['id']);
                    if (!empty($projectsList)) { 
                        foreach ($projectsList as $project) { ?>
                        <div class="project">
                            <a href="project_details.php?project_id=<?php echo $project['id']; ?>">
                                <div class="project-name"><?php echo $project['name']; ?></div>
                                <div class="amount-products">Продуктов: <?php echo $project['totalItems'] ?? "0"; ?></div>
                                <div class="amount-users">Участников: <?php echo $project['totalUsers']; ?> </div>
                                <div class="location">Локация: <?php echo $project['location']; ?></div>
                                <div class="stage">Стадия: <?php echo $project['stage']; ?></div>
                            </a>
                        </div> 
                        <?php }
                    } ?> 
                </div>
                <div id="modal" class="modal">
                    <div class="modal-content">
                        <span class="close" onclick="closeModal()">&times;</span>

                        <h2>Новый проект</h2>

                        <form id="projectForm">
                            <div class="form-field">
                                <label>Название:</label>
                                <input type="text" class="textbox" required="true" maxlength="255" name="name" placeholder="Название">
                            </div>
                            <div class="form-field">
                                <label>Локация:</label>
                                <input type="text" class="textbox" maxlength="255" name="location" placeholder="Локация">
                            </div>
                            <div class="form-field">
                                <label>Стадия:</label>
                                <input type="text" class="textbox" maxlength="255" name="stage" placeholder="Стадия">
                            </div>
                            <div class="form-field">
                                <label>Тип проекта:</label>
                                <input type="text" class="textbox" maxlength="255" name="type" placeholder="Тип проекта">
                            </div>
                            <div class="form-field">
                                <label>Бюджет:</label>
                                <input type="text" class="textbox" maxlength="255" name="budget" placeholder="Бюджет">
                            </div>
                            <div class="form-field">
                                <label>Описание:</label>
                                <textarea type="text" class="textbox" maxlength="255" name="description" placeholder="Описание"></textarea>
                            </div>
                            <div class="form-field">
                                <label>Коментарий:</label>
                                <input type="text" class="textbox" maxlength="255" name="comment" placeholder="Коментарий">
                            </div>
                            <div class="form-field">
                                <button type="button" onclick="submitProject()">Создать проект</button>
                            </div>
                        </form>
                    </div>
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
        function addNewProject() {
            document.getElementById('modal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('modal').style.display = 'none';
        }

        function submitProject() {
            const form = document.getElementById('projectForm');
            const formData = new FormData(form);

            fetch("add_project.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                closeModal();
                form.reset();
            })
            .catch(error => console.error("Ошибка:", error));
        }

    </script>
</body>

</html>