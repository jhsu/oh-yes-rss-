<?php
require_once('ohyesrss.php');
require_once('do_facebook_request.php');

//catch the exception that gets thrown if the cookie has an invalid session_key in it
try {
  if (!$facebook->api_client->users_isAppAdded()) {
    $facebook->redirect($facebook->get_add_url());
  }
} catch (Exception $ex) {
  //this will clear cookies for your application and redirect them to a login prompt
  $facebook->set_user(null, null);
  $facebook->redirect($appcallbackurl);
}

$url = "http://pownce.com/feeds/public/jhsu/";
$num_items = 6;
$rss = fetch_rss( $url );
$fewrss = array_slice($rss->items, 0, $num_items);

$txtcolor ="#000";
$bg1 = "#fff";
$bg2 = "#fff";
$colornow = "";

$markup = "<h2><a href='http://pownce.com/jhsu/public/'>Pownce Network</a></h2><p><ul style='list-style: none;padding:0;'>";
foreach ($fewrss as $item) {         
	if ($colornow == $bg1){
		$colornow = $bg2;
	} else {
		$colornow = $bg1;}
	$href = $item["link"]; 
	$title = $item["title"]; 
	// $description = $item["description"]; 
	$markup .= "<li><a href=$href style='background:".$colornow." url(http://jhsu.org/fb/papers.png) no-repeat left 4px;display:block;padding:4px 4px 4px 20px; margin-bottom: 5px; border-bottom:1px solid #ccc;line-height: 18px;' target='_blank'>$title</a></li>"; }

$markup .= "</ul></p><fb:if-is-own-profile><small><img src='http://jhsu.org/fb/spinner.gif' id='spinner' style='display:none;'/>";
$markup .="<form id='dummy_form'><input type='hidden' name='refresh' value='yes' /></form>";
$markup .="<a clickrewriteurl='http://jhsu.org/fb/index.php' clickrewriteform='dummy_form' clickrewriteid='ohyesrss' clicktoshow='spinner' style='background: #003399; padding: 5px 15px;color:#fff;border-width:1px;border-color: #cedfea #000000 #000000 #cedfea;cursor:pointer;'>Refresh</a>";
$markup .="</small><img src='http://jhsu.org/fb/spinner.gif' height='0' width='0' /></fb:if-is-own-profile>";

if (isset($_POST["refresh"])) {
$post = $facebook->api_client->profile_setFBML("<div id='ohyesrss'>".$markup."</div>", $user);
echo $markup;
exit();
}


$post = $facebook->api_client->profile_setFBML("<div id='ohyesrss'>".$markup."</div>", $user);
if ($post != 1) err("Error calling profile_setFBML");


if ($_GET["action"] == "refresh") {
	$facebook->redirect($facebook->get_facebook_url() . '/profile.php?id='.$user);
}

?>

<fb:dashboard>
 <fb:action href="http://josephhsu.com/" title="by Joseph Hsu">Who?</fb:action>
 <fb:create-button href="http://apps.facebook.com/ohyesrss/?action=refresh">Refresh</fb:create-button>
</fb:dashboard>

<div style="padding: 50px;padding-top:0;">

<p><strong>It's More Than a Note</strong> Your blog posts should be showcased, not noted and lost.  Customize the look and have more 
controll of your incoming rss feeds.</p>
<p>This is my first Facebook app that integrates the RSS feed from my website into my Facebook profile page.</p>
<p>Currently only set to display feed from my blog, allowing more users would mean further development and database integration.</p>
</div>
