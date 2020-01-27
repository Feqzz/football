<?php
require_once (__DIR__ . "/../public/index.php");
require_once(__DIR__ . "/../app/model/update.php");
$update = new update;

date_default_timezone_set('Europe/London');
$current_date = date('Y-m-d');
$current_time = date('H:i:s');
$time_info = array
(
    'date' => date('Y-m-d'),
    'time' => date('H:i:s'),
);

$league_arr = array
(
    0 => 2021, //Premier League
    1 => 2014, //La Liga
);
for ($i = 1; $i < sizeof($league_arr); $i++)
{
    $update->update_current_matchday($league_arr[$i], $time_info);
}

