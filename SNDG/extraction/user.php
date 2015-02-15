<?php
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

/**
 * Class containing the user node
 */

class User {
    /** Class attributes **/
    
    protected $session;
    
    /* fields */
    
    
    protected $user_id="";
    //string
    protected $user_about="";
    //string
    protected $user_age_range;
    //object
    protected $user_bio="";
    //string
    protected $user_birthday="";
    //string
    protected $user_context;
    //object
    protected $user_cover;
    //CoverPhoto
    protected $user_cover_source;
    //string
    protected $user_currency;
    //object
    protected $user_devices;
    //object[]
    protected $user_education;
    //object[]
    protected $user_email="";
    //string
    protected $user_favorite_athletes;
    //Page[]
    protected $user_favorite_teams;
    //Page[]
    protected $user_first_name;
    //string
    protected $user_gender="";
    //string
    protected $user_hometown;
    //Page
    protected $user_hometown_string="";
    protected $user_inspirational_people;
    //Page[]
    protected $user_installed;
    //bool
    protected $user_is_verified;
    //bool
    protected $user_languages;
    //Page[]
    protected $user_last_name="";
    //string
    protected $user_link="";
    //string
    protected $user_locale="";
    //string
    protected $user_location;
    protected $user_location_string="";
    //Page
    protected $user_middle_name="";
    //string
    protected $user_name="";
    //string
    protected $user_name_format="";
    //string
    protected $user_political="";
    //string
    protected $user_quotes="";
    //string
    protected $user_relationship_status="";
    //string
    protected $user_religion="";
    //string
    protected $user_significant_other;
    //User
    protected $user_timezone;
    //int
    //protected $user_third_party_id;
    //string
    protected $user_verified;
    //bool
    protected $user_website="";
    //string
    protected $user_work;
    
    /* Edges */
    //object[]
    protected $friendList;
    //string
    protected $friendList_ids="";
    //int
    protected $friendTotalCount=0;
    
    /* Adjacency matrix */
    protected $adj_matrix;
    protected $adj_matrix_json;

    /* Class constructor */
    function __construct($user = 'me',$session) {
        $this->session=$session;//get the facebook session
        
        // graph api request for user data
        $request = new FacebookRequest($this->session, 'GET', '/' . $user.'?fields=id,name,email,first_name,last_name,middle_name,quotes,bio,cover,gender,locale,birthday,location,hometown,relationship_status,picture.type(large)&limit=100');
        $response = $request -> execute();
        
        // get response
        $graphObject = $response -> getGraphObject();
        
        // get fields
        $this -> user_id = $graphObject -> getProperty('id');
        $this -> user_about = $graphObject -> getProperty('about');
        $this -> user_age_range = $graphObject -> getProperty('age_range');
        $this -> user_bio = $graphObject -> getProperty('bio');
        $this -> user_birthday = $graphObject -> getProperty('birthday');
        $this -> user_context = $graphObject -> getProperty('context');
        $this -> user_cover = $graphObject -> getProperty('cover');
        $this -> user_currency = $graphObject -> getProperty('currency');
        $this -> user_devices = $graphObject -> getProperty('devices');
        $this -> user_education = $graphObject -> getProperty('education');
        $this -> user_email = $graphObject -> getProperty('email');
        $this -> user_favorite_athletes = $graphObject -> getProperty('favorite_athletes');
        $this -> user_favorite_teams = $graphObject -> getProperty('favorite_teams');
        $this -> user_first_name = $graphObject -> getProperty('first_name');
        $this -> user_gender = $graphObject -> getProperty('gender');
        $this -> user_hometown = $graphObject -> getProperty('hometown');
        $this -> user_inspirational_people = $graphObject -> getProperty('inspirational_people');
        $this -> user_installed = $graphObject -> getProperty('installed');
        $this -> user_is_verified = $graphObject -> getProperty('is_verified');
        $this -> user_languages = $graphObject -> getProperty('languages');
        $this -> user_last_name = $graphObject -> getProperty('last_name');
        $this -> user_link = $graphObject -> getProperty('link');
        $this -> user_locale = $graphObject -> getProperty('locale');
        $this -> user_location = $graphObject -> getProperty('location');
        $this -> user_middle_name = $graphObject -> getProperty('middle_name');
        $this -> user_name = $graphObject -> getProperty('name');
        $this -> user_name_format = $graphObject -> getProperty('name_format');
        $this -> user_political = $graphObject -> getProperty('political');
        $this -> user_quotes = $graphObject -> getProperty('quotes');
        $this -> user_relationship_status = $graphObject -> getProperty('relationship_status');
        $this -> user_religion = $graphObject -> getProperty('religion');
        $this -> user_significant_other = $graphObject -> getProperty('significant_other');
        $this -> user_timezone = $graphObject -> getProperty('timezone');
        //$this -> user_third_party_id = $graphObject -> getProperty('third_party_id'); <- is it useful ?
        $this -> user_verified = $graphObject -> getProperty('verified');
        $this -> user_website = $graphObject -> getProperty('website');
        $this -> user_work = $graphObject -> getProperty('work');

        // graph api request for user data
        $request = new FacebookRequest($session, 'GET', '/' . $this -> user_id . '/friends?fields=id,name,first_name,last_name,gender,locale,birthday,location,hometown,relationship_status,picture.type(large)&limit=100');
        $response = $request -> execute();
        // get response
        $graphObject = $response -> getGraphObject();
        $this -> friendList = $graphObject -> asArray();//transforms the graph object into an array
        
        //Put all of the friends' IDs into a string, in order to be saved in the DB
        foreach($this->friendList['data'] as $key => $value){
            $this->friendList_ids=$this->friendList_ids.$value->{"id"}.";";
        }
        $this -> friendTotalCount = $this -> friendList['summary']->{"total_count"};
        
        /* Get a relevant information from attributes that have a type different than int,string or bool*/
        if(isset($this->user_hometown)){
            $this->user_hometown_string=$this->user_hometown->getProperty('name');
        }
        
        if(isset($this->user_location)){
            $this->user_location_string=$this->user_location->getProperty('name');
        }
        
        if(isset($this->user_cover)){
            $this->user_cover_source=$this->user_cover->getProperty('source');//get the cover picture url
        }
    }

