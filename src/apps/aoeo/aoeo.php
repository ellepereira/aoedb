<?php

class aoeo extends app
{	
	public $user;
	
	function __construct(&$parent)
	{
		parent::__construct($parent);
		
	}

	/****************************************************************
	 *                      Controllers                             *
	 ****************************************************************/
	
	/**
	 * Receives index
	 * @return null
	 */
	public function c_index($page = null)
	{	
		$data = array();
		
  		$this->checklogin();
  		
  		if($this->user->logged_in)
  			$data['user'] = $this->user;
  		
		$data['title'] = 'Age of Empires Online Database';
  		$this->show('header', $data);
  		
		if(!$page)
		{
			$this->load->view('index');
		}
		else
		{
			echo "Could not access: $page";
		}
		$this->footer();
	}
	
	private function checklogin()
	{
		$this->load->model('user');
		
		//login and get username
		if(isset($this->m_user))
			$this->user = $this->m_user;
	}
	
	public function c_xml($folder, $file)
	{
		$this->header('XML View');
		$this->c_axml($folder, $file);
		$this->footer();
	}
	
	public function c_axml($folder, $file)
	{
		$path = $this->config['exportpath'].$folder.'/'.$file.'.xml';
	
		echo '<textarea cols=80 rows=30>';
		echo file_get_contents($path);
		echo '</textarea>';
	
	}
	
	public function c_search($s = null, $type=null)
	{
		$this->header('Search Results');
		$this->load->model('search');
		
		if($s == null)
			$s = $this->input->post('q');
		
		$s = addslashes($s);
		
		if(empty($s))
		{
			echo "<meta http-equiv='REFRESH' content='0;url=/aoeo/'>";
			exit;
		}
		
		$data = array();
		$results = $this->m_search->search($s, $type);
		
		unset($results['quest']);
		
		if(isset($results['trait']))
		{
			$results['item'] = $results['trait'];
			unset($results['trait']);
		}
		
		if(count($results) < 1)
		{
			echo '<br /> No results found :(';
			$this->footer();
		}
		else if(isset($results['ONLY']))		
		{
			$r = $results['ONLY'];
			echo "<meta http-equiv='REFRESH' content='0;url=/{$r['type']}s/{$r['dbid']}'>";
			echo 'Redirecting...';
			$this->footer();
			return;
		}
		else
		{
			$data['results'] = $results;
			$data['query'] = $s;
			$this->show('searchresults', $data);
		}
		
	}
	
	public function c_dispimg() {
    require 'libraries/imagecreatefromtga.php';
    require 'libraries/dirRecursive.php';
    $imgpath = $this->config['artpath'];
    $destination = 'images/Art';
    //echo '<pre>';
    echo "<body background='http://i.imgur.com/uaOR2h.jpg'>";
    
    $filelist = dirRecursive($destination . '/ui');
    
    foreach ($filelist as $filename) {
      echo "<img src='{$filename}'>";
    }
    
    $filelist = dirRecursive($destination . '/UserInterface');
    
    foreach ($filelist as $filename) {
      echo "<img src='{$filename}'>";
    }
    
    
//    $filelist = substr($filelist, strlen($imgpath));
    
//    print_r($filelist);
//    $c = 0;
    
/*    $imgpath_len = strlen($imgpath);
    foreach ($filelist as $filename) {
      if (substr($filename, -4, 4) == '.tga' && strpos($filename, '(')) {
        $outputfilename = $destination . substr($filename, $imgpath_len, strpos($filename, '.') - $imgpath_len) . '.png';
        echo "<img src='{$outputfilename}'>\n";
        
        if (!file_exists($outputfilename)) {
          $img = imagecreatefromtga_alpha($filename);
          $outputdir = substr($outputfilename, 0, strrpos($outputfilename, '/'));
          
          if (!is_dir($outputdir)) {
            mkdir($outputdir , 0777, true);
          }
          
          imagepng($img, $outputfilename);
          $c++;
        }
        
        if ($c >= 25)
          exit;
      }
    } */
  }

  	public function header($title = '')
  	{
  		$data = array();
  		
  		$this->checklogin();
  		
  		if($this->user->logged_in)
  			$data['user'] = $this->user;
  		
  		if(empty($title))
  			$title = 'Age of Empires Online Database';
  		else
  			$title .= ' - Age of Empires Online Database';
  		
  		$data['title'] = $title;
  		
  		$this->show('header', $data);
  		$this->show('searchheader');
  	}
  	
