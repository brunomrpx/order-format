<?php

include('app/OrderFormat.php');

use app\OrderFormat;

$json = file_get_contents("http://dsv-fe01.terra.com.br/cengine/ilive/getEventConfiguration?event=26577&country_code=mx&callback=json_event_configurationr&contentType=json");

$orderFormat = new OrderFormat([
    'removeEmptyElements' => true
]);
$newJson = $orderFormat->orderJSON($json);
echo htmlentities($newJson);
