<?php
class AppException extends Exception
{
    public function __construct($message = null, $code = null)
    {
        parent::__construct($message, $code);
    }
}
