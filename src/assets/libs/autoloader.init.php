<?php
// enable error reporting for debuging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Generic class autoloader
spl_autoload_register(function($class_name)
    {
        $directories = array(
            'assets/handlers/',
            'assets/controllers/',
            'assets/libs/',
            'assets/models/'
        );

        foreach($directories as $directory)
        {
            $file_and_path = $directory . $class_name . '.class.php';
            if(is_file($file_and_path))
            {
                require $file_and_path;
                return;
            }
        }
    }
);