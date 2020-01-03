<?php declare(strict_types=1);

namespace Sensors\Application\Application;

use DI\ContainerBuilder;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Http\HttpRequest;
use Http\HttpResponse;
use Psr\Container\ContainerInterface;
use Sensors\Application\Controller\AbstractController;
use Sensors\Application\DataObject\Route;
use Sensors\Application\Exception\InvalidControllerException;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;
use function FastRoute\simpleDispatcher;

class Application
{
    /** @var HttpRequest */
    private $request;

    /** @var HttpResponse */
    private $response;

    public function __construct(array $get, array $post, array $cookie, array $files, array $server)
    {
        $this->request  = new HttpRequest($get, $post, $cookie, $files, $server);
        $this->response = new HttpResponse();
    }

    public function run(): void
    {
        $this->attachWhoops();

        $config    = require ROOT . '/config/config.php';
        $container = $this->buildContainer($config);

        [$foundStatus, $route, $vars] = $this->collectRoutes($config['routes']);

        switch ($foundStatus) {
            case Dispatcher::FOUND:
                /** @var Route $route */
                $handlerClass = $route->getHandlerClass();

                if (!is_subclass_of($handlerClass, AbstractController::class, true)) {
                    throw new InvalidControllerException(sprintf(
                        'Controller must extend "%s", "%s" does not',
                        AbstractController::class,
                        $handlerClass
                    ));
                }

                /** @var AbstractController $controller */
                $controller = $container->get($handlerClass);
                $controller->prepare($this->request, $this->response, $route);
                $controller->handle($vars);

                break;
            case Dispatcher::NOT_FOUND:
                $this->response->setStatusCode(404);
                $this->response->setContent('404 - Not Found :(');
                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                $this->response->setStatusCode(405);
                $this->response->setContent('405 - Method Not Allowed :(');
                break;
        }

        $this->echoContent();
    }

    private function attachWhoops(): void
    {
        $whoops = new Run();
        $whoops->appendHandler(new PrettyPageHandler());
        $whoops->register();
    }

    /**
     * @param Route[] $routes
     * @return array
     * @throws \Http\MissingRequestMetaVariableException
     */
    private function collectRoutes(array $routes): array
    {
        $dispatcher = simpleDispatcher(static function(RouteCollector $r) use($routes) {
            foreach ($routes as $route) {
                $r->addRoute($route->getMethod(), $route->getRoute(), $route);
            }
        });

        $this->request->getPath();

        return $dispatcher->dispatch($this->request->getMethod(), '/' . trim($this->request->getPath(), '/'));
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

    private function echoContent(): void
    {
        if (!headers_sent()) {
            foreach ($this->response->getHeaders() as $header) {
                header($header);
            }
        }

        echo $this->response->getContent();
    }
}
