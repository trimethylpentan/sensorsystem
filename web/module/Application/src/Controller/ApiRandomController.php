<?php

namespace Sensors\Application\Controller;

use Exception;
use Sensors\Application\DataObject\UtcDateTime;
use Sensors\Application\Exception\InvalidDateException;
use Sensors\Application\Service\MeasurementService;

class ApiRandomController extends AbstractController
{
    /** @var MeasurementService */
    private $measurementService;

    public function __construct(MeasurementService $measurementService)
    {
        $this->measurementService = $measurementService;
    }

    public function handle(array $vars): void
    {
        $amount = (int)$this->getRequest()->getQueryParameter('latest', 1);
        $result = $this->measurementService->getRandomMeasurements($amount);

        $this->getResponse()->setHeader('Access-Control-Allow-Origin', '*');

        $this->jsonResponse([
            'statusCode' => 200,
            'points' => $result
        ]);
    }
}
