<?php
use UKMNorge\OAuth2\HandleAPICall;
use UKMNorge\Feedback\Feedbacks;


$handleCall = new HandleAPICall([], [], ['GET', 'POST'], false);



$feedbacks = new Feedbacks();

$retArray = [];
foreach($feedbacks->getAll() as $feedback){
    $innslag = $feedback->getInnslag();
    $resp = null;
    foreach($feedback->getResponses() as $response) {
        $resp = [
            'id' => (int)$response->getId(),
            'sporsmaal' => $response->getSporsmaal(),
            'svar' => $response->getSvar(),
            'innslag_type' => $innslag ? $innslag->getType() : ' ' 
        ];
    }
    $retArray[] = $resp;
}

$handleCall->sendToClient($retArray);