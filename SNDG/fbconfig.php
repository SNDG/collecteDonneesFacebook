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
require_once 'extraction/user.php';
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
    // To Get Facebook full name
    $flocation = $graphObject -> getProperty('hometown');
    //$flocation = $flocation['name'];
    $fpolitical = $graphObject -> getProperty('political');
    
    /*$flocation = new FacebookRequest($session, 'GET', '/me?fields=hometown{name}');
    $flocation= $flocation -> execute() -> getGraphObject(GraphUser::className());
    $flocation = $flocation -> asArray();*/
    if (isset($flocation)) {
        $flocation = $flocation -> getProperty('name');        
    }

    try {
        // Logged in
        $user_friends = new FacebookRequest($session, 'GET', '/me/friends');
        $friends = $user_friends -> execute() -> getGraphObject(GraphUser::className());
        $friends = $friends -> asArray();
        //$pic = $user_photos["data"][0]->{"source"};
        //print_r($friends);
        /*$id='1379285485717850';
        $mutual = new FacebookRequest($session, 'GET', '/'.$id.'?fields=context.fields(mutual_friends)');
        $mutual = $mutual -> execute() -> getGraphObject(GraphUser::className());
        $mutual = $mutual -> asArray();*/
        $user=new User('me',$session);
    } catch(FacebookRequestException $e) {
        echo "Exception occured, code: " . $e -> getCode();
        echo " with message: " . $e -> getMessage();
    }
    /* ---- Session Variables -----*/
    $_SESSION['FBID'] = $fbid;
    $_SESSION['FULLNAME'] = $fbfullname;
    $_SESSION['EMAIL'] = $user->getEmail();
    $_SESSION['FRIENDS'] = $friends;
    $_SESSION['MUTUAL'] = $mutual;
    $_SESSION['BIRTHDAY'] = $fbirthday;
    $_SESSION['LOCATION'] = $flocation;
    $_SESSION['POLITICAL'] = $fpolitical;
    /* ---- header location after session ----*/
    header("Location: index.php");
} else {
    $params = array('scope' => 'user_friends, email, user_birthday, user_hometown, user_religion_politics');
    //permissions
    $loginUrl = $helper -> getLoginUrl($params);
    header("Location: " . $loginUrl);
}
?>