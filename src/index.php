<?php
session_start();

require "twitteroauth/autoload.php";

use Abraham\TwitterOAuth\TwitterOAuth;

define('CONSUMER_KEY', getenv('CONSUMER_KEY'));
define('CONSUMER_SECRET', getenv('CONSUMER_SECRET'));
define('OAUTH_CALLBACK', getenv('OAUTH_CALLBACK'));
define('LCBO_API_KEY', getenv('LCBO_API_KEY'));

if (!isset($_GET["oauth_token"])) {    
    
    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
    
    $request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => OAUTH_CALLBACK));

	$_SESSION['oauth_token'] = $request_token['oauth_token'];
	$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

	$url = $connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));

	echo "<a href=\"$url\"><img src=\"images/sign-in-with-twitter-gray.png\" alt=\"Twitter Sign in\"></a>";

} else {
    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
    
    $access_token = $connection->oauth("oauth/access_token", ["oauth_verifier" => $_GET['oauth_verifier']]);

	echo "<p>You are logged in as: " . $access_token['screen_name'] . "</p>";
	
	$curl_command = curl_init("https://lcboapi.com/products?access_key=" . LCBO_API_KEY); 
	
	//return the transfer as a string 
    curl_setopt($curl_command, CURLOPT_RETURNTRANSFER, 1); 
	
	$lcbo_products_json = curl_exec($curl_command);
	
	curl_close($curl_command);
	
	$lcbo_products = json_decode($lcbo_products_json, true);
	
	echo "<p>The LCBO has " . $lcbo_products['pager']['total_record_count'] . " products</p>";
}

?>