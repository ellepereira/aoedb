<?php
class session
{
	protected $session;
	
	function __construct()
	{
		if(session_id() == null)
			session_start();
			
		if(isset($_SESSION))
			$this->session = $_SESSION;
	}
	
	//session($params)...
	public function __invoke($args)
	{
		$mode = count($args);
		
		switch($mode)
		{
			case 1: //get
				return $this->__get($args[0]);
				break; //won't reach
			case 2: //set
				$this->__set($args[0], $args[1]);
				break;
			default:
				return null;
				break;
		}
	}
	
	public function __get($name)
	{
		if(!isset($this->session[$name]))
			return null;
			
		return $this->session[$name];
	}
	
	public function __set($name, $value)
	{
		$this->session[$name] = $value;
		$_SESSION[$name] = $value;
	}
}
?>