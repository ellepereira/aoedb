<?php
spl_autoload_register('autoload');

function autoload($class, $folders = null)
{
    global $script_folders;

    //we didn't specify any folder, so we look in all of them
    if ($folders == null) {
        //First check if it's an app
        if (load_app($class)) {
            return true;
        }
        //If true, exit here

        //it's not an app, so we want to look into all script folders
        $folders = $script_folders;
    } else {
        //change it into an array if it's not
        if (!is_array($folders)) {
            $folders = array($folders);
        }

    }

    foreach ($folders as $folder) {
        $path = $folder . '/' . $class . '.php';
        if (file_exists(realpath($path))) {
            require_once $path;
            return true;
        }
    }

    return false;
}

function load_app($app)
{
    if (in_array($app, scandir('apps'))) {
        require_once "apps/$app/$app.php";
        return true;
    }

    return false;
}
