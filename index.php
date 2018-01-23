<?php
require_once __DIR__ . '/vendor/autoload.php';

use app\models\Language;

$languages = ['ua', 'ru'];
if (!isset($_GET['hl']) || !in_array($_GET['hl'], $languages)) {
    header("Location: /?hl=ru");
}
$lang = new Language($_GET['hl']);
$text = $lang->getText();
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400&amp;subset=cyrillic" rel="stylesheet">
    <link rel="stylesheet" href="/public/css/style.css">
    <title><?= $text->title ?></title>
</head>
<body>
<div class="main">
    <div class="form">
        <div class="languages">
            <span class="language active" id="ru">ru</span>
            <span class="language" id="ua">ua</span>
        </div>
        <h1><?= $text->h1 ?></h1>
        <form action="/form.php" id="form">
            <div class="input">
                <input type="text" name="login" id="login" placeholder="<?= $text->login->placeholder ?>"/>
            </div>
            <div class="input">
                <input type="password" name="password" id="password" placeholder="<?= $text->password->placeholder ?>"/>
            </div>
            <div class="input">
                <input type="password" name="repeatPassword" id="repeatPassword"
                       placeholder="<?= $text->repeatPassword->placeholder ?>"/>
            </div>
            <div class="input">
                <input type="email" name="email" id="email" placeholder="<?= $text->email->placeholder ?>"/>
            </div>
            <div class="input">
                <input type="file" name="image" id="image"/>
            </div>
            <input name="submit" id="submit" type="submit" value="<?= $text->registration->name ?>"/>
        </form>
    </div>
    <div class="popup">
        <div class="error-popup">
            <h2><?= $text->error->name ?></h2>
            <h4></h4>
            <button><?= $text->reboot ?></button>
        </div>
    </div>
</div>
<script type="text/javascript" src="public/js/script.js"></script>
</body>
</html>