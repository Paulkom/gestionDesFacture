<?php
namespace App\Services;

use Symfony\Component\HttpFoundation\JsonResponse;
use Twilio\Rest\Client;

class SmsEnvoie{
    public function sendSms(string $num, string $nom, string $text){
        $sid = $_ENV["TWILIO_SID"]; 
        $token = $_ENV["TWILIO_TOKEN"];
        $numEnvoie = $_ENV["TWILIO_NUMBER"];
        // dump([$sid,$token,$numEnvoie]);
        // dd([$sid,$token,$numEnvoie]);
        $number = $num;
        $client = new Client($sid,$token);
        $message = $text;

        $client->messages->create(
            $number,
            [
                'from'=>$numEnvoie,
                'body'=>$message
            ]
            );

        //return new JsonResponse([$sid,$token,$numEnvoie]);
    }
}