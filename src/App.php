<?php


namespace MyApp;


use DI\ContainerBuilder;

class App extends \DI\Bridge\Slim\App
{
    protected function configureContainer(ContainerBuilder $builder)
    {
        $dotenv = new \Dotenv\Dotenv(__DIR__ . '/../config');
        $dotenv->load();

        $builder->addDefinitions(__DIR__ . '/../config/di-container.php');
    }
}