<?php

require __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';

spl_autoload_register(function ($class_name) {
    global $config;
    foreach ($config['autoload_path'] as $autoload_path) {
        $file_name = $autoload_path . str_replace('\\', DIRECTORY_SEPARATOR, $class_name) . '.php';
        if (file_exists($file_name)) {
            require $file_name;
        }
    }
});
