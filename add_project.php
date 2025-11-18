<?php
include 'check.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_SESSION['id'];

    $name = $_POST['name'] ?? '';
    $location = $_POST['location'] ?? '';
    $stage = $_POST['stage'] ?? '';
    $type = $_POST['type'] ?? '';
    $budget = $_POST['budget'] ?? '';
    $description = $_POST['description'] ?? '';
    $comment = $_POST['comment'] ?? '';

    $projectData = array(
        'name' => $name,
        'location' => $location,
        'stage' => $stage,
        'type' => $type,
        'budget' => $budget,
        'description' => $description,
        'comment' => $comment
    );

    $project_id = $query->lastInsertId('project', $projectData);

    if ($project_id) {
        $projectsData = array(
            'user_id' => $userId,
            'project_id' => $project_id
        );

        $query->insert('projects', $projectsData);
    }

    echo "Проект '$name' успешно создан!";
} else {
    echo "Ошибка";
}
?>