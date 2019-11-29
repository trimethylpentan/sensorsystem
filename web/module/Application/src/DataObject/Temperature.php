<?php declare(strict_types=1);

namespace Sensors\Application\DataObject;

use JsonSerializable;

final class Temperature implements JsonSerializable
{
    /** @var float */
    private $value;

    private function __construct(float $value)
    {
        $this->value = $value;
    }

    public static function create(float $value): self
    {
        return new self($value);
    }

    public function jsonSerialize()
    {
        return $this->value;
    }
}
