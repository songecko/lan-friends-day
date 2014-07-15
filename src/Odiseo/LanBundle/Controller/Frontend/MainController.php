<?php

namespace Odiseo\LanBundle\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Odiseo\LanBundle\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;

use TwitterAPIExchange;

class MainController extends Controller {

	
	public function indexAction() {
		return $this->render ( 'OdiseoLanBundle:Frontend/Main:index.html.twig');
	}
	
	public function renderContentAction(Request $request) {
		
		$user = $this->getUser();
		if ($user == null) {
			return $this->render ( 'OdiseoLanBundle:Frontend/Main:participate.html.twig');
		} else
		 {
		 	$userRecord = $this->retrieveUserFromDb( $user->getTwitterId ());
			if ( $userRecord->getDni() != null) 
			{	
					return $this->render ( 'OdiseoLanBundle:Frontend/Main:registerForm.html.twig',	
						array ( 'fullName' => $userRecord->getFullName(),
					    'dni' =>  $userRecord->getDni(),
					    'edad' => $userRecord->getEdad(),
					    'telefono' => $userRecord->getTelefono(),
					   'nacionalidad' =>$userRecord->getNacionalidad(),
					    'email' => $userRecord->getMail() ) );
			} 
			else {
				return $this->render ( 'OdiseoLanBundle:Frontend/Main:registerForm.html.twig');
			
			
			}
		}
	}
	
	
	public function registerAction(Request $request) {
		
	
		$register = $request->get ( 'register' );
		$propertyUser = new User();
		$this->setUserProperties($propertyUser, $register);
		$validator = $this->get('validator');
		$errors = $validator->validate($propertyUser);
		if (count($errors) > 0) {
			$errorsMessages = array();
			foreach ( $errors as  $error )
			{
				array_push($errorsMessages,	$error->getMessage() );
			}
			$data = ['onError' => 'true', 'errors' => $errorsMessages];
			return new JsonResponse($data);
		}

		$this->saveUser($register);
		
		$data = ['onError' => 'false', 'message' => 'Gracias por participar!!'];
			
			return new JsonResponse($data);
	}
	
	public function sendTweetAction(Request $request)
	{
		//ldd($_SESSION['oauth_token']);
	//	$twitterClient = $this->container->get('guzzle.twitter.client');
		//$status = $twitterClient->get('statuses/user_timeline.json')->send()->getBody();
		$settings = array(
    		'oauth_access_token' => "1464708482-VKFrC8zo43dCBP17mj2LhTQdM5IE6Q0R7H4QKFf",
    		'oauth_access_token_secret' => "hHpRqUrtcmTZQWS9mttU4Qh3p6jBMordflVQ2HYSHJqNX",
    		'consumer_key' => "QJwsir2Mm2J1EFsP9F5NPXSGV",
    		'consumer_secret' => "PzqTb6Wix3wGeGLgPf2ghWL7hpOxIFtNM2XNS7rFrlxgHpSDcR"
		);
		
		//$url = 'https://api.twitter.com/1.1/friendships/lookup.json';
		$url = 'https://api.twitter.com/1.1/friendships/statuses/update.json';
		//$getfield = '?screen_name=songecko';
		$getfield = '?status=prueba';
		//$requestMethod = 'GET';
		$requestMethod = 'POST';
		
		$twitter = new TwitterAPIExchange($settings);
		
		$res =  $twitter->setGetfield($getfield)
		->buildOauth($url, $requestMethod)
		->performRequest();
		ldd($res);
		return $this->render('OdiseoLanBundle:Frontend/Main:test.html.twig', array(
				'status' => $status
		));
	}
	
	private function saveUser($register){
		$user = $this->getUser ();
		$userRecord = $this->retrieveUserFromDb( $user->getTwitterId ());
		$this->setUserProperties($userRecord , $register);
		$this->getDoctrine ()->getManager ()->flush();
	}
	
	private function retrieveUserFromDb($twitterId){
		
		$em = $this->getDoctrine ()->getManager ();
		$repository = $em->getRepository('OdiseoLanBundle:User' );
		$userRecord = $repository->findOneBy ( array ('twitter_id' => $twitterId ) );
		return $userRecord;
	}
	
	private function setUserProperties($userRecord , $register){
		$userRecord->setFullName ( $register ['fullname'] );
		$userRecord->setDni ( $register ['dni'] );
		$userRecord->setEdad ( $register ['edad'] );
		$userRecord->setTelefono ( $register ['telefono'] );
		$userRecord->setNacionalidad ( $register ['nacionalidad'] );
		$userRecord->setMail ( $register ['mail'] );
	}
	
	
}
