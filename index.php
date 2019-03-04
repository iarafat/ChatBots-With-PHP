<?php

use App\ChatbotHelper;
use App\FacebookMessage;
use App\HttpClient;
use App\TelegramMessage;
use Monolog\Logger;
use \Monolog\Handler\StreamHandler;

require_once __DIR__ . '/vendor/autoload.php';

try {
    $dotenv = new Dotenv\Dotenv(__DIR__);
    $dotenv->load();

    $log = new Logger('general');
    $log->pushHandler(new StreamHandler('debug.log'));

    // save response from user
    $input = json_decode(file_get_contents('php://input'), true) ?? $_GET;
    $log->info('Request: ', $input);

    $chatbotHelper = new ChatbotHelper();
    $messenger = $chatbotHelper->detectMessenger($input);

    switch ($messenger){
        case 'facebook':
            $reply = new FacebookMessage('This is the reply form refactoring code', '2580600762014365');
            break;
        case 'facebook-verify-webhook':
            $chatbotHelper->verifyFacebookWebHook($input);
            break;
        case 'telegram':
            $reply = new TelegramMessage('This is the reply form refactoring code', '668849417');
            break;
        default:
            $reply = false;
    }
    if ($reply){
        $client = new HttpClient();
        $client->send($reply);
    }

} catch (Exception $e) {
    var_dump($e->getMessage());
}



























