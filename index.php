<?php
use Monolog\Logger;
use \Monolog\Handler\StreamHandler;
require_once  __DIR__.'/vendor/autoload.php';

try {
    $log = new Logger('general');
    $log->pushHandler(new StreamHandler('debug.log'));
    //$log->info('Test logging');

    $dotenv = new Dotenv\Dotenv(__DIR__);
    $dotenv->load();
    //$log->info('Token: '.getenv('TELEGRAM_TOKEN'));

    $input = json_decode(file_get_contents('php://input'), true) ?? [];
    $log->info('Incoming request: ', $input);

} catch (Exception $e) {
    var_dump($e->getMessage());
}