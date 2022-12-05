<?php
use UKMNorge\OAuth2\HandleAPICall;
use UKMNorge\Database\SQL\Query;

// Det brukes POST fordi WP tillater POST bare
$handleCall = new HandleAPICall(['from_date'], [], ['GET', 'POST'], false);

$fromDate = $handleCall->getArgument('from_date');

// $feedbacks = new Feedbacks();

$retArray = [];

$SQL = new Query(
    "SELECT last_login AS date, count(id) AS total 
    FROM ukm_user 
    WHERE last_login > '#from_date'
    GROUP BY day(last_login)",
    [
        'from_date' => $fromDate
    ],
    'ukmdelta'
);

$res = $SQL->run();
while ($r = Query::fetch($res)) {
    $retArray[] = $r;
}


// Returner til klienten
$handleCall->sendToClient($retArray);