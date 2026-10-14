<?php
session_start(); // Возобновляем сессию

// 1. Генерируем случайную строку (5-6 символов)
$chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
$length = rand(5, 6);
$random_string = '';
for ($i = 0; $i < $length; $i++) {
    $random_string .= $chars[rand(0, strlen($chars) - 1)];
}

// 2. Сохраняем строку в сессию для последующей проверки
$_SESSION['captcha_string'] = $random_string;

// 3. Создаем изображение
$width = 300;
$height = 80;

// Пытаемся загрузить noise.jpg
if (file_exists('noise.jpg')) {
    $im = imagecreatefromjpeg('noise.jpg');
    // Если картинка меньше нужного размера, создаем новую нужного размера
    if (imagesx($im) < $width || imagesy($im) < $height) {
        $temp_im = imagecreatetruecolor($width, $height);
        imagecopyresampled($temp_im, $im, 0, 0, 0, 0, $width, $height, imagesx($im), imagesy($im));
        imagedestroy($im);
        $im = $temp_im;
    }
} else {
    // Если файла нет, создаем серый фон
    $im = imagecreatetruecolor($width, $height);
    $bg_color = imagecolorallocate($im, 220, 220, 220);
    imagefill($im, 0, 0, $bg_color);
}

// 4. Настройки шрифта
// Внимание: путь к шрифту зависит от ОС. 
// На Windows это c:\windows\fonts\arial.ttf
// На Linux серверах этот путь будет другим, например: /usr/share/fonts/truetype/dejavu/DejaVuSans.ttf
$font = 'C:\Windows\Fonts\arial.ttf'; 

// Если шрифт не найден по указанному пути, пробуем стандартный для Linux
if (!file_exists($font)) {
    $font = '/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf';
}

// Если и его нет, используем встроенный шрифт (менее красиво, но работает)
$use_ttf = file_exists($font);

// 5. Рисуем символы
$x = 20; // Начальный отступ по X
$y = 50; // Базовый отступ по Y

for ($i = 0; $i < strlen($random_string); $i++) {
    $char = $random_string[$i];
    
    // Случайный цвет для текста (темные оттенки)
    $text_color = imagecolorallocate($im, rand(0, 100), rand(0, 100), rand(0, 100));
    
    if ($use_ttf) {
        // Параметры: картинка, размер, угол, X, Y, цвет, шрифт, текст
        $size = rand(18, 30);      // Размер от 18 до 30 пт
        $angle = rand(-15, 15);    // Небольшой наклон
        $y_offset = rand(40, 60);  // Небольшое смещение по вертикали
        
        imagettftext($im, $size, $angle, $x, $y_offset, $text_color, $font, $char);
    } else {
        // Встроенный шрифт (размер 5 - максимальный)
        imagestring($im, 5, $x, $y, $char, $text_color);
    }
    
    // Расстояние между символами 40 пт
    $x += 40; 
}

// 6. Отправляем заголовок и выводим картинку
header('Content-Type: image/jpeg');
imagejpeg($im);
imagedestroy($im);
?>