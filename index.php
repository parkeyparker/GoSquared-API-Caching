<?php

require_once('caching.php');
$APIFunc = 'pageviews';
$parameters = array(
	'api_key' => 'VLS3XR4KNOEXL0J1',
	'sid' => 'GSN-126445-R'
);
$returnType = '.png';
$cacheTime = 3600;
$data = new Caching($APIFunc, $parameters, $returnType, $cacheTime);

echo time();

?>