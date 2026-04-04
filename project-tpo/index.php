<!DOCTYPE html>
<html lang = "ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="components/header.js"></script>
    <title>Вход</title>
</head>
<body>
    <my-header></my-header>
    <?php
    session_start();

    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $base = 'busk_baza';

    $conn = mysqli_connect($host, $user, $pass, $base);

    $a = $_GET['subject'];
    $b = $_GET['subject_pass'];
    $query = "select * FROM `users` WHERE `login`='$a' AND `pass` = '$b'";
    $result = mysqli_query($conn, $query);
    $qwe = mysqli_fetch_array($result);
    if ($qwe) {
        echo '+';
        $_SESSION['user_name'] = $qwe['1'];
        $_SESSION['user_pass'] = $qwe['2'];
        echo "Вы вошли в аккаунт!";
        print_r($_SESSION);
    } else {
        echo "Такого аккаунта не существует";
    };
    ?>

    <form method="GET">
        <input type="text" name="subject" value="">
        <input type="password" name="subject_pass" value="">
        <p><input type="submit" name="btm_conf" value="ok"></p>
    </form>
</body>
</html>