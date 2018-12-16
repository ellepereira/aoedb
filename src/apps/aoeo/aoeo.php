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
  }

  	public function header($title = '')
  	{
  		$data = array();
  		
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
 	
  	function goback()
  	{
  		echo '<meta http-equiv="REFRESH" content="0;url=/aoeo/">';

  	}
}

/**end of file*/