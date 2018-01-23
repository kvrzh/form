<?php

namespace app\error;

use Exception;

class ValidateException extends BaseException
{
    function __construct($message = "Форма не прошла валидацию", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}