

<?php
session_start();
require_once __DIR__ . '/src/Facebook/autoload.php';

	$fb = new Facebook\Facebook
                      ([
                                 'app_id' => '0000000000000000',  //Your apps Id
                                'app_secret' => '******Your app secret *******',
                                'default_graph_version' => 'v2.6',
	]);

	$helper = $fb->getRedirectLoginHelper();

	try
                      {
                                //Get facebook access token from the session
                                if (isset($_SESSION['facebook_access_token'])) 
                                 {
                                            $accessToken = $_SESSION['facebook_access_token'];
                                 } 
                                 else 
                                 {
                                            $accessToken = $helper->getAccessToken();
                                 }
		
                                // Returns a `Facebook\FacebookResponse` object 
                                 //Here can get back the info which you need.
                                 $response = $fb->get('/me?fields=id,name,email,friends', $accessToken);
                                 $profile = $response->getGraphNode()->asArray();
		
                                 //use the taggable_friend to count the total number of friend
                                 $requestFriends = $fb->get('/me/taggable_friends?fields=name&limit=20', $accessToken);
                                 $friends = $requestFriends->getGraphEdge();		
		
	} 
                      catch(Facebook\Exceptions\FacebookResponseException $e) 
                      {
                                 echo 'Graph returned an error: ' . $e->getMessage();
                                 exit;
	} 
                      catch(Facebook\Exceptions\FacebookSDKException $e) 
                      {
                                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                                exit;
	}
	


	echo '<p>';
	
                      //Check that is it the $friend is null, if not, do it.
	if ($fb->next($friends)) 
                      {
                                 $allFriends = array();
                                 $friendsArray = $friends->asArray();
	           $allFriends = array_merge($friendsArray, $allFriends);

                                 while ($friends = $fb->next($friends)) 
                                 {

		$friendsArray = $friends->asArray();
		$allFriends = array_merge($friendsArray, $allFriends);
				
                                 }

	          echo sizeof($allFriends) . '';

			

	} 
                      else 
                      {
                                 //Get the total count from the friend array.
	           $allFriends = $friends->asArray();
	           $totalFriends = count($allFriends);
                                    
                                 //**** if your need to print out the friend list, unlock this.
                                 //Print out the friend list
                                /*
	           foreach ($allFriends as $key) 
                                 {
                                             echo $key['name'] . "";
	           }
                                 
                                 */

	}
	
                     //Print out the user profile.
	print_r($profile);
	