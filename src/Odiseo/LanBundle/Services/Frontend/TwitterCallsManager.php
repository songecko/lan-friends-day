<?php

namespace Odiseo\LanBundle\Services\Frontend;
use TwitterAPIExchange;


class TwitterCallsManager {
	
	private $_consumer_key;
	private $_consumer_key_secret;
	
	
	public  function __construct($consumer_key , $consumer_key_secret)
	{
		$this->_consumer_key = $consumer_key;
		$this->_consumer_key_secret = $consumer_key_secret;
	}
	
	
	/**
	 * Updates the status in twitter of the user whose  $oauth_access_token and $oauth_access_token_secret
	 * are passed as parameters.
	 *
	 * @param unknown $oauth_access_token
	 * @param unknown $oauth_access_token_secret
	 * @return unknown
	 */
	
	public function updateUserStatus($sToTweet , $oauth_access_token , $oauth_access_token_secret ){
		$settings = array(
				'oauth_access_token' => $oauth_access_token ,
				'oauth_access_token_secret' => $oauth_access_token_secret,
				'consumer_key' => $this->_consumer_key,
				'consumer_secret' => $this->_consumer_key_secret
		);
		$url = 'https://api.twitter.com/1.1/statuses/update.json';
		$requestMethod = 'POST';
		
		$twitter = new TwitterAPIExchange($settings);
		$res =  $twitter->setPostfields(array('status' => $sToTweet))->buildOauth($url, $requestMethod)	->performRequest();
		return $res;
	}
	
	
	/**
	 * Validate if the screen names are following to the user whose  $oauth_access_token and $oauth_access_token_secret
	 * are passed as parameters.
	 * @param unknown $screen_name_users
	 * @return boolean true: valid , false: invalida
	 */
	public function isFollowingBy($aScreen_name_users, $oauth_access_token , $oauth_access_token_secret){
		
		$sScreen_name_users = implode(",", $aScreen_name_users);
		
		$settings = array(
				'oauth_access_token' => $oauth_access_token ,
				'oauth_access_token_secret' => $oauth_access_token_secret,
				'consumer_key' => $this->_consumer_key,
				'consumer_secret' => $this->_consumer_key_secret
		);
		
		$url = 'https://api.twitter.com/1.1/friendships/lookup.json';
		$requestMethod = 'GET';
		$twitter = new TwitterAPIExchange($settings);
		$res = json_decode( $twitter->setGetfield('?screen_name='.$sScreen_name_users)->buildOauth($url, $requestMethod)->performRequest());
		
		foreach ( $res as  $value ){
			if ( !($value->connections[0] == 'followed_by')  && !( isset( $value->connections[1] ) &&    $value->connections[1] == 'followed_by') ){
				return false;
			}
		}
		return true;
	}
	
		
	
}
