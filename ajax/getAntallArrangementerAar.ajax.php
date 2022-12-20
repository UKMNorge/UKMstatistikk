<?php
use UKMNorge\OAuth2\HandleAPICall;
use UKMNorge\Database\SQL\Query;

// Det brukes POST fordi WP tillater POST bare
$handleCall = new HandleAPICall([], [], ['GET', 'POST'], false);

$retArray = [];

$SQL = new Query(
    "SELECT season, pl_subtype, count(pl_id) AS antall FROM smartukm_place GROUP BY season, pl_subtype",
    [],
);

$res = $SQL->run();
while( $row = Query::fetch( $res ) ) {

    $retArray[$row['season']][$row['pl_subtype']] = ['antall' => $row['antall']];
}

// Returner til klienten
$handleCall->sendToClient($retArray);