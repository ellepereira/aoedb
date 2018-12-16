<?php

class defaultxml extends model
{
    protected $xmlfiles, $saveto;

    public function __construct(&$parent)
    {
        parent::__construct($parent);

        /*$this->table = 'techtree';
        $this->idfield = 't_id';
        $this->orderby_field = 't_id';*/

        $this->config = $this->parent->config;
        $this->datapath = $this->parent->config['datapath'];
        $this->xmlfiles = $this->config['exportxml'];
        $this->saveto = $this->config['exportpath'];

    }

    public function export_all()
    {
        foreach ($this->xmlfiles as $file) {
            $XML = new XMLReader();
            $fileName = $this->config['datapath'] . $file['file'];

            echo '<br/>';
            echo $fileName;

            if (file_exists($fileName) == false) {
                echo ' - does not exist..';
                continue;
            }

            $XML->open($fileName);

            while ($XML->read()) {
                if ($XML->nodeType != 1) {
                    continue;
                }

                if (strcasecmp($XML->name, $file['mainelement']) == 0) {

                    if (isset($file['nameelement'])) {
                        $el = $XML->expand();
                        $fel = $el->getElementsByTagName($file['nameelement'])->item(0);
                        $filename = $fel->nodeValue;

                    } else if (isset($file['nameattribute'])) {
                        $filename = $XML->getAttribute($file['nameattribute']);
                    } else if (empty($filename)) {
                        continue;
                    }

                    $filename = str_replace('/', ' ', trim($filename));
                    $dir = $this->saveto . $file['mainelement'] . '/';
                    $filename = $filename . '.xml';

                    if (!is_dir($dir)) {
                        mkdir($dir, 0777);
                    }

                    file_put_contents($dir . $filename, $XML->readOuterXml());

                }
            }
        }
    }

}
