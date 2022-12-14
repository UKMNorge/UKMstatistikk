<?php
use UKMNorge\OAuth2\HandleAPICall;
use UKMNorge\Database\SQL\Query;

// Det brukes POST fordi WP tillater POST bare
$handleCall = new HandleAPICall([], [], ['GET', 'POST'], false);

$retArray = [];

$SQL_facebook = new Query(
    "SELECT count(id) as antall from ukm_user WHERE facebook_id != 'NULL'",
    [],
    'ukmdelta'
);

$antall_facebook = $SQL_facebook->run();

$retArray['antall'] = intval(Query::fetch($antall_facebook)['antall']);

// Returner til klienten
$handleCall->sendToClient($retArray);