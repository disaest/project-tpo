<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход</title>

    <link rel="stylesheet" href="components/style.css">

    <script src="components/header.js" defer></script>
    <script src="components/footer.js" defer></script>
</head>
<body>

    <my-header></my-header>

    <main>
        <?php
        session_start();

        $host = 'localhost';
        $user = 'root';
        $pass = '';
        $base = 'busk_baza';

        $conn = mysqli_connect($host, $user, $pass, $base);

        $a = $_GET['subject'] ?? '';
        $b = $_GET['subject_pass'] ?? '';

        if ($a && $b) {
            $query = "SELECT * FROM `users` WHERE `login`='$a' AND `pass`='$b'";
            $result = mysqli_query($conn, $query);
            $qwe = mysqli_fetch_array($result);

            if ($qwe) {
                $_SESSION['user_name'] = $qwe[1];
                $_SESSION['user_pass'] = $qwe[2];
                echo "Вы вошли в аккаунт!";
            } else {
                echo "Такого аккаунта не существует";
            }
        }
        ?>

        <form method="GET">
            <input type="text" name="subject" placeholder="Логин">
            <input type="password" name="subject_pass" placeholder="Пароль">
            <p><input type="submit" value="Войти"></p>
        </form>
    </main>

    <my-footer></my-footer>

</body>
</html>