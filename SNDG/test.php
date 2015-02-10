<?php
$json='{
  "data": [
    {
      "name": "Gaëtan Briant",
      "id": "10153042884219165"
    },
    {
      "name": "Stéphane Tanguy",
      "id": "1037773419"
    },
    {
      "name": "Julien Gambier-morel",
      "id": "10205900211482103"
    },
    {
      "name": "Kaji Sothy",
      "id": "10206200407835779"
    }
  ],
  "paging": {
    "next": "https://graph.facebook.com/v2.2/10204445761636833/friends?limit=25&offset=25&__after_id=enc_AeyEl9WacChPKoPMbgqJMSQG8jyOl93CH4O_sW66DZhsv-VmuHzOx1453VpGJ1iUkfY"
  },
  "summary": {
    "total_count": 222
  }
}"';
echo $json;
$arr = json_decode($json, true);
var_dump($arr);
echo count($arr);



/*session_start();
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
use Facebook\Entities\AccessToken;
use Facebook\HttpClients\FacebookCurlHttpClient;
use Facebook\HttpClients\FacebookHttpable;

// start session

// init app with app id and secret
FacebookSession::setDefaultApplication( '642963862396213','856379884d66aeeb6bb610b7b95c8550' );

// login helper with redirect_uri

    $helper = new FacebookRedirectLoginHelper('http://demos.krizna.com/test.php' );

try {
  $session = $helper->getSessionFromRedirect();
} catch( FacebookRequestException $ex ) {
  // When Facebook returns an error
} catch( Exception $ex ) {
  // When validation fails or other local issues
}

// see if we have a session
if ( isset( $session ) ) {
  // graph api request for user data
  $request = new FacebookRequest( $session, 'GET', '/me' );
  $response = $request->execute();
  // get response
  $graphObject = $response->getGraphObject();

		$fbid = $graphObject->getProperty('id');              // To Get Facebook ID
 	    $fbuname = $graphObject->getProperty('username');  // To Get Facebook Username
 	    $fbfullname = $graphObject->getProperty('name'); // To Get Facebook full name
	    $femail = $graphObject->getProperty('email');    // To Get Facebook email ID
/
	    $_SESSION['FBID'] = $fbid;           
	    $_SESSION['USERNAME'] = $fbuname;
        $_SESSION['FULLNAME'] = $fbfullname;
	    $_SESSION['EMAIL'] =  $femail;
    echo '<pre>' . print_r( $graphObject, 1 ) . '</pre>';
} else {
  // show login url
  echo '<a href="' . $helper->getLoginUrl() . '">Login</a>';
}
*/
?>