<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
//error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);

define("APP_DIR", dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR);
define("VIEWS_DIR", APP_DIR . 'views' . DIRECTORY_SEPARATOR);
define("CONTROLLERS_DIR", APP_DIR . 'controllers' . DIRECTORY_SEPARATOR);
define("MODELS_DIR", APP_DIR . 'models' . DIRECTORY_SEPARATOR);
define("VENDOR_DIR", APP_DIR . 'vendor' . DIRECTORY_SEPARATOR);
define("CORE_DIR", VENDOR_DIR . 'core' . DIRECTORY_SEPARATOR);
define("MEDOO_DIR", VENDOR_DIR);
define("WEB_DIR", APP_DIR . 'web' . DIRECTORY_SEPARATOR);

$config = [
    "org_name" => 'Your organization name',
    "errors" => [
        403 => 'Доступ запрещен',
        404 => 'Запрашиваемая страница не найдена',
        405 => 'HTTP-метод не разрешен',
        801 => 'Controller is not implemented yet',
        802 => 'Model is not implemented yet',
        803 => 'View is not implemented yet',
        804 => 'Unknown action',
        901 => 'Ошибка сохранения данных',
    ],
    "autoload_path" => [
        CORE_DIR, MEDOO_DIR, CONTROLLERS_DIR, MODELS_DIR
    ],
    "routes" => [
        ['route' => '/', 'name' => 'index', 'method' => 'GET'],
        ['route' => '/index', 'name' => 'index', 'method' => 'GET'],
        ['route' => '/profile', 'name' => 'task', 'method' => 'GET'],
        ['route' => '/profile', 'name' => 'task', 'method' => 'POST'],
    ],
    "connection" => [
        'database_type' => 'mysql',
        'database_name' => 'dbname',
        'server' => 'dbserver',
        'username' => 'dbuser',
        'password' => 'dbpass',

        // [optional]
        'charset' => 'utf8',
        'collation' => 'utf8_general_ci',
        'port' => 3306,

        // [optional] Table prefix
        //'prefix' => 'PREFIX_',

        // [optional] Enable logging (Logging is disabled by default for better performance)
        'logging' => true,

        // [optional] MySQL socket (shouldn't be used with server and port)
        //'socket' => '/tmp/mysql.sock',

        // [optional] driver_option for connection, read more from http://www.php.net/manual/en/pdo.setattribute.php
        'option' => [
                PDO::ATTR_CASE => PDO::CASE_NATURAL
        ],

        // [optional] Medoo will execute those commands after connected to the database for initialization
        'command' => [
                'SET SQL_MODE=ANSI_QUOTES'
        ]
    ]
];