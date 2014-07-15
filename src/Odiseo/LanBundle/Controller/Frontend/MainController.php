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
		$settings = array(
    		'oauth_access_token' => "16963100-tcoizpaLQGm4KygHh6r9bbIZAcxbQSgWU7xa9SAnD",
    		'oauth_access_token_secret' => "gXxufSn2m9B9SsWrcu52pGg3drnj6zIkYMYt06A1dGYJ0",
    		'consumer_key' => "DjLQ9OM87GAPn6eTobxEnWAxz",
    		'consumer_secret' => "2bCmeQF6SPI5HAB2XpNVzx47pg2DT8cpATiJtkSMePQ8XOeWOw"
		);
		
		$url = 'https://api.twitter.com/1.1/statuses/update.json';
		$requestMethod = 'POST';
		
		$twitter = new TwitterAPIExchange($settings);
		
		$res =  $twitter->setPostfields(array('status' => 'nuevo post'))
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
