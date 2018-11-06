<?php
use App\CommandHandler;
use App\ConfigHandler;
use App\DI\Container;

require 'vendor/autoload.php';

$parameters = require 'configs/parameters.php';
$services = require 'configs/services.php';

$di = new Container($services, $parameters);

$app = new CommandHandler($di);
$app->handle($argv);