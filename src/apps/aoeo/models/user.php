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
		$this->input = $this->parent->input;
		
		$this->logged_in = $this->login_session();
		
	}
	
	public function login_session()
	{
		if($this->input->cookie('db_uname') && $this->input->cookie('db_password'))
			$this->login($this->input->cookie('db_uname'), $this->input->cookie('db_password'));
		else
			return false;
		
		
		return true;
	}
	
	public function login_form($name, $password)
	{
		$password = md5($password.'saltines');
		return $this->login($name, $password);
	}
		
	public function login($name, $password)
	{	
		$uar = $this->db->query("SELECT * FROM users WHERE name='{$name}' AND password='{$password}' LIMIT 1")->results();
		
		//Could not login
		if(!$uar)
		{
			$this->logout();
			return false;
		}
		
		foreach($uar as $k=>$u)
			$this->$k = $u;
		
		if(!$this->input->cookie('db_uname'))
			$this->createlogincookie();
		
		$this->logged_in = true;
		
		return $uar;
		
	}
	
	public function logout()
	{
		setcookie('db_uid', '', time()-1000 ,'/' ,'');
		setcookie('db_uname', '', time()-1000 ,'/' ,'');
		setcookie('db_password', '', time()-1000 ,'/' ,'');
		setcookie('db_uemail', '', time()-1000 ,'/' ,'');
		
		$this->logged_in = false;
	}
	
	protected function createlogincookie()
	{
		$year = time()+60*60*24*30;
		setcookie('db_uname', $this->name, $year, '/', '');
		setcookie('db_uemail', $this->email, $year, '/', '');
		setcookie('db_password', $this->password, $year, '/', '');
		setcookie('db_uid', $this->uid, $year, '/', '');
		
		return $_COOKIE;
	}
	
	public function register($info)
	{
		$password = md5($info['password'].'saltines');
		
		$newuser = array(
		'name' => $info['username'],
		'password' => $password,
		'email' => $info['email'],
		'auctionopt' => ($info['auctionopt'] == 'on') ? 1:0,
		'emailopt' => ($info['emailopt'] == 'on') ? 1:0,
		'gamertag' => $info['gamertag'],
		'prefserver' => $info['prefserver']
		);
			
		
		if($this->quicksave($newuser))
		{
			$this->uid = $this->db->get_last_insert_id();
			$this->registerdate = time();
			$this->points = 0;
			$this->name = $info['username'];
			$this->email = $info['email'];
			$this->password = $password;
			$this->info = $newuser;
			
			$this->createlogincookie();
		}
		else
		{
			return false;
		}
		
		return true;
	}
	
}