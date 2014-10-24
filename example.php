<?php

include('app/OrderFormat.php');

use app\OrderFormat;

//$json = file_get_contents("http://10.224.195.39/ilive/getNarrationEvent?event=26338&country_code=BR&contentType=json&callback=sportsTimeLineCAPICallback");

$orderFormat = new OrderFormat();
$newJson = $orderFormat->orderJSON($json);
echo htmlentities($newJson);
