<?php

namespace Odiseo\LanBundle\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Odiseo\LanBundle\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Odiseo\LanBundle\Utils\TweetParser;
use Odiseo\LanBundle\Entity\TwitterUser;
use Symfony\Component\HttpFoundation\Response;

class MainController extends Controller 
{	
	protected $configuration = null;
	
	public function indexAction(Request $request) 
	{
		$configuration = $this->getConfiguration();
		
		//Go to end of campaign?
		if($configuration->isCampaignFinished())
		{
			return $this->redirect($this->generateUrl('lan_thanks'));
		}else //else
		{
			if($this->_haveToGoToIndex())
			{
				return $this->render('OdiseoLanBundle:Frontend/Main:index.html.twig');
			}else 
			{
				return $this->redirect($this->generateUrl('lan_plane'));
			}
		}
	}
	
	public function thanksAction()
	{
		$configuration = $this->getConfiguration();
		if(!$configuration->isCampaignFinished())
		{
			return $this->redirect($this->generateUrl('odiseo_lan_frontend_homepage'));
		}
		
		return $this->render('OdiseoLanBundle:Frontend/Main:thanks.html.twig');
	}
	
	public function internoAction()
	{
		return $this->render('OdiseoLanBundle:Frontend/Main:interno.html.twig');
	}
	
	public function countdownAction()
	{
		$configuration = $this->getConfiguration();
		$user = $this->getUser();
		
		if( $configuration->isCampaignFinished() || ( $configuration->isCampaignActive() && (!$user || !$user->isRegistered())))
		{
			return new Response();
		}else 
		{
			return $this->render('OdiseoLanBundle:Frontend/Main:countdown.html.twig', array('configuration' => $configuration));
		}
	}
	
	public function avionAction()
	{
		return $this->_haveToGoToIndex() ? $this->redirect($this->generateUrl('odiseo_lan_frontend_homepage')) :  $this->render('OdiseoLanBundle:Frontend/Main:avion.html.twig') ;
	}
	
	protected function getConfiguration()
	{
		if(!$this->configuration)
		{
			$configurations = $this->get('lan.repository.configuration')->findAll();
			if(isset($configurations[0]))
				$this->configuration = $configurations[0];
		}
		
		return $this->configuration;
	}
	
	private function _haveToGoToIndex()
	{
		$configuration = $this->getConfiguration();
		
		$user = $this->getUser();
		if ( $user == null || !$user->isRegistered() || !$configuration || !$configuration->isCampaignActive()){
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
						$data = array('onError' => 'true', 'errors' => '¡Tenés que hacer mensajes distintos!');
						return new JsonResponse($data);
					}
					//grabar tweet en la base de datos.
					$data = array('onError' => 'false', 'message' => 'Gracias por participar!');
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
		if(  strlen($sToTweet) <= 140 )
		{
			if (TweetParser::existHashTag($sToTweet, "AmigosLan"))
			{
				$friends = TweetParser::getMentionedFriends($sToTweet);
				if (count($friends) == 3 )
				{
					if ( $this->_areDifferents($friends) ){
							return null;
					}
					else{
						return 'Debes citar 3 amigos diferentes.';
					}
					
				}
				else return 'Debes citar 3 amigos diferentes.';
			}
			else  return 'Debe citar el hashtag "#AmigosLan". ';
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
