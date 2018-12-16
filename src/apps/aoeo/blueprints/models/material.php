<?php

class blueprint extends model
{
  protected $xmlpath;
  
	function __construct(&$parent)
	{
		parent::__construct($parent);
	
		$this->table = 'materials';
		$this->idfield = 'ma_id';
		$this->orderby_field = 'ma_id';
		$this->config = $this->parent->config;
		$this->xmlpath = $this->parent->config['materialspath'];
	}
	
	/**
	* Method to update all database entries by re-reading the XML data files for traits
	* WARNING will overright any changes made on the database
	*/
	public function db_update()
	{
		
	    $XMLReader = new XMLReader();
	    $XMLReader->open($this->xmlpath);
	       
	    $this->delete_all();
	    
	    $fields = array(
	          'displaynameid',
	    	  'rollovertextid',
	          'icon',
	          'offertype',
		      'tradeable',
	    	  'destroyable',
	    	  'sellable',
	   		  'rarity',
	          'stacksize',
	          'itemlevel',
	          
	          'budgetcost'
		      );
          
	    while ($XMLReader->read()) 
	    {
		      if ($XMLReader->nodeType == XMLReader::END_ELEMENT || $XMLReader->name != "material")
		        continue;
		      
		      $doc = new DomDocument('1.0');
		      $doc->loadXML($XMLReader->readOuterXml());
		      
		      $item['name'] = $doc->documentElement->getAttribute('name');
		      //$trait['type'] = $doc->documentElement->getAttribute('type');
		      
		      // Most of the easy stuff
		      foreach ($doc->documentElement->childNodes as $node) {
		        if ($node->nodeType != 1)
		          continue;
		        
		        //if it's one of our fields up there
		        if (in_array($node->tagName, $fields))
		        {
		          $item[$node->tagName] = $node->nodeValue;
		        }
		        else if($node->tagName == "capitalresource")
		        {
		        	$item['costmultiplier'] = $node->nodeValue;
		        }
		      }
		      
		      
		      // Fix icon filenames
		      $item = str_replace('\\', '/', $item);
		      
		      $this->quicksave($item);
		      
		      unset($tech);
	    }
	}
}

?>