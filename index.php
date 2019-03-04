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

// verify webhooks
/*if (isset($_REQUEST['hub_challenge'])){
    $fbHubMode = $_GET['hub_mode'];
    $fbHubVerifyToken = $_GET['hub_verify_token'];
    $fbHubChallenge = $_GET['hub_challenge'];

    if ($fbHubMode === 'subscribe' && $fbHubVerifyToken === getenv('FACEBOOK_VERIFY_TOKEN')){
        $log->info('Inside verify method');
        echo  $fbHubChallenge;
    }
    exit;
}*/

// send response to user.
if (isset($input['entry'][0]['messaging'][0]['message']['text'])) {
    // message is from facebook
    $url = 'https://graph.facebook.com/v3.0/me/messages?access_token=' . getenv('FACEBOOK_PAGE_ACCESS_TOKEN');
    $reply = [
        'messaging_type' => 'RESPONSE',
        'recipient' => [
            'id' => '2580600762014365'
        ],
        'message' => [
            'text' => 'This is a reply.'
        ],
    ];
} else {
    $url = 'https://api.telegram.org/bot'.getenv('TELEGRAM_TOKEN').'/sendmessage';
    $reply = [
        'chat_id' => '668849417',
        'text' => 'This is a reply',
    ];
}



$ch = curl_init($url);
// tell curl to send post request.
curl_setopt($ch, CURLOPT_POST, 1);
// attach json string to post fields.
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($reply));
// set the content type
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
// execute
curl_exec($ch);
if (curl_error($ch)) {
    $log->warning('Curl error: ' . curl_error($ch));
}
curl_close($ch);



















