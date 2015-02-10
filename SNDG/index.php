<?php
session_start();
?>
<!doctype html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
  <head>
    <title>SNDG</title>
<link href="http://www.bootstrapcdn.com/twitter-bootstrap/2.2.2/css/bootstrap-combined.min.css" rel="stylesheet"> 
 </head>
  <body>
  <?php if ($_SESSION['FBID']): ?>      <!--  After user login  -->
<div class="container">
<div class="hero-unit">
  <h1>Hello <?php echo $_SESSION['USERNAME']; ?></h1>
  <p>Welcome to SNDG</p>
  </div>
<div class="span4">
 <ul class="nav nav-list">
<li class="nav-header">Image</li>
	<li><img src="https://graph.facebook.com/<?php echo $_SESSION['FBID']; ?>/picture"></li>
<li class="nav-header">Facebook ID</li>
<li><?php echo $_SESSION['FBID']; ?></li>
<li class="nav-header">Facebook fullname</li>
<li><?php echo $_SESSION['FULLNAME']; ?></li>
<li class="nav-header">Facebook Email</li>
<li><?php echo $_SESSION['EMAIL']; ?></li>
<li class="nav-header">Friends</li>
<li><?php 
//$nb_friends_app=0;
//foreach($_SESSION['FRIENDS']['data'] as $friend) {
//    echo $friend->{"name"}, '<br>';
//    $nb_friends_app++;
//} 
//$nb_friends=$_SESSION['FRIENDS']['summary']->{"total_count"};
//echo "Total number of friends : ".$nb_friends, '<br>';
//echo "Friends using the app / Total number of friends : ".($nb_friends_app/$nb_friends)."\n";
//echo "first friend : ".$_SESSION['FRIENDS']['data'][0]->{"name"}, '<br>';
var_dump($_SESSION['FRIENDS']);
//echo $_SESSION['FRIENDS'], '<br>';
echo "Taille : ".count($_SESSION['FRIENDS']["data"]);

?></li>
<li class="nav-header">Birthday</li>
<li><?php echo $_SESSION['BIRTHDAY']; ?></li>
<li class="nav-header">Location</li>
<li><?php 
echo $_SESSION['LOCATION']; 
//var_dump($_SESSION['LOCATION']);
?></li>
<li class="nav-header">Politics</li>
<li><?php echo $_SESSION['POLITICAL']; ?></li>
<li class="nav-header">Adjacency matrix</li>
<li><?php 
echo $_SESSION['ADJ']; 
//var_dump($_SESSION['MUTUAL']);
?></li>

<div><a href="logout.php">Logout</a></div>
</ul></div></div>
    <?php else: ?>     <!-- Before login --> 
<div class="container">
<h1>Login with Facebook</h1>
           Not Connected
<div>
      <a href="fbconfig.php">Login with Facebook</a></div>
      </div>
    <?php endif ?>
  </body>
</html>
