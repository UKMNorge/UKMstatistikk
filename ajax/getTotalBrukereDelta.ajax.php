<?php
use UKMNorge\OAuth2\HandleAPICall;
use UKMNorge\Database\SQL\Query;

// Det brukes POST fordi WP tillater POST bare
$handleCall = new HandleAPICall([], [], ['GET', 'POST'], false);

$retArray = [];

$SQL = new Query(
    "SELECT count(id) as antall from ukm_user WHERE last_login != 'NULL'",
    [],
    'ukmdelta'
);

$SQL2 = new Query(
    "SELECT count(id) as antall from ukm_user",
    [],
    'ukmdelta'
);


$antall_aldri_brukt = $SQL->run();
$antall = $SQL2->run();

$retArray['antall_ikke_brukt'] = intval(Query::fetch($antall_aldri_brukt)['antall']);
$retArray['antall'] = intval(Query::fetch($antall)['antall']);


// Returner til klienten
$handleCall->sendToClient($retArray);