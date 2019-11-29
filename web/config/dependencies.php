<?php

use Sensors\Application\Repository\Factory\MeasurementRepositoryFactory;
use Sensors\Application\Repository\MeasurementRepository;
use Sensors\Application\Service\Factory\MeasurementSocketServiceFactory;
use Sensors\Application\Service\MeasurementSocketService;
use function DI\factory;

return [
    MeasurementRepository::class    => factory(MeasurementRepositoryFactory::class),
    MeasurementSocketService::class => factory(MeasurementSocketServiceFactory::class),
];
