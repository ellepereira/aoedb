<?php

class blueprints extends app
{
    public function __construct(&$parent)
    {
        parent::__construct($parent);

        $this->load->app('aoeo');

        $this->config = $this->aoeo->config;
        $this->load->model('blueprint');

    }

    public function c_index($dbid = null)
    {
        if (!$dbid) {
            $this->c_rarity('cRarityCommon');
        } elseif ($this->m_blueprint->load($dbid)) {
            $this->aoeo->header($this->m_blueprint->displayname . ' Blueprint');
            $blueprint = $this->m_blueprint;
            $this->load->view('blueprint', $blueprint->info);
            $this->aoeo->footer();
        } else {
            $this->aoeo->header('Blueprints');
            echo 'Could not load blueprint';
            $this->aoeo->footer();
        }

    }

    public function c_rarity($rarity)
    {
        $rarities = array('cRarityCommon' => 'Common',
            'cRarityUncommon' => 'Uncommon',
            'cRarityRare' => 'Rare',
            'cRarityEpic' => 'Epic');

        $this->aoeo->header($rarities[$rarity] . ' Blueprints');

        $all = $this->m_blueprint->get_all_by_rarity($rarity);
        $this->load->view('blueprintslist', $all);

        $this->aoeo->footer();
    }

    public function c_update()
    {
        $this->m_blueprint->db_update();
        $this->m_blueprint->db_update_toc();
    }
}
