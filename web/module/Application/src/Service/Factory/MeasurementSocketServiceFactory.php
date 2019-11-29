<?php

namespace Sensors\Application\Service\Factory;

use PDO;
use PDOException;
use Psr\Container\ContainerInterface;
use Sensors\Application\Repository\MeasurementRepository;
use Sensors\Application\Service\MeasurementSocketService;

class MeasurementSocketServiceFactory
{
    public function __invoke(ContainerInterface $container): MeasurementSocketService
    {
        $repository = null;

        while (true) {
            try {
                $repository = $container->get(MeasurementRepository::class);
                break;
            } catch (PDOException $exception) {
                echo $exception->getMessage();
                sleep(5);
            }
        }

        return new MeasurementSocketService($repository);
    }
}