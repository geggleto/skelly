<?php

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use MyApp\App;

include_once __DIR__ . '/vendor/autoload.php';

$app = new App();

$container = $app->getContainer();

$entityManager = $container->get(EntityManager::class);

return ConsoleRunner::createHelperSet($entityManager);



