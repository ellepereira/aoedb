<?php
class MethodNotFoundException extends AppException
{
    public function __construct($message = null, $code = null)
    {
        parent::__construct($message, $code);
    }
}
