<?php
  
class item extends model
{
  protected $xmlpath;
  public $info, $level;
  
	function __construct(&$parent, $level = null)
	{
		parent::__construct($parent);
	
		$this->table = 'traits';
		$this->idfield = 'tr_id';
		$this->orderby_field = 'tr_id';
		$this->level = $level;
		
	}
	
	/**
	* Method to update all database entries by re-reading the XML data files for traits
	* WARNING will overright any changes made on the database
	*/
	public function db_update()
	{
		
	$this->xmlpath = $this->config['aoeo']['traitspath'];
    $XMLReader = new XMLReader();
    $XMLReader->open($this->xmlpath);
    
    
    $this->delete_all();
    $this->db->clear_table('traiteffects');
    
    $traitlevels = $this->fetch_levels();
    
    $techfields = array('dbid',
          'displaynameid',
          'rarity',
          'traittype',
          //'visualfactor',
          'icon',
          'offertype',
	      'tradeable',
    	  'destroyable',
    	  'sellable',
	      'rollovertextid');
          
    $techEffectAttributes = array('type',
          'amount',
          'scaling',
          'subtype',
          'allactions',
          'relativity',
          'action',
          'bonus',
          'unittype',
          'resource',
    	  'damagetype');
    
	    while ($XMLReader->read())
	     {
	      
	     	if ($XMLReader->nodeType == XMLReader::END_ELEMENT || $XMLReader->name != "trait")
	       	 continue;
	      
	     	 $doc = new DomDocument('1.0');
	     	 $doc->loadXML($XMLReader->readOuterXml());
	      
	      	$trait['name'] = $doc->documentElement->getAttribute('name');
	      	//$trait['type'] = $doc->documentElement->getAttribute('type');
	      
	      	// Most of the easy stuff
	     	foreach ($doc->documentElement->childNodes as $node) 
	      	{
	       	 if ($node->nodeType != 1)
	          	continue;
	        
	       	 if (in_array($node->tagName, $techfields))
	          	$trait[$node->tagName] = $node->nodeValue;
	      }
	      
	      
	      // Effects
	      $techEffects = $doc->documentElement->getElementsByTagName('effects');
	      if ($techEffects->length > 0) {
	        $techEffects = $techEffects->item(0);
	        $techEffects = $techEffects->getElementsByTagName('effect');
	        if ($techEffects->length > 0) {
	        	
	          foreach ($techEffects as $techEffectElement) {
	            $techEffect['dbid'] = $trait['dbid'];
	            
	            foreach ($techEffectAttributes as $techEffectAttribute) 
	            {
	              $techEffect[$techEffectAttribute] = $techEffectElement->getAttribute($techEffectAttribute);
	            }
	            
	            $this->db->insert('traiteffects', $techEffect);
	            
	            //print_r($techEffect);
	            
	            unset($techEffect);
	            
	          }
	        }
	      }
	      
	      // Fix icon filenames
	      $trait = str_replace('\\', '/', $trait);
	      
	      //if we have the levels
	      if(isset($traitlevels[$trait['name']]))
	     	 $trait['levels'] = $traitlevels[$trait['name']];
	      else
	      	$trait['levels'] = 0;
	      
	      if($trait['dbid'] == 1872)
	      {
	      	echo '<pre>';
	      	print_r($trait);
	      	echo $traitlevels[$trait['name']];
	      }
	      
	      $this->quicksave($trait);
	      
	      unset($trait);
	    }
    
	}

	private function fetch_levels()
	{
		$this->xmlpath = $this->config['aoeo']['traitslevelspath'];
		$XMLReader = new XMLReader();
		$XMLReader->open($this->xmlpath);
		
		$traits = array();
		
		while ($XMLReader->read())
		{
			if ($XMLReader->nodeType == XMLReader::END_ELEMENT || $XMLReader->name != "Item")
				continue;
			 
			$doc = new DomDocument('1.0');
			$doc->loadXML($XMLReader->readOuterXml());
			 
			$traits[$doc->documentElement->getAttribute('id')] = $doc->documentElement->getAttribute('levels');
		}

		return $traits;
	}
	
