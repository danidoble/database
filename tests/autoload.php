<?php
/*
 * Created by  (c)danidoble 2022.
 */

use Spatie\Ignition\Ignition;

include __DIR__ . '/../vendor/autoload.php';

define('BASE_PATH', realpath(str_replace("/tests", "", __DIR__)));

Ignition::make()
    ->setTheme('dark')
    ->applicationPath(BASE_PATH)
    ->register();

date_default_timezone_set('America/Mexico_City');

if (!file_exists(__DIR__ . '/.env')) {
    throw new ErrorException("You need a file named .env in test dir to execute tests.");
}

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

if (php_sapi_name() === "cli") {
    echo "\nCreated by danidoble\n\n";
}

function echoCliWeb(string $str = "")
{
    if (php_sapi_name() !== "cli") {
        for ($i = 0; $i < 200; $i++) {
            echo '*';
            if ($i === 99) {
                echo "<br>$str<br>";
            }
            if ($i === 199) {
                echo "<br>";
            }
        }
    }
    if (php_sapi_name() === "cli") {
        for ($i = 0; $i < 100; $i++) {
            echo '*';
            if ($i === 49) {
                echo "\n***** $str ***** \n";
            }
            if ($i === 99) {
                echo "\n\n";
            }
        }
    }
}
