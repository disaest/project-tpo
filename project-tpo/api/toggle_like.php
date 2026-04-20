<?php
session_start();
require_once '../components/connect.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if (!isset($_SESSION['user_id'])) {
    $response['message'] = 'Требуется авторизация';
    echo json_encode($response);
    exit;
}

$user_id = $_SESSION['user_id'];
$recipe_id = $_POST['recipe_id'] ?? 0;
$action = $_POST['action'] ?? '';

if (!$recipe_id || !in_array($action, ['like', 'unlike'])) {
    $response['message'] = 'Неверные параметры';
    echo json_encode($response);
    exit;
}

if ($action === 'like') {
    $query = "INSERT IGNORE INTO `user_likes` (`user_id`, `recipe_id`) VALUES ($user_id, $recipe_id)";
    mysqli_query($conn, $query);
    
    mysqli_query($conn, "UPDATE `recipes` SET `likes` = `likes` + 1 WHERE `id` = $recipe_id");
    mysqli_query($conn, "UPDATE `card_recipes` SET `likes` = `likes` + 1 WHERE `id` = $recipe_id");
    
    $response['success'] = true;
    $response['message'] = 'Лайк добавлен';
} else {
    $query = "DELETE FROM `user_likes` WHERE `user_id` = $user_id AND `recipe_id` = $recipe_id";
    mysqli_query($conn, $query);
    
    mysqli_query($conn, "UPDATE `recipes` SET `likes` = GREATEST(`likes` - 1, 0) WHERE `id` = $recipe_id");
    mysqli_query($conn, "UPDATE `card_recipes` SET `likes` = GREATEST(`likes` - 1, 0) WHERE `id` = $recipe_id");
    
    $response['success'] = true;
    $response['message'] = 'Лайк убран';
}

mysqli_close($conn);
echo json_encode($response);
?>