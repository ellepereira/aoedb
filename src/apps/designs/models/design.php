<?php

class design extends model
{
    protected $xmlpath;

    public $info;

    public function __construct(&$parent)
    {
        parent::__construct($parent);

        $this->table = 'designs';
        $this->idfield = 'des_id';
        $this->orderby_field = 'des_id';
        $this->config = $this->parent->config;
        $this->xmlpath = $this->parent->config['designspath'];
    }

    public function load($name)
    {
        $this->info = $this->get($name);
        $this->info['output'] = $this->getOutput($this->info);

        if (strlen($this->info['input']) > 0) {
            $materials = explode(',', $this->info['input']);
            $wherestring = 'WHERE ';
            foreach ($materials as $material) {
                $matstemp = explode('=', $material);
                $matcounts[$matstemp[0]] = $matstemp[1];
                $wherestring .= "materials.name = '{$matstemp[0]}' OR ";
            }
            $wherestring = substr($wherestring, 0, -4);

            $query = 'select name, icon, rarity, displayname.string as displayname from materials left join strings as displayname on displayname.stringid = materials.displaynameid ' . $wherestring;

            $mats = $this->db->query($query)->results(null, true);

            foreach ($mats as $mat) {
                $this->info['materials'][] = array('name' => $mat['name'], 'displayname' => $mat['displayname'], 'icon' => $mat['icon'], 'count' => $matcounts[$mat['name']], 'rarity' => $mat['rarity']);
            }
        }

        return $this;
    }

    private function getOutput($info)
    {
        switch ($info['outputtype']) {
            case 'trait':
                $output = $this->db->query("select name, dbid, rarity, icon,
        displayname.string as displayname
        from traits
        left join strings as displayname on displayname.stringid = traits.displaynameid
        where traits.name = '{$info['output']}' LIMIT 1")->results();
                break;
            case 'consumable':
                $output = $this->db->query("select name, rarity, icon,
        displayname.string as displayname
        from consumables
        left join strings as displayname on displayname.stringid = consumables.displaynameid
        where consumables.name = '{$info['output']}' LIMIT 1")->results();
                break;
            case 'material':
                $output = $this->db->query("select name, rarity, icon,
        displayname.string as displayname
        from materials
        left join strings as displayname on displayname.stringid = materials.displaynameid
        where materials.name = '{$info['output']}' LIMIT 1")->results();
                break;
            default:
                break;
        }
        return $output;
    }

    public function get($name)
    {
        return $this->db->query("select name, icon, offertype, rarity, stacksize, productionpoints, tag, outputtraitlevel, input, cost, outputtype, output,
        displayname.string as displayname,
        rollovertext.string as rollovertext
        from designs
        left join strings as displayname on displayname.stringid = designs.displaynameid
        left join strings as rollovertext on rollovertext.stringid = designs.rollovertextid
        where designs.name = '{$name}' LIMIT 1")->results();
    }

    public function get_all_by_type($type)
    {
        $typetables = array('trait' => 'traits', 'material' => 'materials', 'consumable' => 'consumables');

        $typetable = $typetables[$type];
        $arr = $this->db->query("select designs.name, designs.rarity, designs.icon, designs.tag, designs.cost, designs.tradeable,
        displayname.string as displayname,
        rollovertext.string as rollovertext
        from designs
        left join {$typetable} as type on type.name = designs.output
        left join strings as displayname on displayname.stringid = type.displaynameid
        left join strings as rollovertext on rollovertext.stringid = type.rollovertextid
        where designs.outputtype = '{$type}'
    	ORDER BY displayname")->results(null, true);

        /*foreach ($arr as $k=>$r) {
        $arr[$k]['output'] = $this->getOutput($r);
        }*/
        return $arr;
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
            'productionpoints',
            'autolearn',
            'autorepeat',
            'advanced',
            'ignoreschool',
            'tag',
            'budgetmodifier',

            'outputtraitlevel',
        );

        while ($XMLReader->read()) {
            if ($XMLReader->nodeType == XMLReader::END_ELEMENT || $XMLReader->name != "econdesign") {
                continue;
            }

            $doc = new DomDocument('1.0');
            $doc->loadXML($XMLReader->readOuterXml());

            $item['name'] = $doc->documentElement->getAttribute('name');

            // Most of the easy stuff
            foreach ($doc->documentElement->childNodes as $node) {
                if ($node->nodeType != 1) {
                    continue;
                }

                //if it's one of our fields up there
                if (in_array($node->tagName, $fields)) {
                    $item[$node->tagName] = $node->nodeValue;
                } else if ($node->tagName == "input") {
                    foreach ($node->childNodes as $n) {
                        if ($n->nodeType == XMLReader::TEXT) {
                            continue;
                        }

                        if (isset($item['input'])) {
                            $item['input'] .= ',';
                        } else {
                            $item['input'] = '';
                        }

                        $item['input'] .= "{$n->nodeValue}={$n->getAttribute('quantity')}";
                    }
                } else if ($node->tagName == "sellcostoverride") {
                    $n = $node->getElementsbyTagName('capitalresource')->item(0);
                    $item['cost'] = $n->getAttribute('quantity');
                } else if ($node->tagName == "output") {
                    $n = $node->childNodes->item(1);

                    $item['outputtype'] = $n->tagName;
                    $item['output'] = $n->nodeValue;
                }

            }

            // Fix icon filenames
            $item = str_replace('\\', '/', $item);

            $this->quicksave($item);

            unset($item);
        }
    }
}
