<?php declare(strict_types=1);

namespace Sensors\Application\DataObject;

use JsonSerializable;

final class Color implements JsonSerializable
{
    /** @var int */
    private $r;

    /** @var int */
    private $g;

    /** @var int */
    private $b;

    private function __construct(int $r, int $g, int $b)
    {
        $this->r = $r;
        $this->g = $g;
        $this->b = $b;
    }

    public static function create(int $r, int $g, int $b): self
    {
        return new self($r, $g, $b);
    }

    public function jsonSerialize()
    {
        return [
            'r' => $this->r,
            'g' => $this->g,
            'b' => $this->b,
        ];
    }
}
