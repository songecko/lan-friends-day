<?php

namespace Odiseo\LanBundle\Utils;



class TweetParser {
	
	private static $initialized = false;
 	private function __construct() {}
	
	
 	private static function initialize()
 	{
 		if (self::$initialized)
 			return;

 		self::$initialized = true;
 	}
	
	/**
	 * @param string $sTweet -> phrase to tweet.
	 * @return	array of mentioned friend -> prefixed with @ in function parameter
	 */
	public static function getMentionedFriends($sTweet){
		self::initialize();
		$matches = array();
		preg_match_all("/@([a-z0-9_]+)/i",$sTweet,$matches);
		return $matches[1];
	}
	
	/**
	 * @param string $sTweet
	 * @return array of mentiones hastags -> prefixed by # in function parameter
	 */
	
	public static function getMentionedHashTags($sTweet)
	{
		self::initialize();
		$matches = array();
		preg_match_all("/#([a-z0-9_]+)/i",$sTweet,$matches);
		return $matches[1];
	}
	
	/**
	 *  Tell you if $sHashTag is in $sTweet post.
	 * @param unknown $sTweet
	 * @param unknown $sHashTag
	 * @return boolean 
	 */
	public static  function existHashTag($sTweet , $sHashTag){
		self::initialize();
		$hashTags = self::getMentionedHashTags($sTweet);
		foreach ($hashTags as &$value) {
			if ($value == $sHashTag) 
				return true;
		}
		return false;
	}
	
	/**
	 * Tell you if the number of mentioned friends in $sTweet is equal to $quantity.
	 * @param unknown $sTweet
	 * @param unknown $quantity
	 * @return boolean
	 */
	
	public static  function mentionedFriendsEqualTo($sTweet , $quantity){
		self::initialize();
		$mentionatedFriends = self::getMentionedFriends($sTweet);
		return (count($mentionatedFriends) == $quantity);
	}
	
	
}
