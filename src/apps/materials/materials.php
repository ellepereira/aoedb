<?php

class materials extends app
{
    public function __construct(&$parent)
    {
        parent::__construct($parent);

        $this->load->app('aoeo');

        $this->config = $this->aoeo->config;
        $this->load->model('material');
    }

    public function c_index($name = null)
    {
        if (!$name) {
            $all = $this->c_rarity('cRarityCommon');
        } else if ($this->m_material->load($name)) {
            $this->aoeo->header($this->m_material->displayname);
            $material = $this->m_material;
            $this->load->view('material', $material->info);
            $this->aoeo->load->view('ad_temp');
            $this->aoeo->footer();
        } else {
            $this->aoeo->header('Materials');
            echo 'Could not load material';
            $this->aoeo->footer();
        }

        $this->aoeo->footer();
    }

    public function c_rarity($rarity)
    {
        $rarities = array('cRarityCommon' => 'Common',
            'cRarityUncommon' => 'Uncommon',
            'cRarityRare' => 'Rare',
            'cRarityEpic' => 'Epic');

        $this->aoeo->header($rarities[$rarity] . ' Materials');
        $all = $this->m_material->get_all_by_rarity($rarity);
        $this->load->view('materialslist', $all);
        $this->aoeo->load->view('ad_temp');
        $this->aoeo->footer();
    }

    public function c_update()
    {
        $this->m_material->db_update();
        $this->m_material->db_update_toc();
    }
}
