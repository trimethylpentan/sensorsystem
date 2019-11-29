<?php declare(strict_types=1);

namespace Sensors\Application\Controller;

class IndexController extends AbstractController
{
    public function handle(array $vars): void
    {
        $indexFile = ROOT . '/public/index.html';

        if (file_exists($indexFile)) {
            $response = $this->getResponse();
            $response->setContent(file_get_contents($indexFile));
            $response->setStatusCode(200);
            $response->addHeader('Content-Type', 'text/html');

            return;
        }
    }
}
