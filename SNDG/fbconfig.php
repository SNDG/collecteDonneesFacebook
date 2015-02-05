<?php
session_start();
// added in v4.0.0
require_once 'autoload.php';
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\GraphUser;
use Facebook\Entities\AccessToken;
use Facebook\HttpClients\FacebookCurlHttpClient;
use Facebook\HttpClients\FacebookHttpable;
// init app with app id and secret
FacebookSession::setDefaultApplication('1395213007449007', 'cf3de50a843b4b3c631a436911484d0a');
// login helper with redirect_uri
$helper = new FacebookRedirectLoginHelper('http://localhost/1353/fbconfig.php');
try {
    $session = $helper -> getSessionFromRedirect();
} catch( FacebookRequestException $ex ) {
    // When Facebook returns an error
} catch( Exception $ex ) {
    // When validation fails or other local issues
}
// see if we have a session
if (isset($session)) {
    // graph api request for user data
    $request = new FacebookRequest($session, 'GET', '/me');
    $response = $request -> execute();
    // get response
    $graphObject = $response -> getGraphObject();
    $fbid = $graphObject -> getProperty('id');
    // To Get Facebook ID
    $fbfullname = $graphObject -> getProperty('name');
    // To Get Facebook full name
    $femail = $graphObject -> getProperty('email');
    // To Get Facebook full name
    $fbirthday = $graphObject -> getProperty('birthday');

    try {
        // Logged in
        $user_friends = new FacebookRequest($session, 'GET', '/me/friends');
        $friends = $user_friends -> execute() -> getGraphObject(GraphUser::className());
        $friends = $friends -> asArray();
        //$pic = $user_photos["data"][0]->{"source"};
        //print_r($friends);
    } catch(FacebookRequestException $e) {
        echo "Exception occured, code: " . $e -> getCode();
        echo " with message: " . $e -> getMessage();
    }
    /* ---- Session Variables -----*/
    $_SESSION['FBID'] = $fbid;
    $_SESSION['FULLNAME'] = $fbfullname;
    $_SESSION['EMAIL'] = $femail;
    $_SESSION['FRIENDS'] = $friends;
    $_SESSION['BIRTHDAY'] = $fbirthday;
    /* ---- header location after session ----*/
    header("Location: index.php");
} else {
    $params = array('scope' => 'user_friends, email, user_birthday');
    //permissions
    $loginUrl = $helper -> getLoginUrl($params);
    header("Location: " . $loginUrl);
}
?>