    public function makeAdjMatrix(){
        /* Creation of the adjacency matrix */
        $this->adj_matrix = array();
         
        /* Filling the adjacency matrix */
        $n=count($this -> friendList["data"]);//this is a nxn matrix, where n is the number of friends
        if($n>=2){//if you have less than 2 friends, then it's useless to build an adjacency matrix
        for($i=0;$i<$n;$i++){//lines of the matrix
          for($j=0;$j<$n;$j++){//columns of the matrix
              //you only need to know if a is friend with b, not that b is friend with a. This prevents double connected vertices in the friend network graph.
              if($j <= $i)
                $this->adj_matrix[$i][$j] = 0;
              else {
                $friendship = new FacebookRequest($this->session, 'GET', '/'.$this->friendList['data'][$i]->{"id"}.'/friends/'.$this->friendList['data'][$j]->{"id"});
                $friendship = $friendship -> execute() -> getGraphObject(GraphUser::className());
                $friendship = $friendship -> asArray();
                
                /* if a and b are friends, then the API should return basic information about b in friendship['data'].
                 * If there is nothing, then they are not friends */
                if(array_key_exists(0,$friendship['data'])){
                  $this->adj_matrix[$i][$j] = 1;
                }
                else{
                  $this->adj_matrix[$i][$j] = 0;
                }
              }
          }
        }
        }
        $this->adj_matrix_json=json_encode($this->adj_matrix);        
    }

    // Function allowing export of the class attributes (string, int and bool) to a MySQL DB
    public function saveToDB($host,$user,$pwd,$base){
        
        try

        {
        
            $db = new PDO('mysql:host='.$host.';dbname='.$base.';charset=utf8', $user, $pwd);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        }
        
        catch(Exception $e)
        
        {
        
                die('Error : '.$e->getMessage());
        
        }

        //get all of the class attributes in an associative array -> attributes['name of the attribute']=value
        $attributes=get_object_vars($this);
        
        //We just save attributes of the following types: int, string and bool. We just delete the others in the array.
        foreach($attributes as $key => $value){
            if((is_int($attributes[$key]) == false && is_bool($attributes[$key]) == false && is_string($attributes[$key]) == false) || isset($attributes[$key]) == false){
                unset($attributes[$key]);
            } 
        }
        
        //SQL Insert command
        $sql = sprintf('INSERT INTO FacebookUser (%s) VALUES ("%s")',implode(',',array_keys($attributes)),implode('","',array_values($attributes)));
        //echo $sql;
        
        //Execution of the command
        $db->exec($sql);
    }
    
    //This function returns a json designed to work with the D3.js graph rendering script
    public function formatGraph() {
        $newFriendList = array ();
        $i=0;
        $j=0;
        $newFriendList["nodes"][$i] -> {"name"} = "Me";//the first node is the user
        
        //Nodes are named according to the friend's names
        foreach ($this -> friendList["data"] as $element) {
            $i++;
            $name = $element -> {"name"};
            $newFriendList["nodes"][$i] -> {"name"} = $name;
        }
        
        /* Put the links between "Me" user and all of his friends */
        $k=0;
        for ($i = 0; $i < count ($this -> adj_matrix) ; $i++) {
            $newFriendList["links"][$i] ->  {"source"} = 0;
            $newFriendList["links"][$i] ->  {"target"} = $i+1;
            $k++;
        }
        
        //Put the links between the friends of the user according to the adjacency matrix        
        for ($i = 0; $i < count ($this -> adj_matrix) ; $i++) {
            for ($j = 0; $j < count ($this -> adj_matrix) ; $j++) {

                if ($this -> adj_matrix [$i][$j] == 1){
                    
                    $source = $i+1;
                    $target = $j+1;
                    $newFriendList["links"][$k] ->  {"source"} = $source;
                    $newFriendList["links"][$k] ->  {"target"} = $target;
                    $k++;
                }
            }
        }
        return json_encode($newFriendList);
    }

    /* Class attribute accessors */

    public function getName() {
        return $this -> user_name;
    }
        
    public function getEmail() {
        return $this -> user_email;
    }

    public function getID() {
        return $this -> user_id;
    }
    
    public function getGender() {
        return $this -> user_gender;
    }    
  
    public function getBio() {
        return $this -> user_bio;
    }    
    
    public function getBirthday() {
        return $this -> user_birthday;
    }    
    
    public function getHometown() {
        return $this -> user_hometown_string;
    }    

    public function getLocation() {
        return $this -> user_location_string;
    }
    
    public function getLocale() {
        return $this -> user_locale;
    }      

    public function getQuotes() {
        return $this -> user_quotes;
    }  

    public function getReligion() {
        return $this -> user_religion;
    }           
       
    public function getFriends($json=true) {
        if($json)
            return json_encode($this -> friendList);
        else
            return $this -> friendList;
    }
    
    public function getAdjMatrix($json=true) {
        if($json)
            return $this -> adj_matrix_json;
        else
            return $this -> adj_matrix;
    }


}
?>