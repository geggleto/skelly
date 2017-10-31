<?php

use MyApp\Http\LogoutUser;
use MyApp\Http\ProcessLogin;
use MyApp\Http\ResetEmail;
use MyApp\Http\ResetPassword;
use MyApp\Http\ShowDashboard;
use MyApp\Http\ShowLogin;
use MyApp\Http\ShowProfile;
use MyApp\Http\UpdateUser;
use MyApp\Http\User\ShowUserList;
use MyApp\Middleware\SecurityMiddleware;

//Unsecured Routes should go outside the closure
$app->get('/', ShowLogin::class);
$app->post('/login', ProcessLogin::class);

//Reset Email
$app->post('/api/email/reset', ResetEmail::class);
$app->get('/email/activate/{token}', ResetPassword::class);

// Routes that need users to be logged in should go inside this closure
$app->group('', function () {
    $this->get('/dashboard', ShowDashboard::class);
    $this->get('/profile', ShowProfile::class);
    $this->get('/users', ShowUserList::class);
    $this->get('/user/new', '');
    $this->get('/user/edit/{uuid}', '');

    $this->group('/api', function () {
        $this->put('/profile', UpdateUser::class);
    });
})->add(SecurityMiddleware::class);

$app->get('/logout', LogoutUser::class);