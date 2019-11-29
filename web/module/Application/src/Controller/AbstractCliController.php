<?php declare(strict_types=1);

namespace Sensors\Application\Controller;

use Http\HttpRequest;
use Http\HttpResponse;
use JsonSerializable;
use Sensors\Application\DataObject\Route;

abstract class AbstractCliController
{
    abstract public function handleCli(array $argv): void;
}