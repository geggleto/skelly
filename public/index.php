<?php

use MyApp\App;

include_once __DIR__ . '/../vendor/autoload.php';

session_name('myapp');
session_start();

$app = new App();

include_once __DIR__ . '/../config/routes.php';

$app->run();