<?php

require_once('caching.php');
$APIFunc = 'trends_widget';
$parameters = array(
	'api_key' => 'VLS3XR4KNOEXL0J1',
	'sid' => 'GSN-126445-R',
	'metric' => 'pageviews',
	'period' => 'week'
);
$returnType = '.png';
$cacheTime = 5 * 60; //5 minutes
$cache = new Caching($APIFunc, $parameters, $returnType, $cacheTime);
$data = $cache->getAPIResults();
?>
<html>
<head>
	<title>API Caching Test Page</title>
</head>
<body>
	<img src="<?php echo $data; ?>" alt="">
</body>
