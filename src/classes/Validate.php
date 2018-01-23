<?php

namespace app\classes;

use app\error\ValidateException;

class Validate
{
    private $post;
    private $data;
    private $message;
    private $text;

    public function __construct($post, $text)
    {
        $this->post = $post;
        $this->text = $text;
    }

    public static function clean($value = '')
    {
        $value = trim($value);
        $value = stripcslashes($value);
        $value = strip_tags($value);
        $value = htmlspecialchars($value);
        return $value;
    }

    public function formatData()
    {
        $data['login'] = isset($this->post['login']) ? (string)static::clean($this->post['login']) : "";
        $data['password'] = isset($this->post['password']) ? (string)static::clean($this->post['password']) : "";
        $data['repeatPassword'] = isset($this->post['repeatPassword']) ? (string)static::clean($this->post['repeatPassword']) : "";
        $data['email'] = isset($this->post['email']) ? (string)static::clean($this->post['email']) : "";
        $data['image'] = $this->post['image'] !== null ? $this->post['image'] : null;
        $this->data = $data;
        return $this;
    }

    public function isEmpty()
    {
        foreach ($this->data as $k => $v) {
            if (empty($v)) {
                    $this->message[$k] = $this->text->$k->empty;
                    $this->message['success'] = "false";
            }
        }
        return $this;
    }

    public function validateAll()
    {
        $this->validateEmail();
        $this->validateLogin();
        $this->validatePassword();
        $this->validateRepeatPassword();
        if (isset($this->data['image']) && $this->data['image'] !== null) {
            $this->validateImage();
        }
        if (isset($this->message)) {
            return false;
        }
        return true;
    }

    public function validateEmail()
    {
        if (isset($this->data['email'])) {
            if (!filter_var($this->data['email'], FILTER_VALIDATE_EMAIL)) {
                if (empty($this->message['email'])) {
                    $this->message['email'] = $this->text->email->type;
                }
            }
        } else {
            throw new ValidateException($this->text->email->error);
        }
        return $this;
    }

    public function validateLogin()
    {
        if (isset($this->data['login'])) {
            if (!$this->check_length($this->data['login'], 4, 15)) {
                if (empty($this->message['login'])) {
                    $this->message['login'] = $this->text->login->type;
                }
            }
            if (!preg_match('/^[a-zA-Z0-9]+$/', $this->data['login'])) {
                if (empty($this->message['login'])) {
                    $this->message['login'] = $this->text->login->type;
                }
            }
        } else {
            throw new ValidateException($this->text->login->error);
        }
        return $this;
    }

    public function validatePassword()
    {
        if (isset($this->data['password'])) {
            if (!$this->check_length($this->data['password'], 6)) {
                if (empty($this->message['password'])) {
                    $this->message['password'] = $this->text->password->length;
                }
            }
            if (!preg_match('/^[a-zA-Z0-9]+$/', $this->data['password'])) {
                if (empty($this->message['password'])) {
                    $this->message['password'] = $this->text->password->type;
                }
            }
            if (!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$/', $this->data['password'])) {
                if (empty($this->message['password'])) {
                    $this->message['password'] = $this->text->password->rule;
                }
            }
        } else {
            throw new ValidateException('Отсутствует поле "Пароль". Пожалуйста, перезагрузите страницу.');
        }
        return $this;
    }

    public function validateRepeatPassword()
    {
        if (isset($this->data['repeatPassword'])) {
            if ($this->data['password'] !== $this->data['repeatPassword']) {
                if (empty($this->message['repeatPassword'])) {
                    $this->message['repeatPassword'] = $this->text->repeatPassword->compare;
                }
            }
            return $this;
        } else {
            throw new ValidateException('Отсутствует поле "Повтор пароля". Пожалуйста, перезагрузите страницу.');
        }
    }

    public function validateImage()
    {
        $types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!empty($this->data['image']['name']) && $this->data['image'] !== null) {
            $image = $this->data['image']['tmp_name'] ? $this->data['image']['tmp_name'] : null;
            if ($image === null || !getimagesize($image) || getimagesize($image) === null) {
                if (empty($this->message['image'])) {
                    $this->message['image'] = $this->text->image->server;
                }
            }
            $type = getimagesize($image)['mime'];
            if (!in_array($type, $types)) {
                if (empty($this->message['image'])) {
                    $this->message['image'] = $this->text->image->format;
                }
            }
        }
    }

    public function getMessage()
    {
        if (isset($this->message)) {
            $this->message['status'] = false;
            return $this->message;
        }
        return false;
    }

    public function getData()
    {
        if (isset($this->data)) {
            return $this->data;
        }
        return false;
    }

    private function check_length($value = "", $min, $max = null)
    {
        if ($max !== null) {
            $result = (mb_strlen($value) < $min || mb_strlen($value) > $max);
        } else {
            $result = (mb_strlen($value) < $min);
        }
        return !$result;
    }
}