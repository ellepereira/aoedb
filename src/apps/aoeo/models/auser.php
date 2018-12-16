<?php

class user extends model
{
  public $uid, $name, $password, $email, $registerdate, $points, $logged_in;
  
	function __construct(&$parent)
	{
		parent::__construct($parent);
	
		$this->table = 'users';
		$this->idfield = 'uid';
		$this->orderby_field = 'name';
		$this->config = $this->parent->config;
		
		$this->logged_in = $this->login_session();
		
	}
	
	public function login_session()
	{
		if($this->parent->input->cookie('db_uname') && $this->parent->input->cookie('db_password'))
			$this->login($this->parent->input->cookie('db_uname'), $this->parent->input->cookie('db_password'));
		else
			return false;
		
		return true;
	}
	
	public function login_form($name, $password)
	{
		$password = md5($password.'saltines');
		$this->login($name, $password);
	}
	
	
	public function login($name, $password)
	{	

		$uar = $this->db->query("SELECT * FROM users WHERE name='{$name}' AND password='{$password}' LIMIT 1")->results();
		
		if(!$uar)
			return false;
		
		foreach($uar as $k=>$u)
			$this->$k = $u;
		
		if(!$this->parent->input->cookie('db_uname'))
			$this->createlogincookie();
		
		$this->logged_in = true;
		
		return $uar;
		
	}
	
	public function logout()
	{
		setcookie('db_uid', '', time()-1000);
		setcookie('db_uname', '', time()-1000);
		setcookie('db_password', '', time()-1000);
		setcookie('db_uemail', '', time()-1000);
		
		$this->logged_in = false;
	}
	
	protected function createlogincookie()
	{
		$year = time()+60*60*24*30;
		setcookie('db_uname', $this->name, $year);
		setcookie('db_uemail', $this->email, $year);
		setcookie('db_password', $this->password, $year);
		setcookie('db_uid', $this->uid, $year);
		
		return $_COOKIE;
	}
	
	
	public function register($name, $password, $email)
	{
		$password = md5($password.'saltines');
		
		$newuser = array(
		'name' => $name,
		'password' => $password,
		'email' => $email);
		
		if($this->quicksave($newuser))
		{
			$this->uid = $this->db->get_last_insert_id();
			$this->registerdate = time();
			$this->points = 0;
			$this->name = $name;
			$this->email = $email;
			$this->password = $password;
			
			$this->createlogincookie();
		}
		else
		{
			return false;
		}
	}
	
}