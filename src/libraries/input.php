<?php
class input
{
	public $get, $post, $cookie, $session;
	
	function __construct()
	{
		
		$this->get = $this->clean($_GET);
		$this->post = $this->clean($_POST);
		$this->cookie = $this->clean($_COOKIE);
		//print_r($_COOKIE);
		//
	}
	
	function __call($name, $args)
	{
		if(isset($this->$name))
		{
			if(isset($args[0]))
			{
				$r = $this->$name;

				if(isset($r[$args[0]]))
					return $r[$args[0]];
			}
			else
			{
				return $this->$name;	
			}
		}
		
	}
	
	function clean($arr)
	{
		foreach($arr as $key=>$value) {
			$arr[$key] = addslashes($value);
		}
			
		return $arr;
	}
}
?>