<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../components/connect.php';

$is_logged_in = isset($_SESSION['user_id']);
$user_id = $_SESSION['user_id'] ?? 0;
$user_login = $_SESSION['user_login'] ?? '';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Главная</title>
    <link rel="stylesheet" href="../components/style.css">
    <script src="../components/header.js" defer></script>
    <script src="../components/footer.js" defer></script>
    <style>
        body {
            background-color: #FFDDB7;
        }
        
        .welcome-section {
            margin-bottom: 50px;
        }
        
        .favorites-section {
            width: 100%;
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 32px 40px 32px;
            box-sizing: border-box;
        }
        
        .favorites-header {
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
        
        .favorites-header p {
            color: #ffffff;
            font-size: 32px;
            font-family: "MPlus";
            margin: 0 auto;
            text-align: center;
            max-width: 1280px;
        }
        
        .favorites-grid {
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
        
        .no-favorites {
            background-color: #ffffff;
            border-radius: 16px;
            padding: 48px 32px;
            text-align: center;
            color: #5f5f5f;
            font-size: 22px;
            font-family: "MPlus";
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        
        .login-prompt {
            background-color: #ffffff;
            border-radius: 16px;
            padding: 40px;
            text-align: center;
            color: #5f5f5f;
            font-size: 20px;
            font-family: "MPlus";
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        
        .login-prompt a {
            color: rgb(240, 93, 30);
            text-decoration: none;
            font-weight: bold;
        }
        
        .login-prompt a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body data-logged-in="<?php echo $is_logged_in ? 'true' : 'false'; ?>" data-user-login="<?php echo safe($user_login); ?>">
    <my-header title="главное окно" link-text="категории" link-url="categories.php"></my-header>
    
    <main>
        <div class="welcome-section">
            <div class="welcome-text">
                <p>COOK GO! - ваш путеводитель в мире гастрономии. Благодаря нашему сайту вы сможете легко и удобно найти рецепт любого блюда!</p>
            </div>
        </div>
        
        <?php if ($is_logged_in): ?>
            <div class="favorites-section">
                <div class="favorites-header">
                    <p>ВАМ ПОНРАВИЛОСЬ</p>
                </div>
                
                <?php
                $query = "SELECT r.id, r.title, r.icon, r.description, r.likes, c.title as category_title, c.id as category_id
                    FROM user_likes ul
                    JOIN recipes r ON ul.recipe_id = r.id
                    JOIN categories c ON r.categories = c.id
                    WHERE ul.user_id = $user_id
                    ORDER BY ul.created_at DESC";
                
                $result = mysqli_query($conn, $query);
                
                if ($result && mysqli_num_rows($result) > 0) {
                    echo "<div class='favorites-grid'>";
                    
                    while ($recipe = mysqli_fetch_array($result)) {
                        $id = $recipe['id'];
                        $title = safe($recipe['title']);
                        $icon = safe($recipe['icon']);
                        $description = safe($recipe['description']);
                        $likes = $recipe['likes'];
                        $category_title = safe($recipe['category_title']);
                        $category_id = $recipe['category_id'];
                        
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
                                <div class='recipe-likes'>
                                    <span class='likes-count'>$likes</span>
                                    <img src='../images/ui/heart-filled.png' class='heart-icon-small' alt='♥'>
                                </div>
                            </div>
                        </a>
                        ";
                    }
                    
                    echo "</div>";
                } else {
                    echo "<div class='no-favorites'>У вас пока нет избранных рецептов</div>";
                }
                ?>
            </div>
        <?php else: ?>
            <div class="favorites-section">
                <div class="login-prompt">
                    <p><a href="login.php">Войдите</a> или <a href="register.php">зарегистрируйтесь</a>, чтобы сохранять понравившиеся рецепты!</p>
                </div>
            </div>
        <?php endif; ?>
    </main>
    
    <my-footer></my-footer>
</body>
</html>