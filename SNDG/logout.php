<?php 
session_start();
session_unset();
$_SESSION['token'] = NULL;
$_SESSION['fb_id']= NULL;
$_SESSION['fb_name']= NULL;
$_SESSION['fb_email']= NULL;
$_SESSION['fb_gender']= NULL;
$_SESSION['fb_bio']= NULL;
$_SESSION['fb_birthday']= NULL;
$_SESSION['fb_hometown']= NULL;
$_SESSION['fb_location']= NULL;
$_SESSION['fb_locale']= NULL;
$_SESSION['fb_adj_matrix']= NULL;
$_SESSION['fb_friends']= NULL;;
$_SESSION['fb_graph']= NULL;
header("Location: index.php");
?>