<?php
use UKMNorge\OAuth2\HandleAPICall;
use UKMNorge\Feedback\FeedbackDelta;
use UKMNorge\Feedback\FeedbackResponse;
use UKMNorge\Feedback\Feedbacks;


$handleCall = new HandleAPICall([], [], ['GET', 'POST'], false);



$feedbacks = new Feedbacks();

$retArray = [];
foreach($feedbacks->getAll() as $feedback){
    $innslag = $feedback->getInnslag();
    $resp = null;
    foreach($feedback->getResponses() as $response) {
        $resp = [
            'id' => $response->getId(),
            'sporsmaal' => $response->getSporsmaal(),
            'svar' => $response->getSvar(),
            'innslag_type' => $innslag->getType() 
        ];
    }
    $retArray[] = $resp;
}

$handleCall->sendToClient($retArray);