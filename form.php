<?php

use app\models\User;
use app\classes\MyPDO;
use app\classes\Validate;
use app\models\Language;

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/config/db.php';

if (isset($_POST['submit'])) {
    $data = $_POST;
    $data['image'] = $_FILES['image'] ? $_FILES['image'] : null;
    $lang = $data['lang'] ? new Language($data['lang']) : new Language('ru');
    $text = $lang->getText();
    $validate = new Validate($data, $text);
    try {
        header('Content-Type: application/json; charset=utf-8');
        $isValidate = $validate->formatData()->isEmpty()->validateAll();
        if ($isValidate === true) {
            $data = $validate->getData();
            $pdo = new MyPDO("mysql:" . "host=" . $db['host'] . ";dbname=" . $db['name'], $db['login'], $db['password']);
            $user = new User($pdo, (array)$data, $text);
            echo json_encode($user->create()->getMessage());
        } else {
            $message['validate'] = $validate->getMessage();
            echo json_encode($message);
        }
    } catch (Exception $e) {
        $error['error'] = $e->getMessage();
        echo json_encode($error);
    }
}
if (isset($_GET['hl'])) { // По хорошему, это все реализовать с единой точкой входа через index.php . Но только для формы все это делать - долго.
    $lang = new Language($_GET['hl']);
    echo $lang->getText(true);
}