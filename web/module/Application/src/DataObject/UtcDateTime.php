<?php declare(strict_types=1);

namespace Sensors\Application\DataObject;

use DateInterval;
use DateTimeImmutable;
use DateTimeZone;
use JsonSerializable;

final class UtcDateTime implements JsonSerializable
{
    /** @var DateTimeImmutable */
    private $value;

    private function __construct(string $time)
    {
        $this->value = new DateTimeImmutable($time);
        $this->value = $this->value->setTimezone(new DateTimeZone('GMT'));
    }

    public static function create(string $time = 'now'): self
    {
        return new self($time);
    }

    public function getValue(): DateTimeImmutable
    {
        return $this->value;
    }

    public function add(string $interval): self
    {
        $this->value = $this->value->add(new DateInterval($interval));

        return $this;
    }

    public function sub(string $interval): self
    {
        $this->value = $this->value->sub(new DateInterval($interval));

        return $this;
    }

    public function format(string $format = DATE_ATOM): string
    {
        return $this->value->format($format);
    }

    public function jsonSerialize()
    {
        return $this->value->format(DATE_ATOM);
    }
}
