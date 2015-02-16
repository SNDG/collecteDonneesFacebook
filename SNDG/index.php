<?php
session_start();
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
$helper = new FacebookRedirectLoginHelper('http://localhost/SNDG/index.php');//callback URL
try {
    $session = $helper -> getSessionFromRedirect();
} catch( FacebookRequestException $ex ) {
    // When Facebook returns an error
} catch( Exception $ex ) {
    // When validation fails or other local issues
}

//Default page is index
if(!isset($_GET['page']))
    $_GET['page']="index";

//if we saved the token, then we can instantiate a Facebook Session without the need of the login helper
if(isset($_SESSION['token']))
    $session = new FacebookSession($_SESSION['token']);

if(isset($_GET['action'])){
    //login action
    if($_GET['action']=="login" && !isset($session)){
            //permissions
            $params = array('scope' => 'user_about_me, user_friends, email, user_birthday, user_hometown, user_location, user_religion_politics');
            $loginUrl = $helper -> getLoginUrl($params);
            header("Location: " . $loginUrl);
    }
}

//Once we have a user object from FB, we can save his information into PHP session variables
function setSessionVariables($user_var,$user_session)
{
    $_SESSION['token'] = $user_session->getToken();
    $_SESSION['fb_id']=$user_var->getID();
    $_SESSION['fb_name']=$user_var->getName();
    $_SESSION['fb_email']=$user_var->getEmail();
    $_SESSION['fb_gender']=$user_var->getGender();
    $_SESSION['fb_bio']=$user_var->getBio();
    $_SESSION['fb_birthday']=$user_var->getBirthday();
    $_SESSION['fb_hometown']=$user_var->getHometown();
    $_SESSION['fb_location']=$user_var->getLocation();
    $_SESSION['fb_locale']=$user_var->getLocale();
    $_SESSION['fb_adj_matrix']=$user_var->getAdjMatrix();
    $_SESSION['fb_friends']=$user_var->getFriends();
    $_SESSION['fb_graph']=$user_var->formatGraph();
}

//Creates the user object and saves user data into the DB once in a session time
if(isset($session) && !isset($user) && !isset($_SESSION['fb_id'])){
    $user=new User('me',$session);
    $user->makeAdjMatrix();
    $user->saveToDB('localhost','root','mysql','SNDG');
    setSessionVariables($user,$session);
}
?>
<!DOCTYPE HTML>
<html>

<head>
  <title>.:: SNDG ::. Social Network Data Gathering</title>
  <meta name="description" content="SNDG (Social Networks Data Gathering) is a tool which allows researchers or enthusiasts to gather data from social network services, and to visualize it afterwards. For now, the only SNS supported is Facebook. This is an academic project proposed by two professors of the LUSSI department in Telecom Bretagne, and conducted by two FIP students (students in an apprenticeship program)." />
  <meta name="keywords" content="SNDG, social network, facebook, Telecom Bretagne, Institut Mines-Telecom, LUSSI" />
  <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
  <link rel="stylesheet" type="text/css" href="style/style.css" />
  <!-- little piece of CSS needed for the D3.JS graph part -->
  <style>

    .node {
      stroke: #fff;
      stroke-width: 1.5px;
    }
    
    .link {
      stroke: #999;
      stroke-opacity: .6;
    }
    
    </style>
</head>

