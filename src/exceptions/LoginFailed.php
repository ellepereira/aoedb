<?php 
class LoginFailed extends AppException
{
	function __construct($message=null, $code=null)
	{
		parent::__construct($message, $code);
	}	
}