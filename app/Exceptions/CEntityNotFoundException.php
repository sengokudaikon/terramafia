<?php

namespace App\Exceptions;

use Doctrine\ORM\EntityNotFoundException;
use Throwable;

abstract class CEntityNotFoundException extends EntityNotFoundException
{
    private $messageLayout = "was not found!";

    public function __construct(string $entityId = null, string $message = "", int $code = 0, Throwable $previous = null)
    {
        if (!is_null($entityId) && empty($message)) {
            $message = $this->buildMessage($entityId);
        }

        if (is_null($entityId) && empty($message)) {
            $message = "{$this->getEntityName()} {$this->messageLayout}";
        }

        parent::__construct($message, $code, $previous);
    }

    private function buildMessage(string $entityId)
    {
        return "{$this->getEntityName()} with id {$entityId} was not found!";
    }

    abstract public function getEntityName(): string;
}
