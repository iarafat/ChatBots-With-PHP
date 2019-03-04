<?php

use Monolog\Logger;
use \Monolog\Handler\StreamHandler;

require_once __DIR__ . '/vendor/autoload.php';

// create log file and save the log and save response from user.
try {
    $log = new Logger('general');
    $log->pushHandler(new StreamHandler('debug.log'));
    $log->info('Test logging');

    $dotenv = new Dotenv\Dotenv(__DIR__);
    $dotenv->load();
    //$log->info('Token: '.getenv('TELEGRAM_TOKEN'));

    // save response from user
    $input = json_decode(file_get_contents('php://input'), true) ?? [];
   $log->info('Incoming request: ', $input);

} catch (Exception $e) {
    var_dump($e->getMessage());
}

// send response to user.
$telegramReply = [
    'chat_id' => '668849417',
    'text' => 'This is a reply',
];
$url = 'https://api.telegram.org/bot'.getenv('TELEGRAM_TOKEN').'/sendmessage';

$ch = curl_init($url);

// tell curl to send post request.
curl_setopt($ch, CURLOPT_POST, 1);

// attach json string to post fields.
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($telegramReply));

// set the content type
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

// execute
curl_exec($ch);
if (curl_error($ch)) {
    $log->warning('Curl error: ' . curl_error($ch));
}
curl_close($ch);























