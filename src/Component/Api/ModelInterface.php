<?php

declare(strict_types=1);

namespace App\Component\Api;

use Ramsey\Uuid\UuidInterface;

interface ModelInterface
{
    public function setId(UuidInterface $uuid): void;

    public function getId(): UuidInterface;

    public function getCreated(): \DateTime;

    public function setCreated(\DateTime $created): void;

    public function getChanged(): ?\DateTime;

    public function setChanged(?\DateTime $created): void;
}