<?php

function dump($value,$level=0)
{
  if ($level==-1)
  {
    $trans[' ']='&there4;';
    $trans["\t"]='&rArr;';
    $trans["\n"]='&para;;';
    $trans["\r"]='&lArr;';
    $trans["\0"]='&oplus;';
    return strtr(htmlspecialchars($value),$trans);
  }
  if ($level==0) echo '<pre>';
  $type= gettype($value);
  echo $type;
  if ($type=='string')
  {
    echo '('.strlen($value).')';
    $value= dump($value,-1);
  }
  elseif ($type=='boolean') $value= ($value?'true':'false');
  elseif ($type=='object')
  {
    $props= get_class_vars(get_class($value));
    echo '('.count($props).') <u>'.get_class($value).'</u>';
    foreach($props as $key=>$val)
    {
      echo "\n".str_repeat("\t",$level+1).$key.' => ';
      dump($value->$key,$level+1);
    }
    $value= '';
  }
  elseif ($type=='array')
  {
    echo '('.count($value).')';
    foreach($value as $key=>$val)
    {
      echo "\n".str_repeat("\t",$level+1).dump($key,-1).' => ';
      dump($val,$level+1);
    }
    $value= '';
  }
  echo " <b>$value</b>";
  if ($level==0) echo '</pre>';
}



require_once('caching.php');
$APIFunc = 'livestats';
$parameters = array(
	'api_key' => 'VLS3XR4KNOEXL0J1',
	'sid' => 'GSN-345493-M',
	'do' => 'start_session'
);
$returnType = 'serialized';
$cacheTime = 0; //No caching needed as LiveStats
$caching = new Caching();
$caching->APICallInit($APIFunc, $parameters, $returnType, $cacheTime);
$session = unserialize($caching->getAPIResults());

//Get the session id out for the next call
$APIFunc = 'livestats';
$parameters = array(
	'api_key' => 'VLS3XR4KNOEXL0J1',
	'sid' => 'GSN-345493-M',
	'do' => 'init_visitors',
	'sess_id' => $session['sess_id']
);
$returnType = 'serialized';
$cacheTime = 0; //5No caching needed for livestats func
$caching = new Caching();
$caching->APICallInit($APIFunc, $parameters, $returnType, $cacheTime);
$LSData = unserialize($caching->getAPIResults());

dump($LSData);
?>
<html>
<head>
	<title>API Caching Test Page</title>
</head>
<body>
</body>
