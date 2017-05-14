<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// composer autoload magic
require 'vendor/autoload.php';

// import configs for the app
require 'config/app.php';
require 'config/db.php';

require 'controllers/MainController.php';
require 'mapper/HostMapper.php';
require 'mapper/XconnMapper.php';
require 'mapper/ImageMapper.php';

use Mind\db\HostMapper as hMapper;
use Mind\db\XconnMapper as xMapper;

// setup the app
$app = new \Slim\App(['settings' => $config]);

session_start();


//add dependancies to the container
$container = $app->getContainer();

// add functionality to the container

$container['logger'] = function($c) {
    $logger = new \Monolog\Logger('my_logger');
    $file_handler = new \Monolog\Handler\StreamHandler("logs/app.log");
    $logger->pushHandler($file_handler);
    return $logger;
};

$container['db'] = function($c) {
    $db = $c['settings']['db'];
    $pdo = new PDO("mysql:host=" . $db['host'] . ";dbname=" . $db['dbName'], 
    $db['user'], $db['pass']);
    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    return $pdo;
};

$container['view'] = function($c) {
    $view = new League\Plates\Engine("templates", "tpl");
    return $view;
};

$app->get('/', \MainController::class . ':index');

$app->post('/xconn/add', \MainController::class . ':addXconn');
$app->get('/xconn/delete/{id}', \MainController::class . ':delXconn');

$app->post('/device/add', \MainController::class . ':addDev');
$app->get('/device/delete/{id}', \MainController::class . ':delDev');

$app->run();