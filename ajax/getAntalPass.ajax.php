<?php
use UKMNorge\OAuth2\HandleAPICall;
use UKMNorge\Database\SQL\Query;

// Det brukes POST fordi WP tillater POST bare
$handleCall = new HandleAPICall([], [], ['GET', 'POST'], false);

$retArray = [];

$SQL = new Query(
    "SELECT count(id) as antall from ukm_user WHERE password_requested_at != 'NULL'",
    [],
    'ukmdelta'
);

$antall = $SQL->run();

$retArray['antall'] = intval(Query::fetch($antall)['antall']);

// Returner til klienten
$handleCall->sendToClient($retArray);