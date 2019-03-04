<?php

namespace App;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class HttpClient
{
    /** @var Logger */
    protected $log;

    public function __construct()
    {
        $this->log = new Logger('general');
        $this->log->pushHandler(new StreamHandler('debug.log'));
    }

    public function send(Message $reply)
    {
        $ch = curl_init($reply->getApiEndpoint());
        // tell curl to send post request.
        curl_setopt($ch, CURLOPT_POST, 1);
        // attach json string to post fields.
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($reply->toArray()));
        //return response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // set the content type
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        // execute
        $respone = curl_exec($ch);
        if (curl_error($ch)) {
            $this->log->warning('Curl error: ' . curl_error($ch));
        } else {
            $this->log->info('Curl Response: '. $respone);
        }
        curl_close($ch);

    }
}