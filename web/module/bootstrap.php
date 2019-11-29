<?php declare(strict_types=1);

use Sensors\Application\Application\Application;
use Sensors\Application\Application\CliApplication;

require_once __DIR__ . '/../vendor/autoload.php';

defined('ROOT') ?: define('ROOT', dirname(__DIR__));

if (strpos(PHP_SAPI, 'fpm') !== false) {
    $application = new Application($_GET, $_POST, $_COOKIE, $_FILES, $_SERVER);
    $application->run();
    exit;
}

// Auf PI notwendig, da die Datenbank zu lange braucht
sleep(5);

$application = new CliApplication($argv);
$application->run();
