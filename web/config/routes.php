<?php

use Sensors\Application\Controller\ApiController;
use Sensors\Application\Controller\ApiRandomController;
use Sensors\Application\Controller\IndexController;
use Sensors\Application\DataObject\Route;

return [
    Route::create('/', IndexController::class),
    Route::create('/api', ApiController::class),
    Route::create('/api/random', ApiRandomController::class)
];
