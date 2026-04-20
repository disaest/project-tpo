<?php
session_start();
require_once '../components/connect.php';

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'] ?? 0;
$likes = [];

if ($user_id) {
    $query = "SELECT `recipe_id` FROM `user_likes` WHERE `user_id` = $user_id";
    $result = mysqli_query($conn, $query);
    
    while ($row = mysqli_fetch_array($result)) {
        $likes[] = (int)$row['recipe_id'];
    }
}

mysqli_close($conn);

echo json_encode(['likes' => $likes]);
?>