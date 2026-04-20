<?php
session_start();
require_once '../components/connect.php';

if (isset($_SESSION['user_id'])) {
    header('Location: main.php');
    exit;
}

$error = '';
$is_logged_in = isset($_SESSION['user_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';
    
    if (empty($login)) {
        $error = 'Введите логин';
    } elseif (strlen($login) < 3) {
        $error = 'Логин должен быть не менее 3 символов';
    } elseif (strlen($login) > 50) {
        $error = 'Логин должен быть не более 50 символов';
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $login)) {
        $error = 'Логин может содержать только латинские буквы, цифры и знак подчёркивания';
    } elseif (empty($password)) {
        $error = 'Введите пароль';
    } elseif (strlen($password) < 4) {
        $error = 'Пароль должен быть не менее 4 символов';
    } elseif ($password !== $password_confirm) {
        $error = 'Пароли не совпадают';
    } else {
        $check_query = "SELECT `id` FROM `users` WHERE `login` = '" . mysqli_real_escape_string($conn, $login) . "'";
        $check_result = mysqli_query($conn, $check_query);
        
        if (mysqli_num_rows($check_result) > 0) {
            $error = 'Пользователь с таким логином уже существует';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert_query = "INSERT INTO `users` (`login`, `pass`) VALUES ('" . mysqli_real_escape_string($conn, $login) . "', '$hashed_password')";
            
            if (mysqli_query($conn, $insert_query)) {
                mysqli_close($conn);
                header('Location: login.php?registered=1');
                exit;
            } else {
                $error = 'Ошибка при регистрации. Попробуйте позже.';
            }
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
    <title>Регистрация</title>
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
    </style>
</head>
<body data-logged-in="<?php echo $is_logged_in ? 'true' : 'false'; ?>" data-user-login="<?php echo safe($_SESSION['user_login'] ?? ''); ?>">
    <my-header title="регистрация" link-text="главное окно" link-url="main.php"></my-header>
    
    <main>
        <div class="form-wrapper">
            <div class="form-header">
                <p>Регистрация</p>
            </div>
            <div class="form-container">
                <?php if ($error): ?>
                    <div class="error-message"><?php echo safe($error); ?></div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="login">Логин</label>
                        <input type="text" id="login" name="login" placeholder="Придумайте логин" value="<?php echo safe($_POST['login'] ?? ''); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Пароль</label>
                        <input type="password" id="password" name="password" placeholder="Придумайте пароль" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password_confirm">Подтверждение пароля</label>
                        <input type="password" id="password_confirm" name="password_confirm" placeholder="Повторите пароль" required>
                    </div>
                    
                    <button type="submit" class="submit-btn">Зарегистрироваться</button>
                </form>
                
                <div class="form-link">
                    <a href="login.php">Уже есть аккаунт? Войти</a>
                </div>
            </div>
        </div>
    </main>
    
    <my-footer></my-footer>
</body>
</html>