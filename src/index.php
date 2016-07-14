<?php
session_start();

require "twitteroauth/autoload.php";

use Abraham\TwitterOAuth\TwitterOAuth;

// Environment variables set at Docker run. Please see README.md
define('CONSUMER_KEY', getenv('CONSUMER_KEY'));
define('CONSUMER_SECRET', getenv('CONSUMER_SECRET'));
define('OAUTH_CALLBACK', getenv('OAUTH_CALLBACK'));
define('LCBO_API_KEY', getenv('LCBO_API_KEY'));

if (!isset($_GET["oauth_token"])) {    
    
    // Initialize connection to Twitter OAuth
    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
    
    // Get a request token
    $request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => OAUTH_CALLBACK));

	// Store the oauth token and secret in the user session
	$_SESSION['oauth_token'] = $request_token['oauth_token'];
	$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

	// Create the oauth URL
	$url = $connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));

	echo "<a href=\"$url\"><img src=\"images/sign-in-with-twitter-gray.png\" alt=\"Twitter Sign in\"></a>";

} else {
    // Reinitialize connection to Twitter OAuth
    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
    
    // Get the access token
    $access_token = $connection->oauth("oauth/access_token", ["oauth_verifier" => $_GET['oauth_verifier']]);

	echo "<p>You are logged in as: " . $access_token['screen_name'] . "</p>";
	
	// Initialize the curl command
	$curl_command = curl_init("https://lcboapi.com/products?access_key=" . LCBO_API_KEY); 
	
	// Return the transfer as a string 
    curl_setopt($curl_command, CURLOPT_RETURNTRANSFER, 1); 
	
	// Execute the curl command and get the json encoded products
	$lcbo_products_json = curl_exec($curl_command);
	
	// Close the curl reference
	curl_close($curl_command);
	
	// Convert the products json to an associative array
	$lcbo_products = json_decode($lcbo_products_json, true);
	
	echo "<p>The LCBO has " . $lcbo_products['pager']['total_record_count'] . " products</p>";
}

?>