<?php
class traiteffect extends model
{
  protected $xmlpath;
  
	function __construct(&$parent, $level = 43)
	{
		parent::__construct($parent);
	
		$this->table = 'traiteffects';
		$this->idfield = 'te_id';
		$this->orderby_field = 'te_id';
		
		$this->level = $level;
		
		//Load unit types
		$this->load->config('units', true);
		$this->attackTypes = $this->config['units']['attackTypes'];
		$this->unitTypes = $this->config['units']['unitTypes'];
		 
	}
		
	public function loadByArray($arr)
	{
		$this->info = $arr;
	}
	
	public function amountLeveled($level, $reverse=false)
	{
		if($this->relativity == "Percent")
			$r = number_format(($this->amount+$this->scaling*$level - 1)*100, 1);
		else
			$r = number_format(($this->amount+$this->scaling*$level), 1);
		
		if($reverse)
			$r = ($r-100);

		return $r;
	}
	
	protected function translateUnitType()
	{
		$type = $this->unittype;
	
		$postFixes = array(
			'Tree' => 'Wood',
			'Huntable' => 'Hunted Animals',
			'Herdable' => 'Herded Animals',
			'TownCenter' => 'Town Center'
		);
	
		if(isset($this->unitTypes[$type]))
			return $this->unitTypes[$type];
	
		$retS = str_replace('Abstract', '', $type);
		$retS = str_replace('Convertable', '', $retS);
	
		if(isset($postFixes[$retS]))
			return $postFixes[$retS];
	
		return $retS;
	
	}
	
	protected function translateAttackType($type = null)
	{
		if($type == null)
			$type = $this->action;
		
		$postFixes = array();
		
		if(isset($this->attackTypes[$type]))
			return $this->attackTypes[$type];
		
		$retS = str_replace('Attack', '', $type);
		
		if(isset($postFixes[$retS]))
			return $postFixes[$retS];
		
		return $retS;
		
	}
	
	public function __toString()
	{
		$string = '';
		
		$effectnames = array(
		'Hitpoints' => 'Health',
		'LOS' => 'Line-of-sight',
		'Damage' => 'Damage',
		'CostAll' => 'Cost',
		'BuildPoints' => 'Build Time',
		'TrainPoints' => 'Train Time',
		'DamageBonusReduction' => 'Bonus Damage Protection',
		'MaximumVelocity' => 'Movement Speed',
		'AreaDamageReduction' => 'Area Damage Reduction',
		'TargetSpeedBoostResist' => 'Snare Resist',
		'TargetSpeedBoost' => 'Snare',
		'ConvertResist' => 'Conversion Resistance',
		'BuildingWorkRate' => 'Research/Train Time',
		'MaximumRange' => 'Maximum Range',
		'HitPercent' => 'Critical Hit Chance',
		);
		
		if($this->subtype == "CarryCapacity")
		{
			$string .= "Carry {$this->resource}";
		}
		
		else if($this->subtype == "Armor")
		{
			$string .= "{$this->translateAttackType($this->damagetype)} Armor";
		}
		
		else if($this->subtype == "DamageBonus")
		{
			$string .= "{$this->translateUnitType()} Bonus Damage";
		}
		
		else if($this->subtype == "ActionEnable")
		{
			$string .= "Enable: {$this->action}";
		}
		
		else if($this->action == "")
		{

			if(!empty($this->info['unittype']))
			{
				$string .= $this->translateUnitType();
			}
			
			else if(isset($effectnames[$this->subtype]))
			{
				$string .= $effectnames[$this->subtype];
			}
					
			else 
			{
				$string .= "{$this->subtype} CHANGEME";
			}
		}
		else
		{
								
			if($this->subtype == "WorkRate")
			{
				switch($this->action)
				{
					case "Gather":
						$string.= "Gathering {$this->translateUnitType()}";
						break;					
					case "FishGather":
						$string.= "Gathering Fish";
						break;
					case "Convert":
						$string .="Convert {$this->translateUnitType()} Rate";
						break;
					case "Build":
						/* Commented section caused ticket 57
						if($this->unittype != 'Building')
							$string.="{$this->translateUnitType()} Building Speed";*/
						// Fix for ticket 57:
              $string .= "{$this->translateUnitType()} Speed";
						break;
					case "SelfHeal":
						$string .= "Heal (per second)";
						break;
					case "Heal":
						$string .= "Healing";		
						break;
					case "Empower":
						if($this->unittype == 'ActionBuild')
							$u = 'Build Rate';
						else if ($this->unittype == 'ActionTrain')
							$u = 'Train Rate';
						else
							$u = $this->unittype;
						
						$string .= "Empower $u";
						break;
					default:
						$string .= "{$this->action} {$this->translateUnitType()}";
					break;
				}	
			}
			
					
			else if($this->subtype == "HitPercent")
			{
				$string .= "{$this->translateAttackType()} Critical Hit Chance";
			}
			
			else if(isset($effectnames[$this->subtype]))
			{
				$string .= $effectnames[$this->subtype];
			}
			
			
		}
		
		$amountLeveled = $this->amountLeveled($this->level);
		
		
		if($this->subtype != "ActionEnable")
		{
			if($this->subtype == 'WorkRate' && $this->action == "Convert")
				$amountLeveled = $this->amountLeveled($this->level, false);
			
			$string .= ': ';
			
			if($this->bonus == "true" && $amountLeveled < 0 && $this->subtype != 'CostAll' && $this->subtype != "BuildPoints" && $this->subtype != "TrainPoints")
				$amountLeveled *= -1;
			
			if ($amountLeveled > 0)
	      		$string .= '+';
			
	      	
			$string .= $amountLeveled;
			
			if($this->relativity == "Percent")
				$string .='%';
		}
		
		return $string;
	}
	
}

/**end of file*/