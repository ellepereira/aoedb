<?php

class loader extends component
{
    /**
     * Array of loaded items.
     * Cadeia de itens carregados.
     * @var array
     */
    public $loaded;

    /**
     * Constructs a new loader
     * Constroi um novo loader
     * @param $parent - objecto qual vai receber os recursos do loader
     * @return unknown_type
     */
    public function __construct(&$parent = null)
    {
        parent::__construct($parent);
        $this->loaded = array();
    }

    /**
     * Loads an application
     * Carrega uma applicacao
     * @param $appname
     * @return bool true/false
     */
    public function app($appname)
    {
        if (!load_app($appname)) {
            return false;
        }

        $this->loaded[] = array('app' => $appname);
        $this->parent->$appname = new $appname($this->parent);

        return true;
    }

    /**
     * Loads a model
     * Carrega um modelo
     * @param $modelname
     * @param $var_prefix
     * @return unknown_type
     */
    public function model($modelname, $var_prefix = 'm_')
    {
        $path = 'apps/' . get_class($this->parent) . '/models';
        autoload($modelname, $path);
        $var_name = $var_prefix . $modelname;
        $this->parent->$var_name = new $modelname($this->parent);
        //echo "new $modelname($this->parent);";

        $this->loaded[] = array('model' => $modelname);
    }

    /**
     * Displays a view
     * Mostra uma view
     * @param $viewname
     * @return unknown_type
     */
    public function view($viewname, $data = null)
    {
        //foreach($data as $key=>$value)
        //$$key = $value;

        $path = 'apps/' . get_class($this->parent) . '/views/' . $viewname . EXT;
        $path = realpath($path);

        if (!file_exists($path)) {
            return false;
        }

        require $path;

        $this->loaded[] = array('view' => $viewname);
    }

    /**
     * Loads a database
     * Carrega uma base de dados
     * @param $db
     * @return unknown_type
     */
    public function db($db)
    {
        //require 'components/db/'.$db.EXT;

        autoload($db, 'databases');
        $this->parent->db = new $db($this->parent);
        $this->loaded[] = array('databases' => $db);
    }

    /**
     * Loads a Library
     * Carrega uma bibliteca
     * @param $libname - nome da biblioteca
     * @param $var_prefix - prefixo da variavel ('l_')
     * @param $folder pasta (default: libraries/)
     * @return unknown_type
     */
    public function lib($libname, $args = null, $var_prefix = null)
    {
        $this->load($libname, $args, '', 'libraries');
    }

    /**
     * Loads a component
     * Carrega um componente
     * @param $comname nome do componente
     * @param $args argumento para passar para o componente (se algum)
     * @return unknown_type
     */
    public function component($comname, $args = null)
    {
        return $this->lib($comname, $args, '', 'components');
    }

    /**
     * Loads a configuration file
     * Carrega um arquivo de configuracao
     * @param $configname
     * @param $subconfig determina se o config merge com o do pai ou se usa uma array
     * @return unknown_type
     */
    public function config($configname, $subconfig = false)
    {
        $file_name = 'config/' . $configname . EXT;

        //$this->script($file_name);
        if (!file_exists(realpath($file_name))) {
            return;
        }

        require $file_name;

        //Create our parent's config array if we don't have one
        if (!isset($this->parent->config)) {
            $this->parent->config = array();
        }

        //$config is declared in the included config file
        if (isset($config)) {
            if ($subconfig) {
                $this->parent->config[$configname] = $config;
            } else {
                $this->parent->config = array_merge($this->parent->config, $config);
            }

            unset($config);
        }

        if (isset($$configname)) //POG
        {
            if ($subconfig) {
                $this->parent->config[$configname] = $$configname;
            } else {
                $this->parent->config = array_merge($this->parent->config, $$configname);
            }

            unset($$configname);
        }

        $this->loaded[] = array('config' => $configname);

    }

    /**
     * Main loader function
     * @param $libname
     * @param $args
     * @param $var_prefix
     * @param $folder
     * @return unknown_type
     */
    private function load($libname, $args = null, $var_prefix = '', $folder = '.')
    {
        autoload($libname, $folder);

        $var_name = $var_prefix . $libname;

        $this->parent->$var_name = new $libname($args);

        //load the config file for this lib
        $this->config($libname);

        $this->loaded[] = array($folder => $libname);
    }

    /**
     * PRINT debug text
     * @return unknown_type
     */
    public function debugOUT()
    {
        print_r($this->loaded);
    }

}
