<?php

use MyApp\App;
use MyApp\Models\Authentication\Entities\User;
use MyApp\Models\Authentication\Repositories\UserRepository;

if ($argc != 5) {
    print "Usage: php newUser.php <email> <password> <firstName> <lastName>\n";
    die();
}

include_once __DIR__ . '/../vendor/autoload.php';

$app       = new App();
$container = $app->getContainer();
$repo      = $container->get(UserRepository::class);

$user = new User(
    $argv[1],
    $argv[2],
    $argv[3],
    $argv[4]
);

$repo->storeUser($user);

print "User Created with ID: {$user->getId()->toString()}\n";
