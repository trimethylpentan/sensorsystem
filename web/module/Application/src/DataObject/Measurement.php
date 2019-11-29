<?php declare(strict_types=1);

namespace Sensors\Application\DataObject;

use JsonSerializable;

final class Measurement implements JsonSerializable
{
    /** @var UtcDateTime */
    private $timestamp;

    /** @var Color */
    private $color;

    /** @var Humidity */
    private $humidity;

    /** @var Temperature */
    private $temperature;

    /** @var AirPressure */
    private $airPressure;

    private function __construct(
        UtcDateTime $timestamp,
        Color $color,
        Humidity $humidity,
        Temperature $temperature,
        AirPressure $airPressure
    ) {
        $this->timestamp   = $timestamp;
        $this->color       = $color;
        $this->humidity    = $humidity;
        $this->temperature = $temperature;
        $this->airPressure = $airPressure;
    }

    public static function create(
        UtcDateTime $timestamp,
        Color $color,
        Humidity $humidity,
        Temperature $temperature,
        AirPressure $airPressure
    ): self {
        return new self($timestamp, $color, $humidity, $temperature, $airPressure);
    }

    public function getTimestamp(): UtcDateTime
    {
        return $this->timestamp;
    }

    public function getColor(): Color
    {
        return $this->color;
    }

    public function getHumidity(): Humidity
    {
        return $this->humidity;
    }

    public function getTemperature(): Temperature
    {
        return $this->temperature;
    }

    public function getAirPressure(): AirPressure
    {
        return $this->airPressure;
    }

    public function jsonSerialize()
    {
        return [
            'timestamp'   => $this->timestamp,
            'color'       => $this->color,
            'humidity'    => $this->humidity,
            'temperature' => $this->temperature,
            'airPressure' => $this->airPressure,
        ];
    }
}
