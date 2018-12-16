<?php

class consumable extends model
{
    protected $xmlpath;
    public $info;

    public function __construct(&$parent)
    {
        parent::__construct($parent);

        $this->table = 'consumables';
        $this->idfield = 'co_id';
        $this->orderby_field = 'co_id';
        $this->config = $this->parent->config;
        $this->xmlpath = $this->parent->config['consumablespath'];
    }

    public function load($name)
    {
        $this->info = $this->get($name);

        return $this;
    }

    public function get($name)
    {
        return $this->db->query("select consumables.name, icon, rarity, stacksize, itemlevel, power, cost,
    displayname.string as displayname, rollovertext.string as rollovertext
    from consumables
    left join strings as displayname on displayname.stringid = consumables.displaynameid
    left join strings as rollovertext on rollovertext.stringid = consumables.rollovertextid
    where consumables.name = '{$name}' LIMIT 1")->results();
    }

    public function get_all()
    {
        return $this->db->query("select consumables.name, icon, rarity, stacksize, itemlevel, power, cost,
    displayname.string as displayname, rollovertext.string as rollovertext
    from consumables
    left join strings as displayname on displayname.stringid = consumables.displaynameid
		left join strings as rollovertext on rollovertext.stringid = consumables.rollovertextid
		ORDER BY displayname ASC
    ")->results();
    }

    public function get_all_by_rarity($rarity)
    {
        return $this->db->query("select consumables.name, icon, rarity, stacksize, itemlevel, power, cost,
      displayname.string as displayname, rollovertext.string as rollovertext
      from consumables
      left join strings as displayname on displayname.stringid = consumables.displaynameid
			left join strings as rollovertext on rollovertext.stringid = consumables.rollovertextid
			WHERE rarity='$rarity'
			ORDER BY displayname ASC
      ")->results();
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
            'power',
            'civmatchingtype',
        );

        while ($XMLReader->read()) {
            if ($XMLReader->nodeType == XMLReader::END_ELEMENT || $XMLReader->name != "consumable") {
                continue;
            }

            $doc = new DomDocument('1.0');
            $doc->loadXML($XMLReader->readOuterXml());

            $item['name'] = $doc->documentElement->getAttribute('name');
            //$trait['type'] = $doc->documentElement->getAttribute('type');

            // Most of the easy stuff
            foreach ($doc->documentElement->childNodes as $node) {
                if ($node->nodeType != 1) {
                    continue;
                }

                //if it's one of our fields up there
                if (in_array($node->tagName, $fields)) {
                    $item[$node->tagName] = $node->nodeValue;
                } else if ($node->tagName == "sellcostoverride") {
                    $n = $node->getElementsbyTagName('capitalresource')->item(0);
                    $item['cost'] = $n->getAttribute('quantity');
                }
            }

            // Fix icon filenames
            $item = str_replace('\\', '/', $item);

            $this->quicksave($item);

            unset($tech);
        }
    }
}
