<?php
session_start(); // Запускаем сессию для хранения строки капчи

$message = '';

// Проверяем, была ли отправлена форма
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_input = isset($_POST['user_input']) ? trim($_POST['user_input']) : '';
    $captcha_string = isset($_SESSION['captcha_string']) ? $_SESSION['captcha_string'] : '';

    // Сравниваем введенную строку с той, что в сессии (без учета регистра)
    if ($user_input !== '' && strcasecmp($user_input, $captcha_string) === 0) {
        $message = '<p style="color: green; font-weight: bold;">Правильно</p>';
    } else {
        $message = '<p style="color: red; font-weight: bold;">Неправильно</p>';
    }

    // Очищаем сессию после проверки, чтобы нельзя было использовать одну капчу дважды
    unset($_SESSION['captcha_string']);
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация с CAPTCHA</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .captcha-container { margin: 15px 0; }
        .captcha-container img { border: 1px solid #ccc; }
        input[type="text"] { padding: 5px; font-size: 16px; margin-right: 10px; }
        input[type="submit"] { padding: 5px 15px; font-size: 16px; cursor: pointer; }
    </style>
</head>
<body>

    <h2>Регистрация</h2>

    <form method="POST" action="">
        <div class="captcha-container">
            <!-- Выводим картинку. Параметр t=... нужен, чтобы браузер не кешировал картинку -->
            <img src="captcha.php?t=<?php echo time(); ?>" alt="CAPTCHA">
        </div>

        <label for="user_input">Введите строку</label>
        <input type="text" name="user_input" id="user_input" autocomplete="off" required>
        <input type="submit" value="OK">
    </form>

    <br>
    <?php echo $message; ?>

</body>
</html>