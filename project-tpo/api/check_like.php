<?php
session_start();
require_once '../components/connect.php';

header('Content-Type: application/json');

$recipe_id = $_GET['recipe_id'] ?? 0;
$user_id = $_SESSION['user_id'] ?? 0;

$liked = false;

if ($user_id && $recipe_id) {
    $query = "SELECT `id` FROM `user_likes` WHERE `user_id` = $user_id AND `recipe_id` = $recipe_id";
    $result = mysqli_query($conn, $query);
    $liked = mysqli_num_rows($result) > 0;
}

mysqli_close($conn);

echo json_encode(['liked' => $liked]);
?>