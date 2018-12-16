<?php

class quest extends model
{
	protected $questfiles;
	public $info, $rewards;
	
	function __construct(&$parent)
	{
		parent::__construct($parent);
		
		$this->config = $this->parent->config;
		$this->questfiles = array();

	}
	
	
	/**
	 * Method to update all database entries by re-reading the XML data files for quests
	 * WARNING will overright any changes made on the database
	 */
	public function db_update()
	{
		$this->gather_file_names($this->parent->aoeo->config['questspath']);

		$this->db->clear_table('quests');
		$this->db->clear_table('questrewards');
		$this->db->clear_table('questplayers');
		
		foreach($this->questfiles as $questfile)
		{
		      if (substr($questfile, -6, 6) == '.quest') 
		      {
		        $this->xmltodb($questfile);
		      }
		}
	}
	
	public function db_update_toc()
	{
		$this->db->query("DELETE FROM tableofcontents WHERE type='quest'");
		
		$all = $this->get_all();
		
		foreach($all as $quest)
		{
			$values = array(
							'dbid' => $quest['uniqueid'],
							'keyword' => mysql_real_escape_string($quest['DisplayName']),
							'searchtext' => mysql_real_escape_string($quest['SummaryText']),
							'type' => 'quest'
			);
			$this->db->insert('tableofcontents', $values);
		}
	}
	
	public function db_update_questgiversart()
	{
		//clear our database
		$this->db->clear_table('questgivers');
	
		//acquire all quest giver arts in one array
		$all = $this->quest_giver_pics();
	
		foreach($all as $fn=>$giver)
		{
			$values = array('filename' => $fn,
								'newquestart' => $giver['newquest'],
								'inprogressart' => $giver['inprogress'],
								'completeart' => $giver['complete']);
			
			//insert this quest giver's arts into database
			$this->db->insert('questgivers', $values);
		}
	}
	
	public function get_all_by_region()
	{
		$all = $this->get_all();
		$results = array();
		
		foreach($all as $quest)
		{
			$results[$quest['mappage']][] = $quest;
		}
		
		return $results;
	}
	
