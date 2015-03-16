<?php 
class AppException extends Exception
{
	function __construct($message=null, $code=null)
	{
		parent::__construct($message, $code);
	}	
}