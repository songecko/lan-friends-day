<?php

namespace Odiseo\LanBundle\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Odiseo\LanBundle\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Odiseo\LanBundle\Utils\TweetParser;
use Odiseo\LanBundle\Entity\TwitterUser;

class FlightController extends Controller 
{	
	public function showPassengersAction(Request $request) 
	{
		
		$repository = $this->get('lan.repository.user');
		$userRecords = $repository->lastUserWhoTweets();
		$seat = array();
 		foreach ($userRecords as &$record) {
 			$seat[] = $record->getTwitterProfileImageUrl();
		}
		$data = array('seatsImageUrl' => $seat);
		
		

		$repository = $this->get('lan.repository.twitteruser');
		$max_size_result = $this->container->getParameter('max_size_result_twitter');
		$userTwitterRecords = $repository->findLastTweets($max_size_result);
		
		
		$listTweets = array();
		
		foreach ($userTwitterRecords as &$record) {
			
			$tweets = array('imageUrl' => $record->getUser()->getTwitterProfileImageUrl(),
							'tweet' => $record->getTwitter() ,
							'screenName' => $record->getUser()->getUsername(),
							'timeAgo' =>	 $record->getCreatedAt());
			$listTweets[] = $tweets;
		}
		
		$data = array('seatsImageUrl' => $seat,  'tweets' =>  $listTweets);
		return new JsonResponse($data);
			
	
	}

}
