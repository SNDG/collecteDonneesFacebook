<?php
/**
 * Class containing the user node
 */
class User {
    /* fields */
    protected $user_id;
    //string
    protected $user_about;
    //string
    protected $user_age_range;
    //object
    protected $user_bio;
    //string
    protected $user_birthday;
    //string
    protected $user_context;
    //object
    protected $user_cover;
    //CoverPhoto
    protected $user_currency;
    //object
    protected $user_devices;
    //object[]
    protected $user_education;
    //object[]
    protected $user_email;
    //string
    protected $user_favorite_athletes;
    //Page[]
    protected $user_favorite_teams;
    //Page[]
    protected $user_first_name;
    //string
    protected $user_gender;
    //string
    protected $user_hometown;
    //Page
    protected $user_inspirational_people;
    //Page[]
    protected $user_installed;
    //bool
    protected $user_is_verified;
    //bool
    protected $user_languages;
    //Page[]
    protected $user_last_name;
    //string
    protected $user_link;
    //string
    protected $user_locale;
    //string
    protected $user_location;
    //Page
    protected $user_middle_name;
    //string
    protected $user_name;
    //string
    protected $user_name_format;
    //string
    protected $user_political;
    //string
    protected $user_quotes;
    //string
    protected $user_relationship_status;
    //string
    protected $user_religion;
    //string
    protected $user_significant_other;
    //User
    protected $user_timezone;
    //int
    protected $user_third_party_id;
    //string
    protected $user_verified;
    //bool
    protected $user_website;
    //string
    protected $user_work;
    
    /* Edges */
    //object[]
    protected $friendList;

    function __construct($user = 'me') {
        // graph api request for user data
        $request = new FacebookRequest($session, 'GET', '/' . $user);
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
        $this -> user_third_party_id = $graphObject -> getProperty('third_party_id');
        $this -> user_verified = $graphObject -> getProperty('verified');
        $this -> user_website = $graphObject -> getProperty('website');
        $this -> user_work = $graphObject -> getProperty('work');

        // graph api request for user data
        $request = new FacebookRequest($session, 'GET', '/' . $this -> user_id . '/friends');
        $response = $request -> execute();
        // get response
        $graphObject = $response -> getGraphObject();
        $this -> friendList = $graphObject -> asArray();
    }

    public function getEmail() {
        return $this -> user_email;
    }

    public function getFriends() {
        return $this -> friendList;
    }
    
    public function makeAdjacencyMatrix(){
        /* Creation of the adjacency matrix */
        $adj_matrix = array();
         
        /* Filling the adjacency matrix */
        for($i=0;$i<n;$i++){
          for($j=0;$j<n;$j++){
            $friendship = new FacebookRequest($session, 'GET', '/'.$friendlist['data'][i]->{"id"}.'/friends/'.$friendlist['data'][j]->{"id"});
            $friendship = $friendship -> execute() -> getGraphObject(GraphUser::className());
            $friendship = $friendship -> asArray();
            if(array_key_exists(0,$friendship['data'])){
              $adj_matrix[i][j] = 1;
            }
            else{
              $adj_matrix[i][j] = 0;
            }
          }
        }        
    }

}
?>