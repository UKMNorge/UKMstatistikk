<?php
use UKMNorge\OAuth2\HandleAPICall;
use UKMNorge\Database\SQL\Query;

// Det brukes POST fordi WP tillater POST bare
$handleCall = new HandleAPICall(['from_date', 'timeline_name'], [], ['GET', 'POST'], false);

$fromDate = $handleCall->getArgument('from_date');
$timelineName = $handleCall->getArgument('timeline_name');

// $feedbacks = new Feedbacks();

$retArray = [];
$hourOrDay = $timelineName == 'day' ? 'hour' : 'day';

$SQL = new Query(
    "SELECT last_login AS date, count(id) AS total 
    FROM ukm_user 
    WHERE last_login > '#from_date'
    GROUP BY #day_hour(last_login)",
    [
        'from_date' => $fromDate,
        'day_hour' => $hourOrDay
    ],
    'ukmdelta'
);

$res = $SQL->run();

while ($r = Query::fetch($res)) {
    $retArray[] = $r;
}


// Returner til klienten
$handleCall->sendToClient($retArray);