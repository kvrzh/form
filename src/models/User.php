<?php

namespace app\models;

use app\classes\MyPDO;
use app\error\RegistrationException;

class User
{
    private $database = null;
    private $table = 'users';
    private $data;
    private $message;
    private $text;

    public function __construct(MyPDO $pdo, array $data, $text)
    {
        $this->database = $pdo;
        $this->data = $data;
        $this->text = $text;
    }

    public function select()
    {
        return $this->database->run("SELECT * FROM $this->table")->fetchAll();
    }

    public function create()
    {
        try {
            $this->database->beginTransaction();
            $this->generateHash()->insert();
            if ($this->message['registration']['status'] === true) {
                if (!empty($this->data['image']['tmp_name'])) {
                    $this->loadImage();
                }
            }
            $this->database->commit();
        } catch (\Exception $e) {
            $this->database->rollBack();
            throw new RegistrationException($e->getMessage());
        }
        return $this;
    }

    private function insert()
    {
        $image = !empty($this->data['image']['name']) ? $this->data['login'] . '_' . $this->data['image']['name'] : null;
        $insert = $this->database->run('INSERT INTO ' . $this->table . ' (login, hashedPassword, email, image) VALUES (?, ?, ?, ?)', [$this->data['login'], $this->data['hashedPassword'], $this->data['email'], $image]);
        if ((int)$insert->errorCode() === 00000) {
            $this->message['registration']['message'] = $this->text->user->success;
            $this->message['registration']['status'] = true;
        } else if ((int)$insert->errorCode() === 23000) {
            $this->message['registration']['message'] = $this->text->user->error;
            $this->message['registration']['status'] = false;
        } else {
            print $insert->errorInfo()[3];
            throw new RegistrationException($this->text->user->failRegistration);
        }
        return $this;
    }

    public function getMessage()
    {
        return $this->message;
    }

    private function loadImage()
    {
        if (is_uploaded_file($this->data['image']['tmp_name'])) {
            if (!move_uploaded_file($this->data['image']['tmp_name'], "public/images/" . $this->data['login'] . '_' . $this->data['image']['name'])) {
                throw new RegistrationException($this->text->user->errorImage);
            }
        }
        return $this;
    }

    public function generateHash()
    {
        $hash = password_hash($this->data['password'], PASSWORD_BCRYPT);
        $this->data['hashedPassword'] = $hash;
        return $this;
    }
}