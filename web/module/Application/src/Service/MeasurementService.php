<?php declare(strict_types=1);

namespace Sensors\Application\Service;

use Exception;
use Sensors\Application\DataObject\Measurement;
use Sensors\Application\DataObject\UtcDateTime;
use Sensors\Application\Repository\MeasurementRepository;

class MeasurementService
{
    /** @var MeasurementRepository */
    private $measurementRepository;

    public function __construct(MeasurementRepository $measurementRepository)
    {
        $this->measurementRepository = $measurementRepository;
    }

    /**
     * @param UtcDateTime $from
     * @param UtcDateTime $to
     * @param int $latest
     * @return Measurement[]
     */
    public function getMeasurements(UtcDateTime $from, UtcDateTime $to, int $latest = 0): array
    {
        if ($latest > 0) {
            return $this->measurementRepository->findLatest($latest);
        }

        return $this->measurementRepository->findRange($from, $to);
    }

    /**
     * @param int $amount
     * @return Measurement[]
     * @throws Exception
     */
    public function getRandomMeasurements(int $amount = 1): array
    {
        return $this->measurementRepository->generateRandom($amount);
    }
}
