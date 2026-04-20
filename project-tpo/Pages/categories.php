<?php
session_start();
$is_logged_in = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Категории</title>
    <link rel="stylesheet" href="../components/style.css">
    <script src="../components/header.js" defer></script>
    <script src="../components/footer.js" defer></script>
    <style>
        body {
            background-color: #FFDDB7;
        }

        .categories-container {
            background-color: #ffffff;
            width: 100%;
            max-width: 1280px;
            margin: 32px auto;
            padding: 32px;
            border-radius: 16px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
        }

        .search-wrapper {
            margin-bottom: 24px;
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

        .categories-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 40px;
            justify-items: center;
        }

        .category-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
            background-color: #ffffff;
            width: 100%;
            max-width: 400px;
        }

        .category-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .category-title {
            width: 100%;
            background-color: rgb(240, 93, 30);
            padding: 14px 0;
            text-align: center;
        }

        .category-title p {
            margin: 0;
            color: #ffffff;
            font-size: 26px;
            font-family: "MPlus";
            font-weight: bold;
        }

        .category-image {
            width: 100%;
            height: 320px;
            overflow: hidden;
            background-color: #f5f5f5;
        }

        .category-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .no-categories {
            text-align: center;
            color: #5f5f5f;
            font-size: 19px;
            font-family: "MPlus";
            padding: 32px;
            grid-column: span 2;
        }

        .search-info {
            margin-bottom: 16px;
            color: #5f5f5f;
            font-size: 16px;
            font-family: "MPlus";
        }
    </style>
</head>
<body data-logged-in="<?php echo $is_logged_in ? 'true' : 'false'; ?>">
    <my-header title="категории" link-text="главное окно" link-url="main.php"></my-header>
    
    <main>
        <div class="categories-container">
            <?php
            require_once '../components/connect.php';
            
            $search = trim($_GET['search'] ?? '');
            ?>
            
            <div class="search-wrapper">
                <form method="GET" action="" class="search-form">
                    <input type="text" name="search" class="search-input" placeholder="Поиск по категориям..." value="<?php echo safe($search); ?>">
                    <button type="submit" class="search-btn">Поиск</button>
                    <?php if (!empty($search)): ?>
                        <a href="categories.php" class="reset-btn">Сбросить</a>
                    <?php endif; ?>
                </form>
            </div>
            
            <?php
            if (!$conn) {
                echo "<div class='no-categories'>Ошибка подключения к базе данных</div>";
            } else {
                $query = "SELECT * FROM `categories`";
                if (!empty($search)) {
                    $search_escaped = mysqli_real_escape_string($conn, $search);
                    $query .= " WHERE `title` LIKE '%$search_escaped%'";
                }
                $query .= " ORDER BY `id` ASC";
                
                $result = mysqli_query($conn, $query);
                $count = mysqli_num_rows($result);
                
                if (!empty($search)) {
                    echo "<div class='search-info'>Найдено категорий: $count</div>";
                }
                
                if ($count > 0) {
                    echo "<div class='categories-grid'>";
                    
                    while ($category = mysqli_fetch_array($result)) {
                        $id = $category['id'];
                        $title = safe($category['title']);
                        $icon = safe($category['icon']);
                        
                        echo "
                        <a href='recipes.php?category_id=$id' class='category-card'>
                            <div class='category-title'>
                                <p>$title</p>
                            </div>
                            <div class='category-image'>
                                <img src='../images/categories/$icon' alt='$title'>
                            </div>
                        </a>
                        ";
                    }
                    
                    echo "</div>";
                } else {
                    echo "<div class='no-categories'>";
                    if (!empty($search)) {
                        echo "По запросу «" . safe($search) . "» ничего не найдено";
                    } else {
                        echo "Категории не добавлены";
                    }
                    echo "</div>";
                }
                
                mysqli_close($conn);
            }
            ?>
        </div>
    </main>
    
    <my-footer></my-footer>
</body>
</html>