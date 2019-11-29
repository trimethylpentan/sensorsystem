<?php declare(strict_types=1);

namespace Sensors\Application\Controller;

use \DateTime;
use Exception;
use Sensors\Application\DataObject\Color;
use Sensors\Application\DataObject\Humidity;
use Sensors\Application\DataObject\Temperature;
use Sensors\Application\DataObject\UtcDateTime;
use Sensors\Application\Exception\InvalidDateException;
use Sensors\Application\Service\MeasurementService;

class ApiController extends AbstractController
{
    /** @var MeasurementService */
    private $measurementService;

    public function __construct(MeasurementService $measurementService)
    {
        $this->measurementService = $measurementService;
    }

    public function handle(array $vars): void
    {
        $latest = (int)$this->getRequest()->getQueryParameter('latest', 0);
        $from   = $this->queryParamToDateTime('from', UtcDateTime::create('now - 1 hour'));
        $to     = $this->queryParamToDateTime('to', UtcDateTime::create());
        $result = $this->measurementService->getMeasurements($from, $to, $latest);

        $this->getResponse()->setHeader('Access-Control-Allow-Origin', '*');

        $this->jsonResponse([
            'statusCode' => 200,
            'points' => $result
        ]);
    }

    private function queryParamToDateTime(string $paramName, UtcDateTime $defaultDate): UtcDateTime
    {
        $raw = $this->getRequest()->getQueryParameter($paramName);

        if ($raw === null) {
            return $defaultDate;
        }

        try {
            if (is_numeric($raw)) {
                $date = UtcDateTime::create();
                $date->getValue()->setTimestamp((int)$raw);
                return $date;
            }

            return UtcDateTime::create($raw);
        } catch (Exception $exception) {
            throw new InvalidDateException(sprintf(
                'The given date for "%s" (%s) does not seem to be a valid date',
                $paramName,
                $raw
            ), 0, $exception);
        }
    }
}
