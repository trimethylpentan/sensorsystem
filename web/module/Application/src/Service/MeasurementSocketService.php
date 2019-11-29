<?php declare(strict_types=1);

namespace Sensors\Application\Service;

use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use Sensors\Application\Controller\SocketController;
use Sensors\Application\DataObject\Client;
use Sensors\Application\DataObject\UtcDateTime;
use Sensors\Application\Repository\MeasurementRepository;

class MeasurementSocketService implements MessageComponentInterface
{
    /** @var MeasurementRepository */
    private $measurementRepository;

    /** @var Client[] */
    private $clients = [];

    /** @var UtcDateTime */
    private $lastFetch;

    public function __construct(MeasurementRepository $measurementRepository)
    {
        $this->measurementRepository = $measurementRepository;
        $this->lastFetch             = UtcDateTime::create();

        echo PHP_EOL, 'Socket started successfully', PHP_EOL, PHP_EOL;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        echo $this->getClient($conn)->getIdentifier() . ' connected', PHP_EOL;
    }

    public function onClose(ConnectionInterface $conn)
    {
        echo $this->getClient($conn)->getIdentifier() . ' disconnected', PHP_EOL;
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo $e, PHP_EOL;
    }

    public function onTick(): void
    {
        $now    = UtcDateTime::create();
        $points = $this->measurementRepository->findRange(
            $this->lastFetch,
            $now,
        );

        $this->lastFetch = $now;

        $message = json_encode([
            'points' => $points
        ]);

        $count = count($points);

        echo ' Found ' . $count . ' new points', PHP_EOL;
        echo '       ' . str_repeat('⎺', strlen((string)$count)), PHP_EOL;

        foreach ($this->clients as $client) {
            $client->sendMessage($message);
        }
    }

    private function getClient(ConnectionInterface $connection): Client
    {
        $objectHash = spl_object_hash($connection);
        $client     = $this->clients[$objectHash] ?? null;

        if ($client === null) {
            $client = new Client($connection, $objectHash);
            $this->clients[$objectHash] = $client;
        }

        return $client;
    }
}
