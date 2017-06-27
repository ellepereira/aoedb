<?php
class items extends app
{
	function __construct(&$parent)
	{
		parent::__construct($parent);

		$this->load->app('aoeo');	
		
		$this->load->model('item');
		$this->load->model('traiteffect');
		$this->config['aoeo'] = $this->aoeo->config;
		$this->load->config('items', true);

	}
	
	protected function header($title = 'Items')
	{
		$this->aoeo->header($title);	
	}
		
	protected function footer()
	{
		$this->aoeo->show('ad_temp');
		$this->aoeo->footer();
	}
		
	public function c_index($id = null, $level = null)
	{	
		if($id)
			$this->c_trait($id, $level);
		else
			$this->c_mainpage();
	}
		
	public function c_showall()
	{
		$this->header();
		$data['items'] = $this->m_item->get_all();					
		$this->show('traitlist', $items);
		$this->footer();
	}
		
	public function c_trait($id, $level = null)
	{	
		if($level)
			$level += 3;

		$item = $this->m_item->load($id, $level);
		
		$this->header($item->info['DisplayName']);
		
		$data['item'] = $item->info;
		
		$this->load->view('trait', $data);
		
    	$comments_id = "trait_{$id}";
		//echo $comments_id;
		
		$this->footer();
	}
	
	public function c_a_trait($id, $level = null)
	{
		if($level)
			$level += 3;
		
		$item = $this->m_item->load($id, $level);
		$data['item'] = $item->info;
		
		$this->aoeo->load->view('tinyheader');
		$this->load->view('trait', $data);
		$this->aoeo->load->view('tinyfooter');
	}
	
	public function c_mainpage()
	{	
		$this->header();
		$newarr = array();
		foreach($this->config['items'] as $k=>$n)
		{
			if(strpos($k,'Con') === false && !empty($n))
				$newarr[$k] = $n;
		}
		
		asort($newarr);
		
		$this->show('traitsmain', $newarr);
		$this->footer();
	}
	
	public function c_search($term)
	{
		$this->header();
		$items = $this->m_item->search($term);
		$this->show('searchlist', $items);
		$this->footer();
	}
	
	public function c_type($type)
		{		
			$this->header();
			
			$items = $this->m_item->get_all_by_type($type);
			$this->show('traitlist', $items);
			
			$this->footer();
		}

	public function c_a_minimized($id, $level = null)
	{
		if($level)
			$level += 3;
		
		$item = $this->m_item->load($id, $level);	
		$data['item'] = $item->info;
		
		$this->aoeo->load->view('tinyheader');
		$this->load->view('tinytrait', $data);
		$this->aoeo->load->view('tinyfooter');
	}
		
	public function c_update()
	{
		$this->m_item->db_update();
		$this->m_item->db_update_toc();
	}
		
	public function c_aeffects($dbid, $level)
	{
		$level += 3;
		$effects = $this->m_item->get_effects($dbid, $level);
		
		foreach($effects as $effect)
		{
			echo "<li class='bonus{$effect->bonus}'>{$effect}</li>";
		}
	}
	
	public function getImgData($dbid, $level)
	{
		$rlevel = $level;
		$level += 3;

		$tempitem = $this->m_item->load($dbid, $level);
	
		if (!$tempitem->info)
			return false;
		
		//$level += 3;
		//$rlevel = $level-3;
		
		 
		$tempitem->info['type'] = $this->config[$tempitem->info['traittype']];
	
		$item['displayName'] = $tempitem->info['DisplayName'];
		$item['type'] = $tempitem->info['type'];
		$item['rarity'] = ucwords($tempitem->info['rarity']);
	
		$rarityColors['Uncommon'] = '45ff5e';
		$rarityColors['Rare'] = '3b8fff';
		$rarityColors['Epic'] = 'b04bdf';
		$rarityColors['Legendary'] = 'ffaa0e';
		 
		if (array_key_exists($item['rarity'], $rarityColors))
			$item['rarityColor'] = $rarityColors[$item['rarity']];
		else
			$item['rarityColor'] = 'ffffff';
		 
		$item['icon'] = 'images/Art/' . $tempitem->info['icon'] . '.png';
		 
		$item['description'] = 'Required Level: ' . $rlevel . "\n\n" . str_replace('\n', "\n\n", trim($tempitem->info['RolloverText']));
		$item['description'] = str_replace('<color color= "1.0,1.0,0.0">', '', $item['description']);
		$item['description'] = str_replace('</color>', '', $item['description']);
		 
		foreach ($tempitem->info['effectstrings'] as $effect)
		{
			$item['effects'][] = $effect->__toString();
	
			if ($effect->bonus == 'true')
			$item['effectColors'][] = '00ff00';
			else
			$item['effectColors'][] = 'ff0000';
		}
		 
		$item['dbid_str'] = 'dbid: ' . strval($dbid);
	
		return $item;
	}
}

/**end of file*/