	public function db_update_toc()
	{
		$this->db->query("DELETE FROM tableofcontents WHERE type='trait'");
		
		$all = $this->get_all();
		
		foreach($all as $item)
		{
			$effects = '';
			
			foreach($item['effectstrings'] as $effect)
			{
				$effects .= ' '.$effect;
			}
			$values = array(
					'dbid' => $item['dbid'],
					'keyword' => mysql_real_escape_string($item['DisplayName']),
					'searchtext' => mysql_real_escape_string($item['RolloverText']),
					'type' => 'trait',
					'description' => $effects,
					'icon' => $item['icon']
					
			);
			$this->db->insert('tableofcontents', $values);
		}
	}

	/*function types()
	{
		$path ='c:\\aoeofiles\\data\\\\data\\traittypes.xml';
			
		$XMLReader = new XMLReader();
		$XMLReader->open($path);
		
		echo "\$config = array(";
		while ($XMLReader->read()) {
			if ($XMLReader->nodeType == XMLReader::END_ELEMENT || $XMLReader->name != "traittype")
			        continue;
			
			$doc = new DomDocument('1.0');
			$doc->loadXML($XMLReader->readOuterXml());
		
		
			$name = $doc->documentElement->getAttribute('name');
			$displaynameid = $doc->documentElement->getElementsByTagName('displaynameid')->item(0)->nodeValue;
			
			
			$r = $this->db->query("SELECT * FROM strings WHERE stringid={$displaynameid}")->results();
			
			$displayname = explode(' - ', $r['string']);
			$displayname = explode(' Equipment ', $displayname[0]);
			
			$displayname = $displayname[0];
			
			echo "'$name' => '$displayname' , \n <br />";
			
		}
		
		echo "'');";
		
			
	}*/
	
	public function load($id, $level = null)
	{	
		if($level != null)
			$this->level = $level;

		$this->info = $this->get($id);

		return $this;
	}
	
	public function get($id)
	{
		$arr = $this->db->query("SELECT *,
					    displayname.string as DisplayName, rollovertext.string as RolloverText
					    FROM traits
					    LEFT JOIN strings as displayname on displayname.stringid = traits.DisplayNameID
					    LEFT JOIN strings as rollovertext on rollovertext.stringid = traits.RolloverTextID 
						WHERE dbid={$id} LIMIT 1")->results();
		
		if($this->level != null)
		{
			$arr['level'] = $this->level;
		}
		else if (!empty($arr['levels']))
		{
			$levels = explode('|', $arr['levels']);
			arsort($levels);
			$arr['level'] = $levels[0]+3;
		}
		else
		{
			$arr['level'] = 43;
		}
	
   		$arr['effectstrings'] = $this->get_effects($arr['dbid'], $arr['level']);
   		
   		$arr['type'] = $this->config[$arr['traittype']];
   		
		return $arr;
	}
	
	public function get_effects($dbid, $level = null)
	{
		
		$effect_strings = array();
		$itemeffects = $this->db->query("SELECT * FROM traiteffects WHERE DBID={$dbid}")->results(null, true);
		
		foreach($itemeffects as $effect)
		{
			$m = new traiteffect($this->parent, $level);
			$m->loadByArray($effect);
			$effect_strings[] = $m;
		}
			
		return $effect_strings;
	}
	
	public function get_all()
	{
		$arr = $this->db->query("select *,
				    displayname.string as DisplayName, rollovertext.string as RolloverText
				    FROM traits
				    LEFT JOIN strings as displayname on displayname.stringid = traits.DisplayNameID
				    LEFT JOIN strings as rollovertext on rollovertext.stringid = traits.RolloverTextID")->results();
		
		foreach($arr as $k=>$item)
		{
			$arr[$k]['effectstrings'] = $this->get_effects($item['dbid']);
			$arr[$k]['type'] = $this->config[$item['traittype']];
		}
			
		return $arr;
	}
	
	public function search($term)
	{
		return $this->db->query("SELECT * FROM tableofcontents WHERE keyword LIKE '%{$term}%' AND type='trait' LIMIT 100")->results(null, true);
	}
	
	public function get_all_by_type($type)
	{
		$arr = $this->db->query("select *,
				    displayname.string as DisplayName, rollovertext.string as RolloverText
				    FROM traits
				    LEFT JOIN strings as displayname on displayname.stringid = traits.DisplayNameID
				    LEFT JOIN strings as rollovertext on rollovertext.stringid = traits.RolloverTextID
					WHERE traittype='{$type}' ORDER BY rarity")->results();
		
		foreach($arr as $k=>$r)
		{
			//$arr[$k]['effectstrings'] = $this->get_effects($r['dbid']);
			$arr[$k]['type'] = $this->config[$r['traittype']];
		}
		
		return $arr;
	}
}