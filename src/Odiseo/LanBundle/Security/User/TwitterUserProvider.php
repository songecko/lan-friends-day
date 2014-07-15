<?php
namespace Odiseo\LanBundle\Security\User;
 
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseClass;
use Symfony\Component\Security\Core\User\UserInterface;
use TwitterAPIExchange;

class TwitterUserProvider extends BaseClass
{
    /**
     * {@inheritDoc}
     */
    public function connect(UserInterface $user, UserResponseInterface $response)
    {
        $property = $this->getProperty($response);
        $username = $response->getUsername();
        //on connect - get the access token and the user ID
        $service = $response->getResourceOwner()->getName();
 
        $setter = 'set'.ucfirst($service);
        $setter_id = $setter.'Id';
        $setter_token = $setter.'AccessToken';
 
        //we "disconnect" previously connected users
        if (null !== $previousUser = $this->userManager->findUserBy(array($property => $username))) {
            $previousUser->$setter_id(null);
            $previousUser->$setter_token(null);
            $this->userManager->updateUser($previousUser);
        }
 
        //we connect current user
        $user->$setter_id($username);
        $user->$setter_token($response->getAccessToken());
 
        $this->userManager->updateUser($user);
    }
 
    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $username = $response->getUsername();
        $attr = $response->getResponse();
        //ldd($response);
        $user = $this->userManager->findUserBy(array($this->getProperty($response) => $username));
        
        //when the user is registrating
        if (null === $user) {
        	$twitterName = isset($attr['screen_name'])?$attr['screen_name']:$username;
        	$twitterProfileImageUrl = isset($attr['profile_image_url'])?$attr['profile_image_url']:'';
        	
            $service = $response->getResourceOwner()->getName();
            $setter = 'set'.ucfirst($service);
            $setter_id = $setter.'Id';
            $setter_token = $setter.'AccessToken';
            // create new user here
            $user = $this->userManager->createUser();
            $user->$setter_id($username);
            $user->$setter_token($response->getAccessToken());
            
            echo $response->getAccessToken();
            echo '<br><br>';
            echo $response->getTokenSecret();
            $settings = array(
            		'oauth_access_token' => $response->getAccessToken(),
            		'oauth_access_token_secret' => $response->getTokenSecret(),
            		'consumer_key' => "DjLQ9OM87GAPn6eTobxEnWAxz",
            		'consumer_secret' => "2bCmeQF6SPI5HAB2XpNVzx47pg2DT8cpATiJtkSMePQ8XOeWOw"
            );
            
            //$url = 'https://api.twitter.com/1.1/friendships/lookup.json';
            $url = 'https://api.twitter.com/1.1/statuses/update.json';
            //$url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
            //$getfield = '?screen_name=songecko';
            $getfield = '?status=prueba';
            //$requestMethod = 'GET';
            $requestMethod = 'POST';
            
            $twitter = new TwitterAPIExchange($settings);
            
            $res =  $twitter
            ->buildOauth($url, $requestMethod)
            ->performRequest();
            echo($res);die;
            
            //I have set all requested data with the user's username
            //modify here with relevant data
            $user->setUsername($twitterName);
            $user->setEmail('none@email.com');
            $user->setPassword(md5(time()));
            $user->setEnabled(true);
            $user->setTwitterProfileImageUrl($twitterProfileImageUrl);
            $this->userManager->updateUser($user);
            return $user;
        }
 
        //if user exists - go with the HWIOAuth way
        $user = parent::loadUserByOAuthUserResponse($response);
 
        $serviceName = $response->getResourceOwner()->getName();
        $setter = 'set' . ucfirst($serviceName) . 'AccessToken';
 
        //update access token
        $user->$setter($response->getAccessToken());
 
        return $user;
    }
}