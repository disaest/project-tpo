<?php
session_start();
$is_logged_in = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Карточка рецепта</title>
    <link rel="stylesheet" href="../components/style.css">
    <script src="../components/header.js" defer></script>
    <script src="../components/footer.js" defer></script>
    <style>
        body {
            background-color: #FFDDB7;
        }

        .recipe-container {
            width: 100%;
            max-width: 1280px;
            margin: 32px auto;
            padding: 0 32px 32px 32px;
            box-sizing: border-box;
        }

        .recipe-card-full {
            background-color: #ffffff;
            border-radius: 24px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            padding: 40px;
            overflow: hidden;
        }

        .recipe-main-image {
            width: 480px;
            height: 480px;
            margin: 0 auto 32px auto;
            border-radius: 24px;
            overflow: hidden;
        }

        .recipe-main-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .recipe-header-wrapper {
            display: flex;
            align-items: flex-start;
            justify-content: center;
            position: relative;
            margin-bottom: 32px;
        }

        .recipe-text-content {
            text-align: center;
            max-width: 800px;
        }

        .recipe-title-full {
            color: rgb(240, 93, 30);
            font-size: 38px;
            font-family: "MPlus";
            margin: 0 0 16px 0;
            text-align: center;
        }

        .recipe-description-full {
            color: #5f5f5f;
            font-size: 19px;
            font-family: "MPlus";
            line-height: 1.6;
            margin: 0;
            text-align: center;
        }

        .recipe-likes {
            display: flex;
            align-items: center;
            gap: 12px;
            position: absolute;
            right: 0;
            top: 0;
        }

        .likes-count {
            color: #5f5f5f;
            font-size: 26px;
            font-family: "MPlus";
        }

        .like-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px;
            transition: transform 0.2s;
        }

        .like-btn:hover {
            transform: scale(1.1);
        }

        .like-btn img {
            width: 36px;
            height: 36px;
        }

        .recipe-content-row {
            display: flex;
            gap: 32px;
            margin-bottom: 40px;
        }

        .ingredients-box {
            flex: 2;
            background-color: #f0f0f0;
            padding: 28px;
            border-radius: 16px;
        }

        .ingredients-title {
            color: #5f5f5f;
            font-size: 22px;
            font-family: "MPlus";
            margin: 0 0 16px 0;
        }

        .ingredients-list {
            color: #5f5f5f;
            font-size: 16px;
            font-family: "MPlus";
            line-height: 2;
            margin: 0;
            white-space: pre-line;
        }

        .meta-info-box {
            flex: 1;
            padding: 28px 0;
        }

        .meta-item {
            margin-bottom: 24px;
        }

        .meta-label {
            color: #5f5f5f;
            font-size: 16px;
            font-family: "MPlus";
            margin: 0 0 6px 0;
        }

        .meta-value {
            color: #000000;
            font-size: 26px;
            font-family: "MPlus";
            margin: 0;
        }

        .recipe-section-header {
            background-color: rgb(240, 93, 30);
            padding: 16px 0;
            margin: 0 -40px 32px -40px;
            width: calc(100% + 80px);
        }

        .recipe-section-header p {
            color: #ffffff;
            font-size: 29px;
            font-family: "MPlus";
            margin: 0;
            text-align: center;
        }

        .recipe-steps {
            display: flex;
            flex-direction: column;
            gap: 40px;
        }

        .step-item {
            display: flex;
            flex-direction: column;
        }

        .step-image {
            width: 80%;
            height: 360px;
            margin: 0 auto 16px auto;
            border-radius: 16px;
            overflow: hidden;
        }

        .step-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .step-text {
            color: #5f5f5f;
            font-size: 18px;
            font-family: "MPlus";
            line-height: 1.7;
            margin: 0;
            white-space: pre-line;
        }

        .recipe-not-found {
            background-color: #ffffff;
            border-radius: 24px;
            padding: 64px 40px;
            text-align: center;
            color: #5f5f5f;
            font-size: 26px;
            font-family: "MPlus";
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .back-link {
            display: inline-block;
            margin-top: 24px;
            color: #5f5f5f;
            font-size: 19px;
            font-family: "MPlus";
            text-decoration: none;
            padding: 12px 24px;
            background-color: #ffffff;
            border-radius: 12px;
            transition: background-color 0.2s;
        }

        .back-link:hover {
            background-color: rgb(240, 93, 30);
            color: #ffffff;
        }
        
        .toast-notification {
            position: fixed;
            top: 30px;
            right: 30px;
            background-color: rgb(240, 93, 30);
            color: #ffffff;
            padding: 20px 30px;
            border-radius: 15px;
            font-size: 22px;
            font-family: "MPlus";
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            animation: slideIn 0.3s ease;
        }
        
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    </style>
</head>
<body data-logged-in="<?php echo $is_logged_in ? 'true' : 'false'; ?>">
    <?php
    require_once '../components/connect.php';
    
    $recipe_id = $_GET['recipe_id'] ?? 0;
    $category_id = $_GET['category_id'] ?? 0;
    $category_title = safe(urldecode($_GET['category_title'] ?? 'категория'));
    
    $recipe_query = "SELECT * FROM `recipes` WHERE `id` = $recipe_id";
    $recipe_result = mysqli_query($conn, $recipe_query);
    $recipe = mysqli_fetch_array($recipe_result);
    
    if ($recipe) {
        $recipe_title = safe($recipe['title']);
        $recipe_icon = safe($recipe['icon']);
        $recipe_description = safe($recipe['description']);
        $recipe_likes = $recipe['likes'];
        
        $card_query = "SELECT * FROM `card_recipes` WHERE `id` = $recipe_id";
        $card_result = mysqli_query($conn, $card_query);
        $card_recipe = mysqli_fetch_array($card_result);
        
        if ($card_recipe) {
            $ingredients = safe($card_recipe['ingridients']);
            $portions = safe($card_recipe['portions']);
            $time = safe($card_recipe['time_for_cook']);
            $tutorial = safe($card_recipe['tutorial']);
            $images_string = $card_recipe['images'] ?? '';
        } else {
            $ingredients = 'Ингредиенты не указаны';
            $portions = 'Не указано';
            $time = 'Не указано';
            $tutorial = 'Инструкция пока не добавлена';
            $images_string = '';
        }
    } else {
        $recipe_title = 'рецепт';
        $recipe_icon = 'no-photo.jpg';
        $recipe_description = '';
        $recipe_likes = 0;
        $ingredients = '';
        $portions = '';
        $time = '';
        $tutorial = '';
        $images_string = '';
    }
    
    mysqli_close($conn);
    ?>
    
    <my-header title="<?php echo $recipe_title; ?>" link-text="<?php echo $category_title; ?>" link-url="recipes.php?category_id=<?php echo $category_id; ?>"></my-header>
    
    <main>
        <div class="recipe-container">
            <?php if ($recipe_id > 0 && $recipe): ?>
                <div class="recipe-card-full">
                    <div class="recipe-main-image">
                        <img src="../images/recipes/<?php echo $recipe_icon; ?>" alt="<?php echo $recipe_title; ?>">
                    </div>
                    
                    <div class="recipe-header-wrapper">
                        <div class="recipe-text-content">
                            <h1 class="recipe-title-full"><?php echo $recipe_title; ?></h1>
                            <p class="recipe-description-full"><?php echo $recipe_description; ?></p>
                        </div>
                        <div class="recipe-likes">
                            <span class="likes-count" id="likes-count"><?php echo $recipe_likes; ?></span>
                            <button class="like-btn" id="like-btn" data-recipe-id="<?php echo $recipe_id; ?>" data-liked="false">
                                <img src="../images/ui/heart-empty.png" alt="♥" id="heart-icon">
                            </button>
                        </div>
                    </div>
                    
                    <div class="recipe-content-row">
                        <div class="ingredients-box">
                            <h3 class="ingredients-title">Ингредиенты:</h3>
                            <p class="ingredients-list"><?php echo $ingredients; ?></p>
                        </div>
                        <div class="meta-info-box">
                            <div class="meta-item">
                                <p class="meta-label">Порций:</p>
                                <p class="meta-value"><?php echo $portions; ?></p>
                            </div>
                            <div class="meta-item">
                                <p class="meta-label">Время приготовления:</p>
                                <p class="meta-value"><?php echo $time; ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="recipe-section-header">
                        <p>РЕЦЕПТ</p>
                    </div>
                    
                    <div class="recipe-steps">
                        <?php
                        $step_images = !empty($images_string) ? explode(',', $images_string) : [];
                        $steps = explode("\n", $tutorial);
                        $step_number = 1;
                        
                        foreach ($steps as $index => $step):
                            $step = trim($step);
                            if (!empty($step)):
                                $step_image = isset($step_images[$index]) ? trim($step_images[$index]) : '';
                        ?>
                            <div class="step-item">
                                <?php if (!empty($step_image)): ?>
                                    <div class="step-image">
                                        <img src="../images/steps/<?php echo $step_image; ?>" alt="Шаг <?php echo $step_number; ?>">
                                    </div>
                                <?php endif; ?>
                                <p class="step-text"><?php echo $step; ?></p>
                            </div>
                        <?php 
                                $step_number++;
                            endif;
                        endforeach; 
                        ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="recipe-not-found">
                    <p>Рецепт не найден</p>
                    <a href="categories.php" class="back-link">← Вернуться к категориям</a>
                </div>
            <?php endif; ?>
        </div>
    </main>
    
    <my-footer></my-footer>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const likeBtn = document.getElementById('like-btn');
            const heartIcon = document.getElementById('heart-icon');
            const likesCount = document.getElementById('likes-count');
            const isLoggedIn = document.body.dataset.loggedIn === 'true';
            
            if (likeBtn) {
                const recipeId = likeBtn.dataset.recipeId;
                
                if (!isLoggedIn) {
                    let baseLikes = parseInt(likesCount.textContent);
                    likesCount.textContent = baseLikes;
                    heartIcon.src = '../images/ui/heart-empty.png';
                    likeBtn.dataset.liked = 'false';
                }
                
                if (isLoggedIn) {
                    fetch('../api/check_like.php?recipe_id=' + recipeId)
                        .then(response => response.json())
                        .then(data => {
                            let baseLikes = parseInt(likesCount.textContent);
                            if (data.liked) {
                                likeBtn.dataset.liked = 'true';
                                heartIcon.src = '../images/ui/heart-filled.png';
                                likesCount.textContent = baseLikes;
                            } else {
                                likeBtn.dataset.liked = 'false';
                                heartIcon.src = '../images/ui/heart-empty.png';
                                likesCount.textContent = baseLikes;
                            }
                        });
                }
                
                likeBtn.addEventListener('click', function() {
                    if (!isLoggedIn) {
                        const toast = document.createElement('div');
                        toast.className = 'toast-notification';
                        toast.textContent = 'Чтобы ставить лайки, нужно войти в аккаунт!';
                        document.body.appendChild(toast);
                        
                        setTimeout(function() {
                            toast.remove();
                        }, 3000);
                        return;
                    }
                    
                    const isLiked = likeBtn.dataset.liked === 'true';
                    let currentLikes = parseInt(likesCount.textContent);
                    const action = isLiked ? 'unlike' : 'like';
                    
                    fetch('../api/toggle_like.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'recipe_id=' + recipeId + '&action=' + action
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            if (isLiked) {
                                likesCount.textContent = currentLikes - 1;
                                heartIcon.src = '../images/ui/heart-empty.png';
                                likeBtn.dataset.liked = 'false';
                            } else {
                                likesCount.textContent = currentLikes + 1;
                                heartIcon.src = '../images/ui/heart-filled.png';
                                likeBtn.dataset.liked = 'true';
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Ошибка:', error);
                    });
                });
            }
        });
    </script>
</body>
</html>