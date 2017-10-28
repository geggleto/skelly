<?php

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Mailgun\Mailgun;
use MyApp\Core\Adapters\MailGunAdapter;
use MyApp\Core\MailService;
use Psr\Container\ContainerInterface;

return [
    'settings.displayErrorDetails' => true,
    'doctrine.paths'               => [
        __DIR__ . '/../src/Models/Authentication/Entities',
    ],
    'mailgun.domain'               => 'mg.glenneggleton.com',
    'email.domain'                 => 'http://admin.local',
    EntityManager::class           => function (ContainerInterface $container) {
        $entityManager = null;

        $config = Setup::createAnnotationMetadataConfiguration(
            $container->get('doctrine.paths'),
            false
        );

        $cache = new \Doctrine\Common\Cache\ArrayCache();
        $config->setMetadataCacheImpl($cache);
        $config->setQueryCacheImpl($cache);
        $config->setAutoGenerateProxyClasses(true);


        $file     = __DIR__ . '/doctrine.php';
        $dbConfig = include $file;

        $entityManager = EntityManager::create($dbConfig, $config);
        $platform      = $entityManager->getConnection()->getDatabasePlatform();
        $platform->registerDoctrineTypeMapping('enum', 'string');

        return $entityManager;
    },
    \Slim\Views\Twig::class        => function (ContainerInterface $container) {
        $view = new \Slim\Views\Twig(__DIR__ . '/../templates', [
            'cache' => false,
            'debug' => true,
        ]);

        // Instantiate and add Slim specific extension
        $basePath = rtrim(str_ireplace('index.php', '', $container->get('request')->getUri()->getBasePath()), '/');
        $view->addExtension(new Slim\Views\TwigExtension($container->get('router'), $basePath));
        $view->addExtension(new Twig_Extension_Debug());

        $view->getEnvironment()->addGlobal('domain', $container->get('email.domain'));

        return $view;
    },
    Mailgun::class                 => function (ContainerInterface $container) {
        return Mailgun::create($_ENV['MAILGUN']);
    },
    MailGunAdapter::class          => function (ContainerInterface $container) {
        return new MailGunAdapter($container->get('mailgun.domain'), $container->get(Mailgun::class));
    },
    MailService::class             => function (ContainerInterface $container) {
        return $container->get(MailGunAdapter::class);
    },
];

