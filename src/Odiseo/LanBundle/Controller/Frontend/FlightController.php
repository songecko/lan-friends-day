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
		$em = $this->getDoctrine()->getManager ();
		$repositoryUser = $em->getRepository('OdiseoLanBundle:User');
		
		$repositoryTwitterUser = $this->get('lan.repository.twitteruser');
		
		
		$records = $repositoryTwitterUser->lastUserWhoTweets();
		$seats = array();

		foreach ($records as &$record) {
		
		if(is_array($record) && isset($record[0]))
			
			$userRecord = $repositoryUser->findOneBy(array('id' => $record['userId']));
			
			if($userRecord instanceof User)
 			{
 			$seatData = array('urlImage' => $userRecord->getTwitterProfileImageUrl(), 'twitterName' => $userRecord->getUsername() );
			$seats[] = $seatData;
			}
		}
		
		
		
		$max_size_result = $this->container->getParameter('max_size_result_twitter');
		$userTwitterRecords = $repositoryTwitterUser->findLastTweets($max_size_result);
		$listTweets = array();
		
		foreach ($userTwitterRecords as &$twiTrecord) {
			$tweets = array('imageUrl' => $twiTrecord->getUser()->getTwitterProfileImageUrl(),
							'tweet' => $twiTrecord->getTwitter() ,
							'screenName' => $twiTrecord->getUser()->getUsername(),
							'timeAgo' =>	 $twiTrecord->getCreatedAt());
			$listTweets[] = $tweets;
		}
		
		$data = array('seats' => $seats,  'tweets' =>  $listTweets);
		return new JsonResponse($data);
			
	
	}

}
