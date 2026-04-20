<?php
session_start();
require_once '../components/connect.php';

$error = '';
$just_logged_out = $_GET['logout'] ?? '';
$just_registered = $_GET['registered'] ?? '';
$is_logged_in = isset($_SESSION['user_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($login) || empty($password)) {
        $error = 'Введите логин и пароль';
    } else {
        $query = "SELECT `id`, `login`, `pass` FROM `users` WHERE `login` = '" . mysqli_real_escape_string($conn, $login) . "'";
        $result = mysqli_query($conn, $query);
        $user = mysqli_fetch_array($result);
        
        if ($user && password_verify($password, $user['pass'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_login'] = $user['login'];
            mysqli_close($conn);
            header('Location: main.php');
            exit;
        } else {
            $error = 'Неверный логин или пароль';
        }
    }
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход</title>
    <link rel="stylesheet" href="../components/style.css">
    <script src="../components/header.js" defer></script>
    <script src="../components/footer.js" defer></script>
    <style>
        body {
            background-color: #5F5F5F;
        }
        
        .form-wrapper {
            width: 800px;
            margin: 60px auto;
        }
        
        .form-header {
            background-color: rgb(240, 93, 30);
            padding: 10px 30px;
            text-align: left;
            border-radius: 0;
        }
        
        .form-header p {
            color: #ffffff;
            font-size: 28px;
            font-family: "MPlus";
            margin: 0;
            text-transform: none;
        }
        
        .form-container {
            padding: 40px;
            background-color: #D9D9D9;
            border-radius: 0;
            box-sizing: border-box;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            color: #000000;
            font-size: 18px;
            font-family: "MPlus";
            margin-bottom: 8px;
        }
        
        .form-group input {
            width: 100%;
            padding: 14px 18px;
            font-size: 16px;
            font-family: "MPlus";
            color: #000000;
            background-color: #ffffff;
            border: none;
            border-radius: 0;
            box-sizing: border-box;
            outline: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }
        
        .form-group input::placeholder {
            color: #888;
        }
        
        .form-group:last-of-type {
            margin-bottom: 45px;
        }
        
        .submit-btn {
            width: 300px;
            padding: 10px;
            background-color: rgb(240, 93, 30);
            color: #ffffff;
            font-size: 20px;
            font-family: "MPlus";
            border: none;
            border-radius: 30px;
            cursor: pointer;
            transition: background-color 0.2s;
            margin: 0 auto;
            display: block;
        }
        
        .submit-btn:hover {
            background-color: #d44e1a;
        }
        
        .form-link {
            text-align: center;
            margin-top: 20px;
        }
        
        .form-link a {
            color: #5F5F5F;
            font-size: 16px;
            font-family: "MPlus";
            text-decoration: none;
        }
        
        .form-link a:hover {
            text-decoration: underline;
            color: #4a4a4a;
        }
        
        .error-message {
            background-color: rgba(255, 200, 200, 0.9);
            color: #c00;
            padding: 12px 15px;
            border-radius: 0;
            margin-bottom: 20px;
            font-size: 16px;
            font-family: "MPlus";
            text-align: center;
        }
        
        .already-logged-message {
            background-color: #ffffff;
            color: #000000;
            padding: 20px;
            border-radius: 0;
            margin-bottom: 20px;
            font-size: 18px;
            font-family: "MPlus";
            text-align: center;
        }
        
        .success-message {
            background-color: #ffffff;
            color: #000000;
            padding: 20px;
            border-radius: 0;
            margin-bottom: 20px;
            font-size: 18px;
            font-family: "MPlus";
            text-align: center;
        }
        
        .logout-link {
            text-align: center;
            margin-top: 20px;
        }
        
        .logout-link a {
            color: #000000;
            font-size: 16px;
            font-family: "MPlus";
            text-decoration: none;
            border-bottom: 1px dashed #888;
        }
        
        .logout-link a:hover {
            color: rgb(240, 93, 30);
            border-bottom-color: rgb(240, 93, 30);
        }
    </style>
</head>
<body data-logged-in="<?php echo $is_logged_in ? 'true' : 'false'; ?>" data-user-login="<?php echo safe($_SESSION['user_login'] ?? ''); ?>">
    <my-header title="вход" link-text="главное окно" link-url="main.php"></my-header>
    
    <main>
        <div class="form-wrapper">
            <div class="form-header">
                <p>Вход</p>
            </div>
            <div class="form-container">
                <?php if ($just_registered == '1'): ?>
                    <div class="success-message">Регистрация успешна! Теперь вы можете войти.</div>
                <?php endif; ?>
                
                <?php if ($just_logged_out == '1'): ?>
                    <div class="already-logged-message">Вы вышли из аккаунта</div>
                <?php endif; ?>
                
                <?php if ($is_logged_in): ?>
                    <div class="already-logged-message">
                        Вы уже вошли как <strong><?php echo safe($_SESSION['user_login']); ?></strong>
                    </div>
                    <div class="logout-link">
                        <a href="logout.php">Хотите выйти?</a>
                    </div>
                <?php else: ?>
                    <?php if ($error): ?>
                        <div class="error-message"><?php echo safe($error); ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="login">Логин</label>
                            <input type="text" id="login" name="login" placeholder="Введите логин" value="<?php echo safe($_POST['login'] ?? ''); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="password">Пароль</label>
                            <input type="password" id="password" name="password" placeholder="Введите пароль" required>
                        </div>
                        
                        <button type="submit" class="submit-btn">Войти</button>
                    </form>
                    
                    <div class="form-link">
                        <a href="register.php">Нет аккаунта? Зарегистрироваться</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
    
    <my-footer></my-footer>
</body>
</html>