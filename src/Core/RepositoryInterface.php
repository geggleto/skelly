<?php


namespace MyApp\Core;


use Ramsey\Uuid\Uuid;

interface RepositoryInterface
{
    public function find(Uuid $uuid);

    public function getReferenceFor(Uuid $uuid);
}