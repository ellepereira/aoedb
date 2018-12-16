<?php

class unit extends aoemodel
{
    public function __construct(&$parent)
    {
        parent::__construct($parent);

        /**CONFIGURATION*/
        $this->table = 'proto';
        $this->idfield = 'dbid';
        $this->orderby = 'name';
        $this->where = '';

        $this->fields = array('DBID', 'name', 'MaxVelocity', 'MaxRunVelocity', 'MovementType', 'Icon', 'PortraitIcon', 'InitialHitPoints'
            , 'MaxHitPoints', 'LOS', 'PopulationCount', 'PopulationCapAddition', 'TrainPoints', 'CostFood', 'CostWood', 'CostGold',
            'CostStone', 'Bounty', 'Trait1', 'Trait2', 'Trait3', 'Trait4', 'Trait5', 'AllowedAge', 'UnitTypes', 'ArmorRanged', 'ArmorHand', 'ArmorCavalry', 'ArmorSiege',
            'Flags');

        $this->add_on_fields = array('protoactions' => 'DBID');
        $this->string_fields = array('DisplayNameID' => 'DisplayName', 'RolloverTextID' => 'RolloverText', 'ShortRolloverTextID' => 'ShortRolloverText');

        /**END CONFIGURATION*/

    }
}