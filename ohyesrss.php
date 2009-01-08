<?php
require_once('./facebook/client/facebook.php');
require_once('./magpierss-0.72/rss_fetch.inc');

$api_key = "API-KEY-HERE";
$shh_secret ="SECRET-API-KEY";
$facebook = new Facebook($api_key, $shh_secret);
$appcallbackurl = "http://apps.facebook.com/ohyessrss";
?>
