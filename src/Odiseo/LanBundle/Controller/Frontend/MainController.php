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
<<<<<<< HEAD
		return $this->_haveToGoToIndex() ?  $this->render('OdiseoLanBundle:Frontend/Main:index.html.twig')  :   $this->redirect($this->generateUrl('lan_plane')) ;
=======
	return $this->_haveToGoToIndex() ?  $this->render('OdiseoLanBundle:Frontend/Main:index.html.twig')  :   $this->redirect($this->generateUrl('lan_plane')) ;
>>>>>>> 4dbccd5fe067271e6d85a0e081e5115a5e3e4f6a
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
		return $this->_haveToGoToIndex() ? $this->redirect($this->generateUrl('odiseo_lan_frontend_homepage')) :  $this->render('OdiseoLanBundle:Frontend/Main:avion.html.twig') ;
	}
	
	private function _haveToGoToIndex()
	{
		$configuration = null; 
		$configurations = $this->get('lan.repository.configuration')->findAll();
		if(isset($configurations[0]))
			$configuration = $configurations[0];
		
		$user = $this->getUser();
		if ( $user == null || !$user->isRegistered() || !$configuration || !$configuration->isCampaignActive() ){
			return true;
		}
		return false;
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
		if (  $this->_haveToGoToIndex() ){
			$data = array('onError' => 'true', 'errors' => 'Ocurrió un error, intenta luego.');
			return new JsonResponse($data);
		}
		
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
					$tweets = json_decode($callsManager->updateUserStatus($sToTweet, $_SESSION['twitter_access_token'], $_SESSION['twitter_token_secret']));
					
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
<<<<<<< HEAD
		if(  strlen($sToTweet) <= 140 )
		{
=======
	if(  strlen($sToTweet) <= 140 ){
>>>>>>> 4dbccd5fe067271e6d85a0e081e5115a5e3e4f6a
		if (TweetParser::existHashTag($sToTweet, "AmigosLan"))
			{
				$friends = TweetParser::getMentionedFriends($sToTweet);
				if (count($friends) == 3 )
				{
					if ( $this->_areDifferents($friends) ){
						$callsManager = $this->get('lan.services.twittercallsmanager');
						
<<<<<<< HEAD
						if ($callsManager->isFollowingBy($friends, $_SESSION['twitter_access_token'], $_SESSION['twitter_token_secret']) )
=======
						if ($callsManager->isFollowingBy($friends, '1464708482-BBkQfAWzaZynYuVHCQ14yaydAgq2lXrEOeJgxaW','zAZhIm1giH5CgrKaEjNl7kBfsre1kTLP70ShGmiI5FAet') )
>>>>>>> 4dbccd5fe067271e6d85a0e081e5115a5e3e4f6a
							return null;
						else {
							return "Los amigos citados te deben seguir.";
						}
<<<<<<< HEAD
					}
					else{
						return 'Debes citar 3 amigos diferentes.';
=======
>>>>>>> 4dbccd5fe067271e6d85a0e081e5115a5e3e4f6a
					}
					else{
						return 'Debes citar 3 amigos diferentes.';
					}
					
				}
				else return 'Debes citar 3 amigos diferentes.';
			}
<<<<<<< HEAD
			else  return 'Debes citar el hashtag "#AmigosLan". ';
=======
			else  return 'Debe citar el hashtag "AmigosLan". ';
>>>>>>> 4dbccd5fe067271e6d85a0e081e5115a5e3e4f6a
		}
		else return 'El tweet no puede superar los 140 caracteres. ';
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
