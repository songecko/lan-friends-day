<?php

namespace Odiseo\LanBundle\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Odiseo\LanBundle\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Odiseo\LanBundle\Utils\TweetParser;
use Odiseo\LanBundle\Entity\TwitterUser;

class MainController extends Controller 
{	
	public function indexAction(Request $request) 
	{
		return $this->render('OdiseoLanBundle:Frontend/Main:index.html.twig');
	}
	
	public function internoAction()
	{
		return $this->render('OdiseoLanBundle:Frontend/Main:interno.html.twig');
	}
	
	public function countdownAction()
	{
		return $this->render('OdiseoLanBundle:Frontend/Main:countdown.html.twig');
	}
	
	public function avionAction()
	{
		return $this->render('OdiseoLanBundle:Frontend/Main:avion.html.twig');
	}
	
	public function renderContentAction(Request $request) {
	
		$user = $this->getUser();
		if ($user == null) 
		{
			return $this->render('OdiseoLanBundle:Frontend/Main:participate.html.twig');
		} else
		{
			$userRecord = $this->retrieveUserFromDb($user->getTwitterId());
			if ($userRecord->getDni() == null)
			{
				return $this->render ( 'OdiseoLanBundle:Frontend/Main:registerForm.html.twig',
						array ( 'fullName' => $userRecord->getFullName(),
								'dni' =>  $userRecord->getDni(),
								'edad' => $userRecord->getEdad(),
								'telefono' => $userRecord->getTelefono(),
								'nacionalidad' =>$userRecord->getNacionalidad(),
								'email' => $userRecord->getMail() ) );
			}
			else 
			{
				return $this->render ( 'OdiseoLanBundle:Frontend/Main:registerForm.html.twig');
			}
		}
	}
	
	public function registerAction(Request $request) 
	{
		$register = $request->get('register');
		
		$user = $this->getUser();
		$this->setUserProperties($user, $register);
		
		$validator = $this->get('validator');
		$errors = $validator->validate($user);
		
		if (count($errors) > 0) 
		{
			$errorsMessages = array();
			foreach ($errors as  $error)
			{
				array_push($errorsMessages,	$error->getMessage());
			}
			
			//$data = ['onError' => 'true', 'errors' => $errorsMessages];			
			//return new JsonResponse($data);		
			return $this->redirect($this->generateUrl('odiseo_lan_frontend_homepage'));
		}

		$this->getDoctrine()->getManager()->flush();
		$this->get('lan.send.mailer')->sendRegisterMail($user);
		
		//$data = ['onError' => 'false', 'message' => 'Gracias por participar!!'];	
		//return new JsonResponse($data);
		return $this->redirect($this->generateUrl('odiseo_lan_frontend_homepage'));
	}
	
	
	public function sendTweetAction(Request $request)
	{
		$callsManager = $this->get('lan.services.twittercallsmanager');
		$formData = 	$request->get ( 'form_data' );
		if ($formData != null ){
			$sToTweet = $formData['tweet'];
			if ($sToTweet != null)
			{
				$error = $this->_validateRulesForTweet($sToTweet);
				if ( $error == null)
				{
				
					$this->_saveTwitterUser( $sToTweet);
					$tweets = json_decode($callsManager->updateUserStatus($sToTweet, '1464708482-BBkQfAWzaZynYuVHCQ14yaydAgq2lXrEOeJgxaW','zAZhIm1giH5CgrKaEjNl7kBfsre1kTLP70ShGmiI5FAet'));
					
					if (  isset($tweets->errors))
					{
						$data = array('onError' => 'true', 'errors' => 'Ocurrió un error, intenta luego.');
						return new JsonResponse($data);
					}
					//grabar tweet en la base de datos.
					$data = array('onError' => 'false', 'message' => 'Gracias por participar!!');
					return new JsonResponse($data);
				}
				else{
					
					$data = array('onError' => 'true', 'errors' => $error);
					return new JsonResponse($data);
				}
			}
			else
			{	
				$data = array('onError' => 'true', 'errors' => 'Tu tweet no puede estar vacio.');
				return new JsonResponse($data);
			}
		}
		else
		{
			return $this->render('OdiseoLanBundle:Frontend/Main:send_tweet.html.twig');
		}
	}
	
	/**
	 * to be valid: 3 friends mentioned and existing, and "AmigosLan" as hashTag
	 * @param unknown $sToTweet
	 * @return boolean
	 */
	private function _validateRulesForTweet($sToTweet)
	{
		if (TweetParser::existHashTag($sToTweet, "AmigosLan"))
		{
			
			$friends = TweetParser::getMentionedFriends($sToTweet);
			if (count($friends) == 3 )
			{
				if ( $this->_areDifferents($friends) ){
					$callsManager = $this->get('lan.services.twittercallsmanager');
					
					if ($callsManager->isFollowingBy($friends, '1464708482-BBkQfAWzaZynYuVHCQ14yaydAgq2lXrEOeJgxaW','zAZhIm1giH5CgrKaEjNl7kBfsre1kTLP70ShGmiI5FAet') )
						return null;
					else {
						return "Los amigos citados te deben seguir.";
					}
				}
				else{
					return 'Debes citar 3 amigos diferentes.';
				}
				
			}
			else return 'Debes citar 3 amigos diferentes.';
		}
		else  return 'Debe citar el hashtag "AmigosLan". ';
	}
	
	private function _areDifferents($friends){
		
		return ( ($friends[0] != $friends[1] ) && ($friends[0] != $friends[2] ) && 
				 ($friends[1] != $friends[2] )	);
		
	}
	
	private function retrieveUserFromDb($twitterId)
	{
		$em = $this->getDoctrine()->getManager ();
		$repository = $em->getRepository('OdiseoLanBundle:User');
		$userRecord = $repository->findOneBy(array('twitter_id' => $twitterId));
		
		return $userRecord;
	}
	
	private function setUserProperties($userRecord , $register)
	{
		$userRecord->setFullName($register['fullname']);
		$userRecord->setDni($register['dni']);
		$userRecord->setEdad($register['edad']);
		$userRecord->setTelefono($register['telefono']);
		$userRecord->setProvincia($register['provincia']);
		$userRecord->setMail($register['mail']);
		$userRecord->setAcceptNewsletter(isset($register['accept_newsletter'])?true:false);
	}
	
	private function _saveTwitterUser( $sToTweet){
		$user = $this->getUser();
		$em = $this->getDoctrine()->getManager ();
		$twitterUser = new TwitterUser();
		$twitterUser->setUser($user);
		$twitterUser->setTwitter($sToTweet);
		$em->persist($twitterUser);
		$em->flush();
	}
}
