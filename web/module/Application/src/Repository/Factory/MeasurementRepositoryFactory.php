<?php declare(strict_types=1);

namespace Sensors\Application\Repository\Factory;

use PDO;
use PDOException;
use Psr\Container\ContainerInterface;
use Sensors\Application\Repository\MeasurementRepository;

class MeasurementRepositoryFactory
{
    public function __invoke(ContainerInterface $container): MeasurementRepository
    {
        $config = $container->get('config')['db'];
        $dsn = sprintf(
            'mysql:dbname=%s;host=%s;port=%s',
            $config['database'],
            $config['host'],
            $config['port'],
        );

        $user     = $config['auth']['user'];
        $password = $config['auth']['password'];
        $pdo      = new PDO($dsn, $user, $password);

        return new MeasurementRepository($pdo);
    }
}
