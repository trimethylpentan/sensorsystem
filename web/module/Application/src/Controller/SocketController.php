<?php declare(strict_types=1);

namespace Sensors\Application\Controller;

use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use React\EventLoop\Factory as LoopFactory;
use React\Socket\Server as Reactor;
use Sensors\Application\Service\MeasurementSocketService;

class SocketController extends AbstractCliController
{
    public const TICK_INTERVAL = 3;

    /** @var MeasurementSocketService */
    private $socketService;

    public function __construct(MeasurementSocketService $socketService)
    {
        $this->socketService = $socketService;
    }

    public function handleCli(array $argv): void
    {
        $server = $this->buildIoServer();
        $server->run();
    }

    private function buildIoServer(): IoServer
    {
        $httpServer = new HttpServer(new WsServer($this->socketService));
        $loop       = LoopFactory::create();
        $loop->addPeriodicTimer(self::TICK_INTERVAL, [$this->socketService, 'onTick']);

        $socket = new Reactor('0.0.0.0:1414', $loop);

        return new IoServer($httpServer, $socket, $loop);
    }
}
