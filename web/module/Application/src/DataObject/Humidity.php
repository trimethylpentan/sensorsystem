<?php declare(strict_types=1);

namespace Sensors\Application\DataObject;

use JsonSerializable;

final class Humidity implements JsonSerializable
{
    /** @var float */
    private $value;

    private function __construct(float $value)
    {
        $this->value = $value;
    }

    public static function create(float $value): self
    {
        if ($value < 0) {
            $value = 0.0;
        }

        return new self($value);
    }

    public function jsonSerialize()
    {
        return $this->value;
    }
}