	public function get_all()
	{
		$results = $this->db->query('SELECT
			*,
			displayname.string as DisplayName,
			description.string as Description,
			completiontext.string as CompletionText,
			inprogresstext.string as InProgressText,
			summarytext.string as SummaryText
			from quests
			left join strings as displayname on displayname.stringid = quests.displayname
			left join strings as description on description.stringid = quests.description
			left join strings as completiontext on completiontext.stringid = quests.completiontext
			left join strings as inprogresstext on inprogresstext.stringid = quests.inprogresstext
			left join strings as summarytext on summarytext.stringid = quests.summarytext')->results();		
		
		foreach($results as $key=>$result)
		{
			$results[$key]['rewards'] = $this->db->query("SELECT * FROM questrewards WHERE uniqueid={$result['uniqueid']}")->results();
			$results[$key]['players'] = $this->db->query("SELECT * FROM questplayers WHERE uniqueid={$result['uniqueid']}")->results();
		}
		return $results;
	}
	
	public function get($id)
	{
		$result = $this->db->query('SELECT
				*,
				displayname.string as DisplayName,
				description.string as Description,
				completiontext.string as CompletionText,
				inprogresstext.string as InProgressText,
				summarytext.string as SummaryText
				from quests
				left join strings as displayname on displayname.stringid = quests.displayname
				left join strings as description on description.stringid = quests.description
				left join strings as completiontext on completiontext.stringid = quests.completiontext
				left join strings as inprogresstext on inprogresstext.stringid = quests.inprogresstext
				left join strings as summarytext on summarytext.stringid = quests.summarytext 
				WHERE uniqueid = '.$id.' LIMIT 1')->results();		

			$result['rewards'] = $this->db->query("SELECT * FROM questrewards WHERE uniqueid={$result['uniqueid']}")->results();
			$result['players'] = $this->db->query("SELECT * FROM questplayers WHERE uniqueid={$result['uniqueid']}")->results();
			
		return $result;
	}
	
	private function xmltodb($xmlpath)
	{
		$XMLReader = new XMLReader();
		$XMLReader->open($xmlpath);
		
		echo "$xmlpath <br />";
		
		// uniqueid, version from <quest> tag
		$questfields = array(
		  'displayname',
          'tier',
          'repeatable',
          'offertype',
          'canplaycoop',
          'cooprequired',
          'description',
          'completiontext',
          'inprogresstext',
          'summarytext',
          'canabandon',
          'hiddenquest',
          'friendonly',
          'suppressnotifications',
          'disableelite',
          'customquest',
          'questtype',
          'maplocationx',
          'maplocationy',
          'mapmarker',
          'mappage',
          'elitespawnchance',
          'time',
		   'xp');
		
		$quest = array();
		$quest['folder'] = str_replace($this->parent->aoeo->config['questspath'].'\\','', $xmlpath);
		$quest['folder'] = dirname(addslashes($quest['folder']));
		
		$qtypeinfo = explode('\\', $quest['folder']);
		
		$quest['questline'] = @$this->config['lines'][$qtypeinfo[0]];
		$quest['type'] = @$this->config['types'][$qtypeinfo[2]];
		
		$XMLReader->read();
		$quest['uniqueid'] = $XMLReader->getAttribute('uniqueid');
		
		while($XMLReader->read())
		{
			if($XMLReader->nodeType != 1)
				continue;
			
			if(in_array($XMLReader->name, $questfields))
			{
				$v = $XMLReader->readString();
				
				if(!empty($v))
					$quest[$XMLReader->name] = trim(str_replace('$$', '', $v));
				
			}
			
			else
			{
				switch($XMLReader->name)
				{	
						
					case 'rewards':
						$questrewards = $this->read_rewards($XMLReader->expand(), $quest['uniqueid']);
						break;
						
					case 'questgivers':
						$quest['questgivers'] = $this->questgiverorret($XMLReader->expand());
						break;
					case 'questreturners':
						$quest['questreturners'] = $this->questgiverorret($XMLReader->expand());
						break;
						
					case 'level':
						$questlevels = $this->read_levels($XMLReader->expand());
						
						if(isset($questlevels['min']))
							$quest['minlevel'] = $questlevels['min'];						
						if(isset($questlevels['max']))
							$quest['maxlevel'] = $questlevels['max'];						
						if(isset($questlevels['level']))
							$quest['level'] = $questlevels['level'];					
						break;
						
					case 'playersettings':
						$questplayers[] = $this->read_player($XMLReader->expand(),  $quest['uniqueid']);
						break;
						
					default:
						'did not import field: '."{$XMLReader->name} <br />";
						break;
						
				}
			}
			
			
			
		}

		$this->db->insert('quests', $quest);
		
	}
	
	private function read_player($el, $uid)
	{
		$player = array();
		$player['id'] = $el->getAttribute('id');
		$player['uniqueid'] = $uid;
		
		$playerfields = array(
			'team', 'color', 'playertype', 'playertype', 'character','cooponly','startingfood', 'startinggold','startingwood','startingstone',
			'personality', 'script'
		);
		
		$flags = array('aiflagvariables', 'aislidervariables');
		
		foreach($el->childNodes as $entry)
		{
			if($entry->nodeType != 1)
				continue;
			
			if(in_array($entry->tagName, $playerfields))
			{
				$player[$entry->tagName] = str_replace('$$', '', trim($entry->nodeValue));
			}
			
			else if (in_array($entry->tagName, $flags))
			{
				$varsList = $entry->getElementsByTagName('aivariable');
				
				foreach($varsList as $var)
				{
					
					$key = $var->getElementsByTagName('key')->item(0);					
					//stupid empty keys... WHY?
					if($key)
						$key = $key->nodeValue;
					else
						$key = '';
					
					$value = $var->getElementsByTagName('value')->item(0)->nodeValue;
					
					if(!isset($player[$entry->tagName]))
						$player[$entry->tagName] = "{$key}={$value}";
					else
						$player[$entry->tagName] .= ",{$key}={$value}";
				}
				
			}
			
			else 
			{
				echo "Could not read {$entry->tagName} with value {$entry->nodeValue} <br />";
			}
		}
		
		$this->db->insert('questplayers', $player);
		
		return $player;
		
	}

	private function read_levels($el)
	{
		$ret = array();
		
		if($el->childNodes->length == 1)
		{
			$ret['level'] = $el->nodeValue;
		}
		else 
		{
			$ret['min'] = $el->getElementsByTagName('min')->item(0)->nodeValue;
			$ret['max'] = $el->getElementsByTagName('max')->item(0)->nodeValue;
		}
		
		return $ret;
	}
	
	private function questgiverorret($el)
	{
		$retString = null;
		
		foreach($el->childNodes as $v)
		{
			if($v->nodeType!=1)
				continue;
			
			if(!isset($retString))
				$retString = $v->nodeValue;
			else 
				$retString .= ",{$v->nodeValue}";
			
		}
		
		return $retString;
	}
	
	private function read_rewards($el, $qid, $pickone = false)
	{
		$rewards = array();
		
		$rewardfields = array(
			'capitalresource' => array('capitalresource', 'amount'),
			'material' => array('materialname', 'count'),
			'consumematerial' => array('materialname', 'count'),
			'blueprint' => null,
			'loottable' => null,
			'advisor' => null,
			'lockregion' => null,
			'unlockregion' => null,
			'capitaltech' => null,
			'consumable' => array('consumablename', 'count'),
			'questgiver' => array('name', 'status'),
			'trait' => null
		);
		
		
		$rest = array('mailreward');
		$ignore = array('or', 'protip', 'enableprotip', 'xp');
		
		foreach($el->childNodes as $meh)
		{
			if($meh->nodeType != 1)
				continue;
			
			if(array_key_exists($meh->tagName, $rewardfields))
			{
				$nreward = $this->rewards_helper($meh, $rewardfields[$meh->tagName], true);
				$nreward['pickone'] = $pickone;
				$nreward['uniqueid'] = $qid;
				$rewards[] = $nreward;
				
			}
			
			else if(in_array($meh->tagName, $rest))
			{
				$rewards[] = array('name' => $meh->tagName, 'value' => trim($meh->nodeValue), 'type' => $meh->tagName, 'uniqueid' => $qid,'pickone' => $pickone);
			}
			
			else if(!in_array($meh->tagName, $ignore))
			{
				echo "what's {$meh->tagName} = {".htmlentities($meh->textContent)."} ? ";
				echo "<br />";
			}
				
		}
		
		//$reward['visible'] = $el->getAttribute('visible');
		
	
		$ors = $el->getElementsByTagName('or');
		
		if($ors->length > 0)
		{
			$reward = array_merge($this->read_rewards($ors->item(0), $qid, true), $rewards);
			unset($ors);
		}
		
		foreach($rewards as $reward)
		{
			$this->db->insert('questrewards', $reward);
		}
		
		return $rewards;
	}
	
	private function rewards_helper($el, $lookingfor, $needVisible = true)
	{
		$results = array();

		if(($needVisible == true && $el->getAttribute('visible') != null) || (!$needVisible))
		{	
			if(!$lookingfor)
			{
				$results = array('name' => $el->tagName, 'value' => trim($el->nodeValue), 'type' => $el->tagName);
			}
			else
			{
				foreach($el->childNodes as $look)
				{
					if($look->nodeType != 1)
						continue;
					
					if(in_array($look->tagName, $lookingfor))
					{
						if(strcmp($look->tagName, $lookingfor[0]) === 0)
							$results['name'] = trim($look->nodeValue);
						if(strcmp($look->tagName, $lookingfor[1]) === 0)
							$results['value'] = trim($look->nodeValue);
					}
					
					$results['type'] = $el->tagName;
				}
			}

		}
			
			return $results;
	}
	
	private function gather_file_names($dir)
	{
		if(!is_dir($dir))
			return null;
		
		$d = dir($dir);
		
		while (false !== ($entry = $d->read())) 
		{
			if(strpos($entry, '.') === 0)
				continue;
			
			$fullpath = $d->path.'\\'.$entry;
			
			if(strpos($entry, 'quest') != false)
				$this->questfiles[] = $fullpath;
			
			else if(is_dir($fullpath))
				$this->gather_file_names($fullpath);
		}
		
		return true;
	}
	
}

?>