<?php

\Doctrine\DBAL\Types\Type::addType('uuid', 'Ramsey\Uuid\Doctrine\UuidType');

return [
    'driver'   => 'pdo_mysql',
    'user'     => $_ENV['DOCTRINE_USERNAME'],
    'password' => $_ENV['DOCTRINE_PASSWORD'],
    'dbname'   => $_ENV['DOCTRINE_DATABASE'],
    'host'     => $_ENV['DOCTRINE_HOST'],
    'charset'  => 'utf8',
    'port'     => $_ENV['DOCTRINE_PORT'],
];
