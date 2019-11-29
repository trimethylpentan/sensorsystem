<?php

namespace Sensors\Application\DataObject;

use Ratchet\ConnectionInterface;

final class Client
{
    /** @var ConnectionInterface */
    private $connection;

    /** @var string */
    private $identifier;

    public function __construct(ConnectionInterface $connection, string $identifier)
    {
        $this->connection = $connection;
        $this->identifier = $identifier;
    }

    public function sendMessage(string $message): void
    {
        $this->connection->send($message);
    }

    public function close(string $message = ''): void
    {
        if ($message !== '') {
            $this->connection->send($message);
        }

        $this->connection->close();
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }
}
