<?php
use UKMNorge\OAuth2\HandleAPICall;
use UKMNorge\Database\SQL\Query;

// Det brukes POST fordi WP tillater POST bare
$handleCall = new HandleAPICall([], [], ['GET', 'POST'], false);

$retArray = [];

// Antall per definert år
$SQL_antall = new Query(
    "SELECT count(pl_id) as antall from smartukm_place",
    []
);
$antall = $SQL_antall->run();

// Kommune
$SQL_antall_kommune = new Query(
    "SELECT count(pl_id) as antall from smartukm_place WHERE pl_type='kommune'",
    []
);
$antall_kommune = $SQL_antall_kommune->run();

// Fylke
$SQL_antall_fylke = new Query(
    "SELECT count(pl_id) as antall from smartukm_place WHERE pl_type='fylke'",
    []
);
$antall_fylke = $SQL_antall_fylke->run();


$retArray['antall'] = intval(Query::fetch($antall)['antall']);
$retArray['antall_kommune'] = intval(Query::fetch($antall_kommune)['antall']);
$retArray['antall_fylke'] = intval(Query::fetch($antall_fylke)['antall']);

// Returner til klienten
$handleCall->sendToClient($retArray);