<body>
  <div id="main">
    <div id="header">
      <div id="logo">
        <div id="logo_text">
          <h1><a href="index.php"><span class="logo_colour">SNDG</span></a></h1>
          <h2>Social Network Data Gathering</h2>
        </div>
      </div>
      <div id="menubar">
        <ul id="menu">
          <li id="li_home" class="selected"><a href="index.php?page=index">Home</a></li>
          <li id="li_info"><a href="index.php?page=info">Information about the user</a></li>
          <li id="li_graph"><a href="index.php?page=graph">Friend Network Graph</a></li>
        </ul>
      </div>
    </div>
    <div id="content_header"></div>
    <div id="site_content">
      <div id="sidebar_container">
        <div class="sidebar"> <!-- Contains basic info about the user logged in (Profile picture, name) and login/logout link -->
          <div class="sidebar_top"></div>
          <div class="sidebar_item">
            <?php
            if (isset($session)) {?>
            <h3><?php echo $_SESSION['fb_name'];?></h3>
            <img src="https://graph.facebook.com/<?php echo $_SESSION['fb_id']; ?>/picture">
            <h4>Connected</h4>
            <p><a href="logout.php">Logout</a></p>
            <?php }
            else {              
                ?>
            <h3>Anon</h3>
            <h4>Not Connected</h4>
            <p><a href="index.php?action=login">Login with Facebook</a></p>
            <?php } ?>
          </div>
          <div class="sidebar_base"></div>
        </div>
      </div>
      
      <div id="content"><!-- Content of the page: this is the part which changes when clicking on another tab -->
          
          <!-- "Index" page -->
          
          <?php
          if($_GET['page']=="index"){?>
        <h1>Welcome to SNDG</h1>
        <p>SNDG (Social Networks Data Gathering) is a tool which allows researchers or enthusiasts to gather data from social network services, and to visualize it afterwards. For now, the only SNS supported is Facebook.</p>
        <p>This is an academic project proposed by two professors of the <a href="http://departements.telecom-bretagne.eu/lussi/">LUSSI</a> department in Telecom Bretagne, and developped by two <a href="http://www.telecom-bretagne.eu/formations/ingenieur_specialise/">FIP</a> students (engineering students in an apprenticeship program).</p>
        <h2>Research Description</h2>
        <p>Blabla:</p>
        <ul>
          <li>Blabla</li>
          <li>Blabla</li>
          <li>...</li>
        </ul>
        
        <!-- "Information about the user" page -->
        
        <?php }
          elseif($_GET['page']=="info"){
        ?>
        <!-- Piece of JS code to highlight the right tab-->
        <script>
            document.getElementById('li_info').setAttribute("class","selected");
            document.getElementById('li_home').removeAttribute("class");
            document.getElementById('li_home').removeAttribute("graph");
        </script>
        <h1>Information about the user</h1>
        <h2>ID</h2>
        <p><?php echo $_SESSION['fb_id'] ?></p>
        <h2>Email</h2>
        <p><?php echo $_SESSION['fb_email'] ?></p>
        <h2>Gender</h2>
        <p><?php echo $_SESSION['fb_gender'] ?></p> 
        <h2>Bio</h2>
        <p><?php echo $_SESSION['fb_bio'] ?></p>   
        <h2>Birthday</h2>
        <p><?php echo $_SESSION['fb_birthday'] ?></p>      
        <h2>Location</h2>
        <p><?php echo $_SESSION['fb_location'] ?></p>   
        <h2>Hometown</h2>
        <p><?php echo $_SESSION['fb_hometown'] ?></p>  
        <h2>Locale</h2>
        <p><?php echo $_SESSION['fb_locale'] ?></p>
        <h2>Friends</h2>
        <table style="width:100%; border-spacing:0;">
            <tr><th>No</th><th>Profile Picture</th><th>Name</th><th>ID</th></tr>
            <?php
            $n=0;
            $friends=json_decode($_SESSION['fb_friends'], true);
            foreach($friends['data'] as $friend) { 
            ?>
            <tr><td><?php echo $n++; ?></td><td><img src="<?php echo $friend["picture"]["data"]["url"];?>"></td><td><?php echo $friend["name"];?></td><td><?php echo $friend["id"];?></td></tr>
            <?php } ?>
        </table>   
        
        <!-- Graph page -->
        
        <?php }
          elseif($_GET['page']=="graph"){
        ?>
        <!-- Piece of JS code to highlight the right tab-->
        <script>
            document.getElementById('li_graph').setAttribute("class","selected");
            document.getElementById('li_home').removeAttribute("class");
            document.getElementById('li_info').removeAttribute("graph");
        </script>
        <h1>Friend Network Graph</h1>
        
        <!-- D3.JS graph rendering script -->
        <!-- Original script: http://bl.ocks.org/mbostock/4062045 -->
        <!-- Modified to show only one node color (no grouping) and to display names next to the nodes-->
        <script src="http://d3js.org/d3.v3.min.js"></script>
        <script>
        
        var width = 500,
            height = 300;
        
        var color = d3.scale.category20();
        
        var force = d3.layout.force()
            .charge(-120)
            .linkDistance(120)
            .size([width, height]);
        
        var svg = d3.select("#content").append("svg")
            .attr("width", width)
            .attr("height", height);
        
        var graph = <?php echo $_SESSION['fb_graph'];?>;
        
          force
              .nodes(graph.nodes)
              .links(graph.links)
              .start();
        
          var link = svg.selectAll(".link")
              .data(graph.links)
            .enter().append("line")
              .attr("class", "link")
              .style("stroke-width", function(d) { return Math.sqrt(d.value); });
        
          var node = svg.selectAll(".node")
              .data(graph.nodes)
            .enter().append("circle")
              .attr("class", "node")
              .attr("r", 5)
              .style("fill", function(d) { return color(1); })
              .call(force.drag);
        
          node.append("title")
              .text(function(d) { return d.name; });
              
        var texts = svg.selectAll("text.label")
                        .data(graph.nodes)
                        .enter().append("text")
                        .attr("class", "label")
                        .attr("fill", "black")
                        .text(function(d) {  return d.name;  });
                        
          force.on("tick", function() {
            link.attr("x1", function(d) { return d.source.x; })
                .attr("y1", function(d) { return d.source.y; })
                .attr("x2", function(d) { return d.target.x; })
                .attr("y2", function(d) { return d.target.y; });
        
            node.attr("cx", function(d) { return d.x; })
                .attr("cy", function(d) { return d.y; });
            
                texts.attr("transform", function(d) {
                    return "translate(" + d.x + "," + d.y + ")";
                });
          });
        
        </script>

        <?php } ?>
      </div>
    </div>
    
    <div id="content_footer"></div>
    
    <!-- Footer -->
    <div id="footer">
      <p><img src="images/TB_contour_quadri.jpg" alt="TB" height="64" width="64">&nbsp;<img src="images/INSTITUT-MINES-TELECOM_WEB.png" alt="Institut Mines-Telecom" height="64" width="64">&nbsp;</p>
      <p>SNDG | <a href="http://validator.w3.org/check?uri=referer">HTML5</a> | <a href="http://jigsaw.w3.org/css-validator/check/referer">CSS</a> | <a href="http://www.html5webtemplates.co.uk">Based on Simple Style 4 from HTML5webtemplates.co.uk</a></p>
    </div>
  </div>
</body>
</html>