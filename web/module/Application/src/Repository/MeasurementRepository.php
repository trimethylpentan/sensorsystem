<?php declare(strict_types=1);

namespace Sensors\Application\Repository;

use PDO;
use PDOStatement;
use Sensors\Application\DataObject\AirPressure;
use Sensors\Application\DataObject\Color;
use Sensors\Application\DataObject\Humidity;
use Sensors\Application\DataObject\Measurement;
use Sensors\Application\DataObject\Temperature;
use Sensors\Application\DataObject\UtcDateTime;

class MeasurementRepository
{
    /** @var PDO */
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @param UtcDateTime $from
     * @param UtcDateTime $to
     * @return Measurement[]
     */
    public function findRange(UtcDateTime $from, UtcDateTime $to): array
    {
        $query = <<<SQL
            SELECT * FROM measurements m
            JOIN colors c ON m.color = c.color_id  
            WHERE m.timestamp > :from
            AND m.timestamp <= :to
        SQL;

        $statement = $this->pdo->prepare($query, []);
        $statement->execute([
            'from' => $from->format('Y-m-d H:i:s'),
            'to'   => $to->format('Y-m-d H:i:s'),
        ]);

        return $this->statementToArray($statement);
    }

    /**
     * @param int $amount
     * @return Measurement[]
     */
    public function findLatest(int $amount): array
    {
        $query = <<<SQL
            SELECT * FROM measurements m
            JOIN colors c ON m.color = c.color_id  
            ORDER BY m.timestamp DESC
            LIMIT :amount
        SQL;

        $statement = $this->pdo->prepare($query, []);
        $statement->bindParam('amount', $amount, PDO::PARAM_INT);
        $statement->execute();

        return $this->statementToArray($statement);
    }

    /**
     * @param int $amount
     * @return Measurement[]
     * @throws \Exception
     */
    public function generateRandom(int $amount = 1): array
    {
        $entries = [];

        for ($i = 0; $i < $amount; $i++) {
            $timestamp = UtcDateTime::create();
            $now = $timestamp->getValue()->getTimestamp();
            $now += random_int(-3600, 3600);
            $timestamp->getValue()->setTimestamp($now);

            $row = [
                'timestamp'    => $timestamp->getValue()->format('Y-m-d H:i:s'),
                'humidity'     => random_int(0, 110) / 100,
                'temperature'  => random_int(-2000, 4000) / 100,
                'air_pressure' => random_int(0, 1000),
                'red'          => random_int(0, 255),
                'blue'         => random_int(0, 255),
                'green'        => random_int(0, 255),
            ];

            $measurement = $this->rowToMeasurement($row);
            $entries[]   = $measurement;
        }

        return $entries;
    }

    private function statementToArray(PDOStatement $statement): array
    {
        $measurements = [];

        while (($row = $statement->fetch(PDO::FETCH_ASSOC)) !== false) {
            $measurement = $this->rowToMeasurement($row);
//            $timestamp = $measurement->getTimestamp()->getValue()->getTimestamp();
            $measurements[] = $measurement;
        }

        return $measurements;
    }

    private function rowToMeasurement(array $row): Measurement
    {
        return Measurement::create(
            UtcDateTime::create($row['timestamp']),
            Color::create(
                (int)$row['red'],
                (int)$row['blue'],
                (int)$row['green'],
            ),
            Humidity::create((float)$row['humidity']),
            Temperature::create((float)$row['temperature']),
            AirPressure::create((float)$row['air_pressure']),
        );
    }
}
