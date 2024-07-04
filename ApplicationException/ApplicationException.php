<?php

namespace app\ApplicationException;

class ApplicationException extends \Exception
{
    public function __construct(
        $message = 'Возникла ошибка при выполнении',
        $code = 500,
        $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}