  	public function c_test()
  	{

  		$this->show('headertest');
  		
  		$this->load->view('index');
  		
  		$this->footer();
  	}
  	
  	public function footer()
  	{
  		$this->show('footer');
  	}
  	
  	private function parse_feed($feed) 
  	{
  		$stepOne = explode("<content type=\"html\">", $feed);
  		$stepTwo = explode("</content>", $stepOne[1]);
  		$tweet = $stepTwo[0];
  		$tweet = htmlspecialchars_decode($tweet,ENT_QUOTES);
  		return $tweet;
  	}
  	
  	public function c_tweets()
  	{
		$username = "aoedb";
		$feed = "http://search.twitter.com/search.atom?q=from:" . $username . "&rpp=1";
		$twitterFeed = file_get_contents($feed);
		echo('&quot;'.$this->parse_feed($twitterFeed).'&quot;');
	}
  	
  	public function c_regions()
  	{
  		$this->header('Regions');
  		$this->footer();
  	}
  	
  	public function c_vendors()
  	{
  		$this->header('Vendors');
  		$this->footer();
  	}
  	
  	public function c_videos()
  	{
  		$this->header('Videos');
  		$this->footer();
  	}
  	
  	function c_login($backto=null)
  	{
  		if(isset($backto))
  			$this->backto = $backto;
  		
  		$this->checklogin();
  	
  		if($this->input->post('uname') && $this->input->post('password'))
  		{
  			
  			if($this->user->login_form($this->input->post('uname'), $this->input->post('password')))
  			{ //successful login
  				echo "yay";
  				//$this->goback();
  			}
  			else
  			{
  				$this->header('Login');
  				$this->show('invalid_login');
  			}
  				
  		}
  		
  		else
  		{
  			$this->header('Login');
  		}
  	
  		if(!isset($this->user->name))		
  			$this->c_login_screen();
  		else
  			$this->goback();
  		
  		$this->footer();
  	
  	}
  	
  	function c_logout($backto = null)
  	{
  		if(isset($backto))
  			$this->backto = $backto;
  		
  		$this->checklogin();
  		 
  		$this->user->logout();
  		
  		$this->header('Logout');
  		
  		$this->footer();
  		
  		$this->goback();
  		 
  	}
  	
  	function c_login_screen()
  	{
  		$this->load->view('login');
  	}
  	
  	function c_register_screen()
  	{
  		$this->load->view('register');
  	}
  	
  	function c_register()
  	{ 		
  		if(count($this->input->post) > 5)
  		{
  			$this->load->model('user');
  			
  			$p = $this->input->post;
  			
  			if(strlen($p['username']) < 5)
  			{
  				$this->header('Register');
  				echo "Username has to be at least 5 characters long.";
  				$this->footer();
  				return;
  			}
  			
  			else if(strlen($p['gamertag']) < 5)
  			{
  				$this->header('Register');
  				echo "Gamertag has to be at least 5 characters long.";
  				$this->footer();
  				return;
  			}
  			
  			else if(strlen($p['password']) < 6)
  			{
  				$this->header('Register');
  				echo "Password has to be at least 6 characters long.";
  				$this->footer();
  				return;
  			}
  			
  			else if(strlen($p['email']) < 6)
  			{
  				$this->header('Register');
  				echo "Invalid email address.";
  				$this->footer();
  				return;
  			}
  			
  			else if(!$this->m_user->register($this->input->post))
  			{
  				$this->header('Register');
  				echo 'Registration error - please go back and try again.';
  				$this->footer();
  			}
  			
  			else 
  			{
  				$this->goback();
  			}
  		}
  		
  	
  		else
  		{
  			$this->header('Register');
  			$this->c_register_screen();
  			$this->footer();
  		}
  	}
  	
  	function c_editprofile()
  	{
  		if($this->input->post('username') && $this->input->post('password') && $this->input->post('email'))
  		{
  			$this->load->model('user');
  		}
  		 
  		else
  		{
  			$this->header('Edit Profile');
  			$this->show('editprofile', $this->user);
  			$this->footer();
  		}
  	}
 	
  	function goback()
  	{
  		echo '<meta http-equiv="REFRESH" content="0;url=/aoeo/">';

  	}
}

/**end of file*/