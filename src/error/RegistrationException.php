<?php

namespace app\error;

use Exception;

class RegistrationException extends BaseException
{
    function __construct($message = "Ошибка при регистарции", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}