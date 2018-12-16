<?php

class techtree extends model
{
    protected $xmlpath;

    public function __construct(&$parent)
    {
        parent::__construct($parent);

        $this->table = 'techtree';
        $this->idfield = 't_id';
        $this->orderby_field = 't_id';
        $this->config = $this->parent->config;
        $this->xmlpath = $this->parent->config['techtreepath'];
    }

    /**
     * Method to update all database entries by re-reading the XML data files for techs
     * WARNING will overright any changes made on the database
     */
    public function db_update()
    {
        $XMLReader = new XMLReader();
        $XMLReader->open($this->xmlpath);

        $this->delete_all();
        $this->db->clear_table('techeffects');

        $techfields = array('DBID',
            'DisplayNameID',
            'ResearchPoints',
            'Status',
            'Icon',
            'RolloverTextID');

        $techEffectAttributes = array('type',
            'amount',
            'scaling',
            'subtype',
            'allactions',
            'relativity',
            'proto',
            'culture',
            'newName',
            'action',
            'unittype');

        while ($XMLReader->read()) {
            if ($XMLReader->nodeType == XMLReader::END_ELEMENT || $XMLReader->name != "Tech") {
                continue;
            }

            $doc = new DomDocument('1.0');
            $doc->loadXML($XMLReader->readOuterXml());

            $tech['name'] = $doc->documentElement->getAttribute('name');
            $tech['type'] = $doc->documentElement->getAttribute('type');

            // Most of the easy stuff
            foreach ($doc->documentElement->childNodes as $node) {
                if ($node->nodeType != 1) {
                    continue;
                }

                if (in_array($node->tagName, $techfields)) {
                    $tech[$node->tagName] = $node->nodeValue;
                }

            }

            // Flags
            $Flags = $doc->documentElement->getElementsByTagName('Flag');
            if ($Flags->length > 0) {
                $tech['Flags'] = '';
                foreach ($Flags as $Flag) {
                    $tech['Flags'] .= $Flag->nodeValue . ',';
                }
                $tech['Flags'] = substr($tech['Flags'], 0, -1);
            }

            // Cost
            $Costs = $doc->documentElement->getElementsByTagName('Cost');
            if ($Costs->length > 0) {
                foreach ($Costs as $Cost) {
                    $resource = $Cost->getAttribute('resourcetype');
                    switch ($resource) {
                        case 'Food':
                            $unit['CostFood'] = $Cost->nodeValue;
                            break;
                        case 'Wood':
                            $unit['CostWood'] = $Cost->nodeValue;
                            break;
                        case 'Gold':
                            $unit['CostGold'] = $Cost->nodeValue;
                            break;
                        case 'stone':
                            $unit['CostStone'] = $Cost->nodeValue;
                            break;
                    }
                }
            }

            // Prereqs
            $Prereqs = $doc->documentElement->getElementsByTagName('Prereqs');
            if ($Prereqs->length > 0) {
                $Prereqs = $Prereqs->item(0);

                // SpecificAge
                $SpecificAge = $Prereqs->getElementsByTagName('SpecificAge');
                if ($SpecificAge->length > 0) {
                    $SpecificAge = $SpecificAge->item(0);
                    $tech['PrereqSpecificAge'] = $SpecificAge->nodeValue;
                }

                // TechStatus
                $TechStatuses = $Prereqs->getElementsByTagName('TechStatus');
                if ($TechStatuses->length > 0) {
                    $tech['PrereqsTechStatus'] = '';
                    $tech['Prereqs'] = '';
                    foreach ($TechStatuses as $TechStatus) {
                        $tech['PrereqsTechStatus'] .= $TechStatus->getAttribute('status') . ',';
                        $tech['Prereqs'] .= $TechStatus->nodeValue . ',';
                    }
                    $tech['PrereqsTechStatus'] = substr($tech['PrereqsTechStatus'], 0, -1);
                    $tech['Prereqs'] = substr($tech['Prereqs'], 0, -1);
                }
            }

            // Effects
            $techEffects = $doc->documentElement->getElementsByTagName('Effects');
            if ($techEffects->length > 0) {
                $techEffects = $techEffects->item(0);
                $techEffects = $techEffects->getElementsByTagName('Effect');
                if ($techEffects->length > 0) {
                    foreach ($techEffects as $techEffectElement) {
                        $techEffect['DBID'] = $tech['DBID'];
                        foreach ($techEffectAttributes as $techEffectAttribute) {
                            $techEffect[$techEffectAttribute] = $techEffectElement->getAttribute($techEffectAttribute);
                        }

                        $techEffectTarget = $techEffectElement->getElementsByTagName('Target');
                        if ($techEffectTarget->length > 0) {
                            $techEffectTarget = $techEffectTarget->item(0);
                            $techEffect['targettype'] = $techEffectTarget->getAttribute('type');
                            $techEffect['target'] = $techEffectTarget->nodeValue;
                        }

                        $this->db->insert('techeffects', $techEffect);

                        //print_r($techEffect);

                        unset($techEffect);

                    }
                }
            }

            // Fix icon filenames
            $tech = str_replace('\\', '/', $tech);

            $this->quicksave($tech);

            unset($tech);
        }

        /*$this->gather_file_names($this->config['techpath']);

    foreach($this->questfiles as $questfile)
    {
    $this->xmltodb($questfile);
    }*/
    }
}
