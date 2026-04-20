<?php
session_start();
$is_logged_in = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Рецепты</title>
    <link rel="stylesheet" href="../components/style.css">
    <script src="../components/header.js" defer></script>
    <script src="../components/footer.js" defer></script>
    <style>
        body {
            background-color: #FFDDB7;
        }

        .recipes-container {
            width: 100%;
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 32px 32px 32px;
            box-sizing: border-box;
        }

        .category-header {
            background-color: rgb(240, 93, 30);
            padding: 16px 0;
            margin: 0 0 32px 0;
            width: 100vw;
            position: relative;
            left: 50%;
            right: 50%;
            margin-left: -50vw;
            margin-right: -50vw;
        }

        .category-header p {
            color: #ffffff;
            font-size: 32px;
            font-family: "MPlus";
            margin: 0 auto;
            text-align: center;
            max-width: 1280px;
        }

        .search-wrapper {
            margin-bottom: 24px;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 16px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .search-form {
            display: flex;
            gap: 8px;
        }

        .search-input {
            flex: 1;
            padding: 13px 16px;
            font-size: 16px;
            font-family: "MPlus";
            color: #333;
            background-color: #f5f5f5;
            border: 2px solid #e0e0e0;
            border-radius: 32px;
            outline: none;
        }

        .search-input:focus {
            border-color: rgb(240, 93, 30);
        }

        .search-btn {
            padding: 13px 32px;
            background-color: rgb(240, 93, 30);
            color: #ffffff;
            font-size: 16px;
            font-family: "MPlus";
            border: none;
            border-radius: 32px;
            cursor: pointer;
        }

        .search-btn:hover {
            background-color: #d44e1a;
        }

        .reset-btn {
            padding: 13px 24px;
            background-color: #5f5f5f;
            color: #ffffff;
            font-size: 16px;
            font-family: "MPlus";
            border: none;
            border-radius: 32px;
            cursor: pointer;
            text-decoration: none;
        }

        .reset-btn:hover {
            background-color: #4a4a4a;
        }

        .search-info {
            margin-top: 12px;
            color: #5f5f5f;
            font-size: 14px;
            font-family: "MPlus";
        }

        .recipes-grid {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .recipe-card {
            display: flex;
            background-color: #ffffff;
            border-radius: 16px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            text-decoration: none;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .recipe-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
        }

        .recipe-image {
            width: 320px;
            height: 240px;
            flex-shrink: 0;
            background-color: #f5f5f5;
        }

        .recipe-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .recipe-info {
            padding: 24px 32px;
            display: flex;
            flex: 1;
            gap: 24px;
        }

        .recipe-text {
            flex: 1;
        }

        .recipe-title {
            color: rgb(240, 93, 30);
            font-size: 26px;
            font-family: "MPlus";
            margin: 0 0 12px 0;
        }

        .recipe-description {
            color: #5f5f5f;
            font-size: 16px;
            font-family: "MPlus";
            margin: 0;
            line-height: 1.5;
        }

        .recipe-likes {
            display: flex;
            align-items: center;
            gap: 8px;
            align-self: flex-start;
        }

        .likes-count {
            color: #5f5f5f;
            font-size: 19px;
            font-family: "MPlus";
        }

        .heart-icon-small {
            width: 26px;
            height: 26px;
        }

        .no-recipes {
            background-color: #ffffff;
            border-radius: 16px;
            padding: 48px 32px;
            text-align: center;
            color: #5f5f5f;
            font-size: 22px;
            font-family: "MPlus";
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body data-logged-in="<?php echo $is_logged_in ? 'true' : 'false'; ?>">
    <?php
    require_once '../components/connect.php';
    
    $category_id = $_GET['category_id'] ?? 0;
    $search = trim($_GET['search'] ?? '');
    
    $cat_query = "SELECT `title` FROM `categories` WHERE `id` = $category_id";
    $cat_result = mysqli_query($conn, $cat_query);
    $category = mysqli_fetch_array($cat_result);
    $category_title = safe($category['title'] ?? 'категория');
    ?>
    
    <my-header title="<?php echo $category_title; ?>" link-text="категории" link-url="categories.php"></my-header>
    
    <main>
        <div class="recipes-container">
            <div class="category-header">
                <p><?php echo $category_title; ?></p>
            </div>
            
            <div class="search-wrapper">
                <form method="GET" action="" class="search-form">
                    <input type="hidden" name="category_id" value="<?php echo $category_id; ?>">
                    <input type="text" name="search" class="search-input" placeholder="Поиск по рецептам в этой категории..." value="<?php echo safe($search); ?>">
                    <button type="submit" class="search-btn">Поиск</button>
                    <?php if (!empty($search)): ?>
                        <a href="recipes.php?category_id=<?php echo $category_id; ?>" class="reset-btn">Сбросить</a>
                    <?php endif; ?>
                </form>
                <?php if (!empty($search)): ?>
                    <div class="search-info">Поиск: «<?php echo safe($search); ?>»</div>
                <?php endif; ?>
            </div>
            
            <?php
            $query = "SELECT * FROM `recipes` WHERE `categories` = $category_id";
            if (!empty($search)) {
                $search_escaped = mysqli_real_escape_string($conn, $search);
                $query .= " AND (`title` LIKE '%$search_escaped%' OR `description` LIKE '%$search_escaped%')";
            }
            $query .= " ORDER BY `id` DESC";
            
            $result = mysqli_query($conn, $query);
            $count = mysqli_num_rows($result);
            
            if ($count > 0) {
                echo "<div class='recipes-grid'>";
                
                while ($recipe = mysqli_fetch_array($result)) {
                    $id = $recipe['id'];
                    $title = safe($recipe['title']);
                    $icon = safe($recipe['icon']);
                    $description = safe($recipe['description']);
                    $likes = $recipe['likes'];
                    
                    $category_title_encoded = urlencode($category_title);
                    
                    echo "
                    <a href='recipe_card.php?recipe_id=$id&category_id=$category_id&category_title=$category_title_encoded' class='recipe-card'>
                        <div class='recipe-image'>
                            <img src='../images/recipes/$icon' alt='$title'>
                        </div>
                        <div class='recipe-info'>
                            <div class='recipe-text'>
                                <h2 class='recipe-title'>$title</h2>
                                <p class='recipe-description'>$description</p>
                            </div>
                            <div class='recipe-likes' data-recipe-id='$id'>
                                <span class='likes-count' id='likes-$id'>$likes</span>
                                <img src='../images/ui/heart-empty.png' class='heart-icon-small' id='heart-$id' alt='♥'>
                            </div>
                        </div>
                    </a>
                    ";
                }
                
                echo "</div>";
            } else {
                echo "<div class='no-recipes'>";
                if (!empty($search)) {
                    echo "По запросу «" . safe($search) . "» рецептов не найдено";
                } else {
                    echo "В этой категории пока нет рецептов";
                }
                echo "</div>";
            }
            
            mysqli_close($conn);
            ?>
        </div>
    </main>
    
    <my-footer></my-footer>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const isLoggedIn = document.body.dataset.loggedIn === 'true';
            
            if (!isLoggedIn) {
                document.querySelectorAll('.recipe-likes').forEach(function(likesContainer) {
                    const recipeId = likesContainer.dataset.recipeId;
                    const likesSpan = document.getElementById('likes-' + recipeId);
                    const heartImg = document.getElementById('heart-' + recipeId);
                    
                    if (likesSpan && heartImg) {
                        let baseLikes = parseInt(likesSpan.textContent);
                        likesSpan.textContent = baseLikes;
                        heartImg.src = '../images/ui/heart-empty.png';
                    }
                });
            } else {
                fetch('../api/get_user_likes.php')
                    .then(response => response.json())
                    .then(data => {
                        const likedRecipes = data.likes || [];
                        
                        document.querySelectorAll('.recipe-likes').forEach(function(likesContainer) {
                            const recipeId = likesContainer.dataset.recipeId;
                            const likesSpan = document.getElementById('likes-' + recipeId);
                            const heartImg = document.getElementById('heart-' + recipeId);
                            
                            if (likesSpan && heartImg) {
                                let baseLikes = parseInt(likesSpan.textContent);
                                
                                if (likedRecipes.includes(parseInt(recipeId))) {
                                    likesSpan.textContent = baseLikes;
                                    heartImg.src = '../images/ui/heart-filled.png';
                                } else {
                                    likesSpan.textContent = baseLikes;
                                    heartImg.src = '../images/ui/heart-empty.png';
                                }
                            }
                        });
                    });
            }
        });
    </script>
</body>
</html>