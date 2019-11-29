<?php declare(strict_types=1);

namespace Sensors\Application\Application;

use DI\ContainerBuilder;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Http\HttpRequest;
use Http\HttpResponse;
use Psr\Container\ContainerInterface;
use Sensors\Application\Controller\AbstractCliController;
use Sensors\Application\Controller\AbstractController;
use Sensors\Application\DataObject\Route;
use Sensors\Application\Exception\InvalidControllerException;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;
use function FastRoute\simpleDispatcher;

class CliApplication
{
    /** @var array */
    private $argv;

    public function __construct(array $argv)
    {
        $this->argv = $argv;
    }

    public function run(): void
    {
        $config    = require ROOT . '/config/config.php';
        $container = $this->buildContainer($config);

        $command           = $this->argv[1] ?? null;
        $availableCommands = $config['commands'];

        if ($command === null) {
            echo 'No command given', PHP_EOL;
            $this->printAvailableCommands($availableCommands);
            return;
        }

        $command = strtolower($command);

        if (!isset($availableCommands[$command])) {
            echo sprintf('Command "%s" does not exist', $command), PHP_EOL;
            $this->printAvailableCommands($availableCommands);
            return;
        }

        $handlerClass = $availableCommands[$command]['handler'];

        if (!is_subclass_of($handlerClass, AbstractCliController::class, true)) {
            throw new InvalidControllerException(sprintf(
                'Controller must extend "%s", "%s" does not',
                AbstractCliController::class,
                $handlerClass
            ));
        }

        /** @var AbstractCliController $controller */
        $controller = $container->get($handlerClass);
        $controller->handleCli($this->argv);
    }

    private function buildContainer(array $config = []): ContainerInterface
    {
        $dependencyLessConfig = $config;
        unset($dependencyLessConfig['dependencies']);

        $builder = new ContainerBuilder();
        $builder->addDefinitions($config['dependencies']);
        $builder->addDefinitions(['config' => $dependencyLessConfig]);
        $builder->useAutowiring(true);

        return $builder->build();
    }

    private function printAvailableCommands($availableCommands): void
    {
        echo 'Available commands: ' . PHP_EOL . PHP_EOL .
            ' ' . implode(PHP_EOL . ' ', array_keys($availableCommands)) . PHP_EOL . PHP_EOL;
    }
}
