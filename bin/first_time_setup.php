<?php
require_once ("../public/index.php");
require_once( "../app/model/update.php");
$update = new update;

$update->make_tables();

$league_arr = array
(
    0 => 2021, //Premier League
    1 => 2014, //La Liga
);

for ($i = 0; $i < sizeof($league_arr); $i++)
{
    $update->add_league($league_arr[$i]);
    $update->add_teams($league_arr[$i]);
    $update->add_matchday($league_arr[$i]);
    $update->add_matches($league_arr[$i]);
}



