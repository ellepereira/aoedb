<?php 
class MethodNotFoundException extends AppException
{
	function __construct($message=null, $code=null)
	{
		parent::__construct($message, $code);
	}	
}