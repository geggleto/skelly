<?php


namespace MyApp\Core;

use Ramsey\Uuid\UuidInterface;

interface RepositoryInterface
{
    public function find(UuidInterface $uuid);

    public function getReferenceFor(UuidInterface $uuid);
}