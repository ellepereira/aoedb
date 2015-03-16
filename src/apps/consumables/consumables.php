<?php

class consumables extends app
{
  public $info;
	function __construct(&$parent)
	{
		parent::__construct($parent);

		//easy database handler
		//$this->load->lib('easydb', $this->db);
		$this->load->app('aoeo');

		$this->config = $this->aoeo->config;
		$this->load->model('consumable');
		
			
	}
	
	public function c_index($name = null)
	{
		if (!$name) 
		{
			$this->c_rarity('cRarityCommon');
		}
    
    	elseif ($this->m_consumable->load($name)) 
    	{
    		$this->aoeo->header($this->m_consumable->displayname. ' Consumable');
     	 	$consumable = $this->m_consumable;
      		$this->load->view('consumable', $consumable->info);
      		$this->aoeo->load->view('ad_temp');
      		$this->aoeo->footer();
    	}
	    else
	    {
	   		$this->aoeo->header();
	      	echo 'Could not load consumable';
	      	$this->aoeo->footer();
	    }
	}
	
	
	public function c_rarity($rarity)
	{
		$rarities = array('cRarityCommon' => 'Common',
		                  'cRarityUncommon' => 'Uncommon',
		                  'cRarityRare' => 'Rare',
		                  'cRarityEpic' => 'Epic');
		
		$this->aoeo->header($rarities[$rarity].' Consumables');
		$c = $this->m_consumable->get_all_by_rarity($rarity);
		$this->show('consumableslist', $c);
		$this->aoeo->load->view('ad_temp');
		$this->aoeo->footer();
	}
	
	public function c_update()
	{
		$this->m_consumable->db_update();
	}
}
?>